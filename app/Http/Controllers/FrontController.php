<?php

namespace App\Http\Controllers;

use App\Enums\CustomTransactionStatus;
use App\Enums\RentalTransactionStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Comment;
use App\Models\CustomTransaction;
use App\Models\Product;
use App\Models\RentalTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class FrontController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['booking_save', 'storeCustomOrder', 'uploadCustomPaymentProof', 'cancelCustomOrder', 'approveCustomOrder', 'storeComment', 'cancelRentalOrder']);
    }

    public function index(Request $request)
    {
        $availableSizes = \App\Models\ProductSize::distinct()->pluck('size')->sort()->values()->all();
        $query = Product::with('productSizes');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->filled('size')) {
            $query->whereHas('productSizes', function ($query) use ($request) {
                $query->where('size', $request->input('size'));
            });
        }
        if ($request->filled('sort_price')) {
            $sortDirection = $request->input('sort_price') === 'asc' ? 'asc' : 'desc';
            $query->orderBy('price', $sortDirection);
        } else {
            $query->latest();
        }
        // Paginate instead of loading every product at once; withQueryString()
        // keeps the search / size / sort filters on the pagination links.
        $products = $query->paginate(12)->withQueryString();
        return view('front.index', compact('products', 'availableSizes'));
    }

    public function details(Product $product)
    {
        $product->load(['productSizes', 'photos']);
        return view('front.details', compact('product'));
    }

    public function booking(Product $product)
    {
        return view('front.booking', compact('product'));
    }

    public function booking_save(StoreBookingRequest $request, Product $product)
    {
        // Ambil data booking dari request
        $data = $request->only(['selected_size', 'duration', 'started_at', 'name', 'phone_number']);
        // Redirect ke halaman checkout dengan data booking sebagai query string
        return redirect()->route('front.checkout', [
            'product' => $product->slug,
            'selected_size' => $data['selected_size'],
            'duration' => $data['duration'],
            'started_at' => $data['started_at'],
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
        ]);
    }

    public function checkout(Product $product, Request $request)
    {
        $selectedSize = $request->query('selected_size');
        $duration = (int) $request->query('duration');
        $startedAt = $request->query('started_at');
        $name = $request->query('name');
        $phoneNumber = $request->query('phone_number');
        $productSize = $product->productSizes()->where('size', $selectedSize)->first();
        // The checkout page is reached via query string, so a tampered URL
        // could carry duration 0 / negative or a size that does not exist.
        // Send the user back to booking instead of showing a Rp 0 total.
        // Also refuse a size whose stock is already 0: without this the
        // user only finds out in checkout_store, after uploading proof.
        if ($duration < 1 || !$productSize || $productSize->stock < 1) {
            return redirect()->route('front.booking', $product->slug)
                ->withErrors(['error' => 'Invalid booking details. Please choose a size and duration again.']);
        }
        try {
            $startedDate = $startedAt ? Carbon::parse($startedAt) : now();
        } catch (\Exception $e) {
            $startedDate = now();
        }
        $endedDate = $startedDate->copy()->addDays($duration);
        $subTotal = $product->price * $duration;
        $grandTotal = $subTotal;
        $bankAccounts = [
            'BCA' => '5545011970 (Niken Alfinanda Putri)',
            'BRI' => '626801015467534 (Niken Alfinanda Putri)',
        ];
        return view('front.checkout', compact('product', 'selectedSize', 'duration', 'startedDate', 'endedDate', 'name', 'phoneNumber', 'subTotal', 'grandTotal', 'bankAccounts'));
    }

    public function checkout_store(StorePaymentRequest $request)
    {
        $product = Product::where('slug', $request->product_slug)->firstOrFail();
        $selectedSize = $request->selected_size;
        $duration = (int) $request->duration;
        $startedDate = Carbon::parse($request->started_at);
        $endedDate = Carbon::parse($request->ended_at);
        $totalAmount = $product->price * $duration;

        // Stock pre-check BEFORE storing the proof file: the locked
        // check inside the DB transaction below is authoritative, but
        // without this the proof was stored first and left as an orphan
        // file whenever the size turned out to be out of stock. The
        // proof is still stored before touching the database, so a
        // corrupt image fails before any order is created.
        $earlySize = $product->productSizes()->where('size', $selectedSize)->first();
        if (!$earlySize || $earlySize->stock <= 0) {
            throw ValidationException::withMessages(['selected_size' => 'Selected size is out of stock.']);
        }

        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        $this->optimizeUploadedImage($proofPath);

        // Cek apakah ini re-upload payment proof untuk transaksi yang sudah ada (PAYMENT_FAILED)
        $existing = RentalTransaction::where([
            'product_id' => $product->id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'selected_size' => $selectedSize,
            'started_at' => $startedDate,
            'ended_at' => $endedDate,
        ])->where('status', \App\Enums\RentalTransactionStatus::PAYMENT_FAILED)->first();

        if ($existing) {
            if ($existing->payment_proof) {
                Storage::disk('public')->delete($existing->payment_proof);
            }
            $existing->update([
                'payment_proof' => $proofPath,
                'payment_method' => $request->payment_method,
                'status' => \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION,
            ]);
            return redirect()->route('front.success.booking', $existing->trx_id);
        }

        // Stock check + decrement happen inside one transaction with a row
        // lock: previously the stock was checked outside the transaction,
        // so two simultaneous checkouts for the last unit could both pass
        // the check and oversell the size.
        $rentalTransaction = null;
        DB::transaction(function () use ($request, $product, $selectedSize, $startedDate, $endedDate, $totalAmount, $proofPath, &$rentalTransaction) {
            $productSize = $product->productSizes()->where('size', $selectedSize)->lockForUpdate()->firstOrFail();
            if ($productSize->stock <= 0) {
                throw ValidationException::withMessages(['selected_size' => 'Selected size is out of stock.']);
            }
            $rentalTransaction = \App\Models\RentalTransaction::create([
                'trx_id' => 'RENTAL-' . \Str::random(8),
                'user_id' => \Auth::id(),
                'product_id' => $product->id,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'selected_size' => $selectedSize,
                'started_at' => $startedDate,
                'ended_at' => $endedDate,
                'total_amount' => $totalAmount,
                'payment_proof' => $proofPath,
                'payment_method' => $request->payment_method,
                'status' => \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION,
            ]);
            $productSize->decrement('stock');
        });
        return redirect()->route('front.success.booking', $rentalTransaction->trx_id);
    }

    public function success_booking($transactionId)
    {
        $transaction = RentalTransaction::where('trx_id', $transactionId)->first();
        if (!$transaction) {
            // A custom transaction has no product, and this view reads
            // $transaction->product — previously that crashed with a 500.
            $custom = CustomTransaction::where('trx_id', $transactionId)->first();
            if ($custom) {
                return redirect()->route('front.custom.success', $custom->trx_id);
            }
            abort(404);
        }
        $this->authorizeTransactionOwner($transaction);
        return view('front.success_booking', compact('transaction'));
    }

    public function transactions()
    {
        return view('front.transactions');
    }

    public function transactions_details(Request $request)
    {
        $request->validate([
            'trx_id' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
        ]);
        $trx_id = $request->input('trx_id');
        $phone_number = $request->input('phone_number');
        $rentalDetails = RentalTransaction::with('product')
            ->where('trx_id', $trx_id)
            ->where('phone_number', $phone_number)
            ->first();
        if ($rentalDetails) {
            $subTotal = $rentalDetails->product->price * $rentalDetails->duration;
            $grandTotal = $rentalDetails->total_amount;
            return view('front.transaction_details', compact('subTotal', 'grandTotal'))->with('details', $rentalDetails);
        }
        $customDetails = CustomTransaction::where('trx_id', $trx_id)
            ->where('phone_number', $phone_number)
            ->first();
        if ($customDetails) {
            // Render directly instead of redirecting: the custom details
            // route is owner-only, so a redirect would 403 the very user
            // who just proved ownership with their phone number.
            return view('front.custom_transaction_details', ['details' => $customDetails]);
        }
        return back()->withErrors(['error' => 'Transactions not found.']);
    }

    public function custom()
    {
        return view('front.custom');
    }

    public function storeCustomOrder(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'image_reference_1' => ['required', 'image', 'mimes:jpg,png'],
            'image_reference_2' => ['nullable', 'image', 'mimes:jpg,png'],
            'image_reference_3' => ['nullable', 'image', 'mimes:jpg,png'],
            'material' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'size_chart_option' => ['required', 'string', 'in:S,M,L,XL,custom'],
            'lebar_bahu_belakang' => ['nullable', 'numeric', 'required_if:size_chart_option,custom'],
            'lingkar_panggul' => ['nullable', 'numeric', 'required_if:size_chart_option,custom'],
            'lingkar_pinggul' => ['nullable', 'numeric', 'required_if:size_chart_option,custom'],
            'lingkar_dada' => ['nullable', 'numeric', 'required_if:size_chart_option,custom'],
            'kerung_lengan' => ['nullable', 'numeric', 'required_if:size_chart_option,custom'],
            'kebaya_preference' => ['required', 'string'],
            'amount_to_buy' => ['required', 'integer', 'min:1', 'max:15'],
            'date_needed' => ['required', 'date', 'after_or_equal:today'],
        ], [], [
            // Friendly English names for the validation errors: without
            // these, a failed custom-size field shows its Indonesian
            // column name (e.g. "The lebar bahu belakang field ...").
            'lebar_bahu_belakang' => 'back shoulder width',
            'lingkar_panggul' => 'hip circumference',
            'lingkar_pinggul' => 'waist circumference',
            'lingkar_dada' => 'bust circumference',
            'kerung_lengan' => 'armhole circumference',
            'size_chart_option' => 'size',
        ]);

        $validatedData = $request->only([
            'full_name',
            'phone_number',
            'material',
            'color',
            'kebaya_preference',
            'amount_to_buy',
            'date_needed',
        ]);

        $validatedData['selected_size_chart'] = $request->input('size_chart_option');

        $standardSizes = [
            'S' => [
                'lebar_bahu_belakang' => 36,
                'lingkar_panggul' => 88,
                'lingkar_pinggul' => 66,
                'lingkar_dada' => 86,
                'kerung_lengan' => 42,
            ],
            'M' => [
                'lebar_bahu_belakang' => 38,
                'lingkar_panggul' => 96,
                'lingkar_pinggul' => 72,
                'lingkar_dada' => 92,
                'kerung_lengan' => 44,
            ],
            'L' => [
                'lebar_bahu_belakang' => 39,
                'lingkar_panggul' => 108,
                'lingkar_pinggul' => 78,
                'lingkar_dada' => 98,
                'kerung_lengan' => 48,
            ],
            'XL' => [
                'lebar_bahu_belakang' => 40,
                'lingkar_panggul' => 112,
                'lingkar_pinggul' => 84,
                'lingkar_dada' => 104,
                'kerung_lengan' => 50,
            ],
        ];

        if (array_key_exists($validatedData['selected_size_chart'], $standardSizes)) {
            $selectedStandardSize = $standardSizes[$validatedData['selected_size_chart']];
            $validatedData['lebar_bahu_belakang'] = $selectedStandardSize['lebar_bahu_belakang'];
            $validatedData['lingkar_panggul'] = $selectedStandardSize['lingkar_panggul'];
            $validatedData['lingkar_pinggul'] = $selectedStandardSize['lingkar_pinggul'];
            $validatedData['lingkar_dada'] = $selectedStandardSize['lingkar_dada'];
            $validatedData['kerung_lengan'] = $selectedStandardSize['kerung_lengan'];
        } elseif ($validatedData['selected_size_chart'] === 'custom') {
            $validatedData['lebar_bahu_belakang'] = $request->input('lebar_bahu_belakang');
            $validatedData['lingkar_panggul'] = $request->input('lingkar_panggul');
            $validatedData['lingkar_pinggul'] = $request->input('lingkar_pinggul');
            $validatedData['lingkar_dada'] = $request->input('lingkar_dada');
            $validatedData['kerung_lengan'] = $request->input('kerung_lengan');
        } else {
            $validatedData['lebar_bahu_belakang'] = null;
            $validatedData['lingkar_panggul'] = null;
            $validatedData['lingkar_pinggul'] = null;
            $validatedData['lingkar_dada'] = null;
            $validatedData['kerung_lengan'] = null;
        }

        $validatedData['name'] = $validatedData['full_name'];
        unset($validatedData['full_name']);

        $image1Path = $request->file('image_reference_1')->store('custom_kebaya_references', 'public');
        $this->optimizeUploadedImage($image1Path);  // Optimize image with Intervention Image
        $validatedData['image_reference'] = $image1Path;

        if ($request->hasFile('image_reference_2')) {
            $image2Path = $request->file('image_reference_2')->store('custom_kebaya_references', 'public');
            $this->optimizeUploadedImage($image2Path);  // Optimize image with Intervention Image
            $validatedData['image_reference_2'] = $image2Path;
        }

        if ($request->hasFile('image_reference_3')) {
            $image3Path = $request->file('image_reference_3')->store('custom_kebaya_references', 'public');
            $this->optimizeUploadedImage($image3Path);  // Optimize image with Intervention Image
            $validatedData['image_reference_3'] = $image3Path;
        }

        $validatedData['user_id'] = Auth::id();

        $validatedData['trx_id'] = 'CUSTOM-' . Str::random(8);

        $customTransaction = CustomTransaction::create($validatedData);

        return redirect()->route('front.custom.success', $customTransaction->trx_id);
    }

    public function contact()
    {
        $comments = Comment::latest()->paginate(10);  // Mengambil data komentar dari database
        return view('front.contact', compact('comments'));  // Mengirim data ke tampilan
    }

    public function customTransactionDetails($transactionId)
    {
        $details = CustomTransaction::where('trx_id', $transactionId)->first();
        if (!$details) {
            abort(404);
        }
        $this->authorizeTransactionOwner($details);
        return view('front.custom_transaction_details', compact('details'));
    }

    public function customSuccess($transactionId)
    {
        $transaction = CustomTransaction::where('trx_id', $transactionId)->first();
        if (!$transaction) {
            abort(404);
        }
        $this->authorizeTransactionOwner($transaction);
        return view('front.custom_success', compact('transaction'));
    }

    public function uploadCustomPaymentProof(Request $request, CustomTransaction $customTransaction)
    {
        $this->authorizeTransactionOwner($customTransaction);
        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,png'],
            'payment_method' => ['required', 'string', 'in:BCA,BRI'],
            'confirm_payment' => ['accepted'],
        ]);
        $proofPath = $request
            ->file('payment_proof')
            ->store('custom_payment_proofs', 'public');
        $this->optimizeUploadedImage($proofPath);  // Optimize image with Intervention Image
        $customTransaction->update([
            'payment_proof' => $proofPath,
            'payment_method' => $request->payment_method,
            'status' => CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION,
        ]);
        return back()->with('success', 'Payment proof uploaded successfully. Waiting for admin verification.');
    }

    public function cancelCustomOrder(CustomTransaction $customTransaction)
    {
        $this->authorizeTransactionOwner($customTransaction);
        if ($customTransaction->status === CustomTransactionStatus::PENDING || $customTransaction->status === CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION) {
            $customTransaction->update(['status' => CustomTransactionStatus::CANCELLED]);
            return back()->with('success', 'Custom order cancelled successfully.');
        }
        return back()->withErrors(['error' => 'Custom order cannot be cancelled at this stage.']);
    }

    public function approveCustomOrder(CustomTransaction $customTransaction)
    {
        return back()->withErrors(['error' => 'This action is not available in the current workflow.']);
    }

    public function cancelRentalOrder(RentalTransaction $rentalTransaction)
    {
        $this->authorizeTransactionOwner($rentalTransaction);
        if (
            $rentalTransaction->status !== RentalTransactionStatus::COMPLETED &&
            $rentalTransaction->status !== RentalTransactionStatus::REJECTED &&
            $rentalTransaction->status !== RentalTransactionStatus::CANCELLED
        ) {
            if ($rentalTransaction->payment_proof) {
                Storage::disk('public')->delete($rentalTransaction->payment_proof);
            }
            $rentalTransaction->update(['status' => RentalTransactionStatus::CANCELLED]);
            return back()->with('success', 'Rental order cancelled successfully.');
        }
        return back()->withErrors(['error' => 'Rental order cannot be cancelled at this stage.']);
    }

    /**
     * Only the transaction's owner (or an admin) may view, cancel, or
     * upload a payment proof for it. Previously cancelRentalOrder,
     * cancelCustomOrder and uploadCustomPaymentProof never checked
     * ownership at all — any logged-in user could cancel or overwrite
     * anyone else's order just by guessing its ID.
     * Transactions without a user_id (legacy records) are allowed
     * through, since there is no owner to compare against.
     */
    private function authorizeTransactionOwner($transaction): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        if ($user->role === 'admin') {
            return;
        }
        if ($transaction->user_id && (int) $transaction->user_id !== (int) $user->id) {
            abort(403);
        }
    }

    /**
     * Downscale an uploaded image to at most 1600px wide (keeping aspect
     * ratio, never upscaling) and re-encode at quality 80. Previously
     * uploads were only re-saved at quality 80, so full-resolution phone
     * photos were stored and served as-is on every page that shows them.
     */
    private function optimizeUploadedImage(string $path): void
    {
        $fullPath = Storage::disk('public')->path($path);
        Image::make($fullPath)
            ->resize(1600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($fullPath, 80);
    }

    public function redirectToGoogle()
    {
        return \Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = \Socialite::driver('google')->user();
            $findUser = \App\Models\User::where('google_id', $user->id)->first();
            if ($findUser) {
                \Auth::login($findUser);
                return redirect()->intended(route('front.index'));
            } else {
                $existingUser = \App\Models\User::where('email', $user->email)->first();
                if ($existingUser) {
                    $existingUser->google_id = $user->id;
                    $existingUser->google_token = $user->token;
                    $existingUser->save();
                    \Auth::login($existingUser);
                    return redirect()->intended(route('front.index'));
                } else {
                    $newUser = \App\Models\User::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'google_id' => $user->id,
                        'google_token' => $user->token,
                        'password' => \Hash::make(Str::random(16)),
                    ]);
                    \Auth::login($newUser);
                    return redirect()->intended(route('front.index'));
                }
            }
        } catch (\Exception $e) {
            \Log::error('Google login failed: ' . $e->getMessage());
            return redirect(route('front.index'))->withErrors(['google_login' => 'Unable to login with Google. Please try again.']);
        }
    }

    public function getComments()
    {
        $comments = Comment::all();
        return view('front.contact', compact('comments'));
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'comment' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,png'],
        ]);
        $userName = Auth::user()->name;
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comment_images', 'public');
            $this->optimizeUploadedImage($imagePath);  // Optimize image with Intervention Image
        }
        Comment::create([
            'user_id' => Auth::id(),
            'name' => $userName,
            'comment' => $request->comment,
            'image' => $imagePath,
        ]);
        return back()->with('success', 'Comment submitted successfully!');
    }
}
