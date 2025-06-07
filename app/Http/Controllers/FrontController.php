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
    $products = $query->get();
    return view('front.index', compact('products', 'availableSizes'));
}

public function details(Product $product)
{
    $product->load('productSizes');
    return view('front.details', compact('product'));
}

public function booking(Product $product)
{
    return view('front.booking', compact('product'));
}

public function booking_save(StoreBookingRequest $request, Product $product)
{
    $productSize = $product->productSizes()->findOrFail($request->product_size_id);
    if ($productSize->stock <= 0) {
        return back()->withErrors(['product_size_id' => 'Selected size is out of stock.'])->withInput();
    }
    $duration = (int) $request->duration;
    $startedDate = Carbon::parse($request->started_at);
    $endedDate = $startedDate->copy()->addDays($duration);
    $totalAmount = $product->price * $duration;
    $rentalTransaction = null;
    DB::transaction(function () use ($request, $product, $productSize, $duration, $startedDate, $endedDate, $totalAmount, &$rentalTransaction) {
        $rentalTransaction = RentalTransaction::create([
            'trx_id' => 'RENTAL-' . Str::random(8),
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'started_at' => $startedDate,
            'ended_at' => $endedDate,
            'total_amount' => $totalAmount,
            'status' => RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION,
        ]);
        $productSize->decrement('stock');
    });
    return redirect()->route('front.checkout', [
        'product' => $product->slug,
        'transactionId' => $rentalTransaction->trx_id,
    ]);
}

public function checkout(Product $product, Request $request)
{
    $transactionId = $request->query('transactionId');
    $rentalTransaction = RentalTransaction::where('trx_id', $transactionId)
        ->where('product_id', $product->id)
        ->firstOrFail();
    $subTotal = $rentalTransaction->product->price * $rentalTransaction->duration;
    $grandTotal = $rentalTransaction->total_amount;
    $bankAccounts = [
        'BCA' => '5545011970 a/n Niken Alfinanda Putri',
        'BRI' => '626801015467534 a/n Niken Alfinanda Putri',
    ];
    return view('front.checkout', compact('product', 'rentalTransaction', 'subTotal', 'grandTotal', 'bankAccounts'));
}

public function checkout_store(StorePaymentRequest $request)
{
    $rentalTransaction = RentalTransaction::where('trx_id', $request->transaction_id)->firstOrFail();
    if ($rentalTransaction->payment_proof) {
        Storage::disk('public')->delete($rentalTransaction->payment_proof);
    }
    $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
    $fullProofPath = Storage::disk('public')->path($proofPath);
    Image::make($fullProofPath)->save($fullProofPath, 80); // Optimize image with Intervention Image
    $rentalTransaction->update([
        'payment_proof' => $proofPath,
        'payment_method' => $request->payment_method,
        'status' => RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION,
    ]);
    return redirect()->route('front.success.booking', $rentalTransaction->trx_id);
}

public function success_booking($transactionId)
{
    $transaction = RentalTransaction::where('trx_id', $transactionId)->first();
    if (!$transaction) {
        $transaction = CustomTransaction::where('trx_id', $transactionId)->first();
    }
    if (!$transaction) {
        abort(404);
    }
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
        return redirect()->route('front.custom.details', $customDetails->trx_id);
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
    $fullPath1 = Storage::disk('public')->path($image1Path);
    Image::make($fullPath1)->save($fullPath1, 80); // Optimize image with Intervention Image
    $validatedData['image_reference'] = $image1Path;

    if ($request->hasFile('image_reference_2')) {
        $image2Path = $request->file('image_reference_2')->store('custom_kebaya_references', 'public');
        $fullPath2 = Storage::disk('public')->path($image2Path);
        Image::make($fullPath2)->save($fullPath2, 80); // Optimize image with Intervention Image
        $validatedData['image_reference_2'] = $image2Path;
    }

    if ($request->hasFile('image_reference_3')) {
        $image3Path = $request->file('image_reference_3')->store('custom_kebaya_references', 'public');
        $fullPath3 = Storage::disk('public')->path($image3Path);
        Image::make($fullPath3)->save($fullPath3, 80); // Optimize image with Intervention Image
        $validatedData['image_reference_3'] = $image3Path;
    }

    $validatedData['user_id'] = Auth::id();

    $validatedData['trx_id'] = 'CUSTOM-' . Str::random(8);

    $customTransaction = CustomTransaction::create($validatedData);

    return redirect()->route('front.custom.success', $customTransaction->trx_id);
}

public function contact()
{
    return view('front.contact');
}

public function customTransactionDetails($transactionId)
{
    $details = CustomTransaction::where('trx_id', $transactionId)->first();
    if (!$details) {
        abort(404);
    }
    return view('front.custom_transaction_details', compact('details'));
}

public function customSuccess($transactionId)
{
    $transaction = CustomTransaction::where('trx_id', $transactionId)->first();
    if (!$transaction) {
        abort(404);
    }
    return view('front.custom_success', compact('transaction'));
}

public function uploadCustomPaymentProof(Request $request, CustomTransaction $customTransaction)
{
    $request->validate([
        'payment_proof' => ['required', 'image', 'mimes:jpg,png'],
        'payment_method' => ['required', 'string', 'in:BCA,BRI'],
        'confirm_payment' => ['accepted'],
    ]);
    $proofPath = $request
        ->file('payment_proof')
        ->store('custom_payment_proofs', 'public');
    $fullProofPath = Storage::disk('public')->path($proofPath);
    Image::make($fullProofPath)->save($fullProofPath, 80); // Optimize image with Intervention Image
    $customTransaction->update([
        'payment_proof' => $proofPath,
        'payment_method' => $request->payment_method,
        'status' => CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION,
    ]);
    return back()->with('success', 'Payment proof uploaded successfully. Waiting for admin verification.');
}

public function cancelCustomOrder(CustomTransaction $customTransaction)
{
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
        $fullImagePath = Storage::disk('public')->path($imagePath);
        Image::make($fullImagePath)->save($fullImagePath, 80); // Optimize image with Intervention Image
    }
    Comment::create([
        'user_id' => Auth::id(),
        'name' => $userName,
        'comment' => $request->comment,
        'image' => $imagePath,
    ]);
    return back()->with('success', 'Komentar berhasil dikirim!');
}
}
