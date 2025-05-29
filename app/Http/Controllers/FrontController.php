<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Comment;
use App\Models\ContactInformation;
use App\Models\CustomTransaction;
use App\Models\Product;
use App\Models\RentalTransaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Import Str for generating unique IDs

class FrontController extends Controller
{
    public function index(Request $request) // Inject Request
    {
        // Fetch all unique product sizes for the filter dropdown
        $availableSizes = \App\Models\ProductSize::distinct()->pluck('size')->sort()->values()->all();

        // Start with the base product query
        $query = Product::with('productSizes');

        // Apply search filter if present in the request
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Apply size filter if present in the request
        if ($request->filled('size')) {
            $query->whereHas('productSizes', function ($query) use ($request) {
                $query->where('size', $request->input('size'));
            });
        }

        // Apply price sorting if present in the request
        if ($request->filled('sort_price')) {
            $sortDirection = $request->input('sort_price') === 'asc' ? 'asc' : 'desc';
            $query->orderBy('price', $sortDirection);
        } else {
            // Default sorting (e.g., latest)
            $query->latest();
        }

        // Fetch the filtered and sorted products
        $products = $query->get();

        // You might want to implement logic for "You Might Like" and "Popular Products" here later

        return view('front.index', compact('products', 'availableSizes')); // Pass availableSizes to the view
    }

    public function details(Product $product)
    {
        // Load product with its sizes
        $product->load('productSizes');
        return view('front.details', compact('product'));
    }

    public function booking(Product $product)
    {
        // Stores are removed, handle pickup location differently if needed
        return view('front.booking', compact('product'));
    }

    public function booking_save(Request $request, Product $product) // Changed Request type hint
    {
        $request->validate([
            'duration' => ['required', 'integer', 'min:1'],
            'started_at' => ['required', 'date', 'after_or_equal:today'],
            'product_size_id' => ['required', 'exists:product_sizes,id'], // Validate selected size
            'name' => ['required', 'string', 'max:255'], // Added name validation
            'phone_number' => ['required', 'string', 'max:255'], // Added phone_number validation
        ]);

        $productSize = $product->productSizes()->findOrFail($request->product_size_id);

        if ($productSize->stock <= 0) {
            return back()->withErrors(['product_size_id' => 'Selected size is out of stock.'])->withInput();
        }

        $duration = (int) $request->duration;
        $startedDate = Carbon::parse($request->started_at);
        $endedDate = $startedDate->copy()->addDays($duration);
        $totalAmount = $product->price * $duration; // Calculate total amount without PPN/Insurance

        $rentalTransaction = null;

        DB::transaction(function () use ($request, $product, $productSize, $duration, $startedDate, $endedDate, $totalAmount, &$rentalTransaction) {
            $rentalTransaction = RentalTransaction::create([
                'trx_id' => 'RENTAL-' . Str::random(8), // Generate unique transaction ID
                'product_id' => $product->id,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'started_at' => $startedDate,
                'ended_at' => $endedDate,

                'total_amount' => $totalAmount,
                'status' => 'pending', // Initial status
            ]);

            // Decrement stock
            $productSize->decrement('stock');
        });

        // Redirect to checkout with transaction ID
        return redirect()->route('front.checkout', [
            'product' => $product->slug,
            'transactionId' => $rentalTransaction->trx_id,
        ]);
    }

    public function checkout(Product $product, Request $request) // Added Request to get transactionId
    {
        $transactionId = $request->query('transactionId');
        $rentalTransaction = RentalTransaction::where('trx_id', $transactionId)
                                ->where('product_id', $product->id) // Ensure transaction matches product
                                ->firstOrFail();

        // Calculate subTotal and grandTotal based on the fetched transaction
        $subTotal = $rentalTransaction->product->price * $rentalTransaction->duration;
        $grandTotal = $rentalTransaction->total_amount; // Assuming total_amount in DB is already grand total without PPN/Insurance

        // Bank Account Details
        $bankAccounts = [
            'BCA' => '5545011970 a/n Niken Alfinanda Putri',
            'BRI' => '626801015467534 a/n Niken Alfinanda Putri',
        ];

        return view('front.checkout', compact('product', 'rentalTransaction', 'subTotal', 'grandTotal', 'bankAccounts'));
    }

    public function checkout_store(Request $request) // Changed Request type hint
    {
        $request->validate([
            'transaction_id' => ['required', 'exists:rental_transactions,trx_id'], // Validate transaction ID
            'payment_proof' => ['required', 'image', 'mimes:jpg,png', 'max:2048'], // Validate image file
            'payment_method' => ['required', 'string', 'in:BCA,BRI'], // Validate payment method
            'confirm_payment' => ['accepted'], // Validate checkbox
        ]);

        $rentalTransaction = RentalTransaction::where('trx_id', $request->transaction_id)->firstOrFail();

        // Store the payment proof
        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update the rental transaction
        $rentalTransaction->update([
            'payment_proof' => $proofPath,
            'payment_method' => $request->payment_method,
            'status' => 'pending_payment_verification', // New status for admin verification
        ]);

        // Redirect to the success booking page with the transaction ID
        return redirect()->route('front.success.booking', $rentalTransaction->trx_id);
    }

    public function success_booking($transactionId) // Changed parameter name to transactionId
    {
        // Fetch the rental transaction by trx_id
        $transaction = RentalTransaction::where('trx_id', $transactionId)->first();

        if (!$transaction) {
            // If not found in rental, check custom transactions (if needed for a combined success page)
            $transaction = CustomTransaction::where('trx_id', $transactionId)->first();
        }

        if (!$transaction) {
            abort(404); // Transaction not found
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

        // Attempt to find in RentalTransactions
        $rentalDetails = RentalTransaction::with('product')
            ->where('trx_id', $trx_id)
            ->where('phone_number', $phone_number)
            ->first();

        if ($rentalDetails) {
            // Calculate subTotal and grandTotal for rental
            $subTotal = $rentalDetails->product->price * $rentalDetails->duration;
            $grandTotal = $rentalDetails->total_amount; // Assuming total_amount in DB is already grand total without PPN/Insurance

            return view('front.transaction_details', compact('subTotal', 'grandTotal'))->with('details', $rentalDetails);
        }

        // Attempt to find in CustomTransactions
        $customDetails = CustomTransaction::where('trx_id', $trx_id)
            ->where('phone_number', $phone_number)
            ->first();

        if ($customDetails) {
            // Redirect to a new custom transaction details page
            return redirect()->route('front.custom.details', $customDetails->trx_id); // Need to define this route and view
        }

        // If not found in either
        return back()->withErrors(['error' => 'Transactions not found.']);
    }

    // New method for Custom Kebaya Order form
    public function custom()
    {
        return view('front.custom');
    }

    // New method to store Custom Kebaya Order
    public function storeCustomOrder(Request $request)
    {
        \Log::error('Custom Order Request:', $request->all());

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'image_reference_1' => ['required', 'image', 'mimes:jpg,png', 'max:2048'],
            'image_reference_2' => ['nullable', 'image', 'mimes:jpg,png', 'max:2048'],
            'image_reference_3' => ['nullable', 'image', 'mimes:jpg,png', 'max:2048'],
            'kebaya_preference' => ['required', 'string'],
            'amount_to_buy' => ['required', 'integer', 'min:1', 'max:15'],
            'date_needed' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $validatedData = $request->only([
            'full_name',
            'phone_number',
            'kebaya_preference',
            'amount_to_buy',
            'date_needed',
        ]);

        $validatedData['name'] = $validatedData['full_name'];
        unset($validatedData['full_name']);

        // Upload gambar 1 wajib
        // Upload gambar 1 wajib
        $image1 = $request->file('image_reference_1')->store('custom_kebaya_references', 'public');
        $validatedData['image_reference'] = $image1; // <-- cocokkan dengan nama kolom di DB

        // Upload gambar 2 & 3 jika ada
        if ($request->hasFile('image_reference_2')) {
            $validatedData['image_reference_2'] = $request->file('image_reference_2')->store('custom_kebaya_references', 'public');
        }

        if ($request->hasFile('image_reference_3')) {
            $validatedData['image_reference_3'] = $request->file('image_reference_3')->store('custom_kebaya_references', 'public');
        }


        // Generate trx_id
        $validatedData['trx_id'] = 'CUSTOM-' . Str::random(8);

        // Simpan ke DB
        $customTransaction = CustomTransaction::create($validatedData);

        return redirect()->route('front.custom.success', $customTransaction->trx_id);
    }


    // New method for Contact page
    public function contact()
    {
        $contactInfo = ContactInformation::first(); // Assuming only one row for contact info
        return view('front.contact', compact('contactInfo'));
    }

    // New method for Custom transaction details page
    public function customTransactionDetails($transactionId)
    {
        $details = CustomTransaction::where('trx_id', $transactionId)->first();

        if (!$details) {
            abort(404); // Transaction not found
        }

        return view('front.custom_transaction_details', compact('details'));
    }

    // New method for Custom order success page
    public function customSuccess($transactionId)
    {
        $transaction = CustomTransaction::where('trx_id', $transactionId)->first();

        if (!$transaction) {
            abort(404); // Transaction not found
        }

        return view('front.custom_success', compact('transaction'));
    }

    // New method to handle custom order payment proof upload
    public function uploadCustomPaymentProof(Request $request, CustomTransaction $customTransaction) // Added CustomTransaction type hint
    {
        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,png', 'max:2048'], // Validate image file
            'payment_method' => ['required', 'string', 'in:BCA,BRI'], // Validate payment method
            'confirm_payment' => ['accepted'], // Validate checkbox
        ]);



        // Store the payment proof
        $proofPath = $request->file('payment_proof')
            ->store('custom_payment_proofs', 'public')
            ->optimize('webp'); // Optimize to webp

        // Update the custom transaction
        $customTransaction->update([
            'payment_proof' => $proofPath,
            'payment_method' => $request->payment_method,
            'status' => \App\Enums\CustomTransactionStatus::PENDING_PAYMENT, // Changed status to PENDING_PAYMENT enum
        ]);

        return back()->with('success', 'Payment proof uploaded successfully. Waiting for admin verification.');
    }

    // New method to handle custom order cancellation
    public function cancelCustomOrder(CustomTransaction $customTransaction) // Added CustomTransaction type hint
    {
        // Allow cancellation only if status is pending or accepted
        if ($customTransaction->status === 'pending' || $customTransaction->status === 'accepted') {
            $customTransaction->update(['status' => 'cancelled']);
            return back()->with('success', 'Custom order cancelled successfully.');
        }

        return back()->withErrors(['error' => 'Custom order cannot be cancelled at this stage.']);
    }

    // New method to handle custom order approval by the customer
    public function approveCustomOrder(CustomTransaction $customTransaction)
    {
        // Allow approval only if status is accepted and not paid
        if ($customTransaction->status->value === 'accepted') {
            $customTransaction->status = \App\Enums\CustomTransactionStatus::PENDING_PAYMENT;
            $customTransaction->save();
            return redirect()->route('front.custom.details', $customTransaction->trx_id)->with('success', 'Order approved. Please proceed with payment.');
        }


        return back()->withErrors(['error' => 'Custom order cannot be approved at this stage.']);
    }

    
    // Need to create methods for:
    // - Admin actions in Filament (will be in Filament resources)

    // Socialite Methods
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
                return redirect()->intended(route('front.index')); // Redirect to intended page or home
            } else {
                // Check if a user with the same email already exists
                $existingUser = \App\Models\User::where('email', $user->email)->first();

                if ($existingUser) {
                    // If user exists with the same email, link the Google account
                    $existingUser->google_id = $user->id;
                    $existingUser->google_token = $user->token;
                    $existingUser->save();

                    \Auth::login($existingUser);
                    return redirect()->intended(route('front.index'));
                } else {
                    // Create a new user
                    $newUser = \App\Models\User::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'google_id' => $user->id,
                        'google_token' => $user->token,
                        'password' => \Hash::make(Str::random(16)), // Generate a random password
                    ]);

                    \Auth::login($newUser);
                    return redirect()->intended(route('front.index'));
                }
            }
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            \Log::error('Google login failed: ' . $e->getMessage());
            return redirect(route('front.index'))->withErrors(['google_login' => 'Unable to login with Google. Please try again.']);
        }
    }

    public function getComments()
    {
        $comments = Comment::latest()->get(); // Ambil semua komentar, urutkan dari terbaru
        return view('front.contact', compact('comments'));
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'comment' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,png', 'max:2048'],
        ]);

        // Ambil nama user yang login
        $userName = Auth::user()->name;

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('comment_images', 'public');
        }

        Comment::create([
            'name' => $userName, // Gunakan nama user yang login
            'comment' => $request->comment,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }


    // Need to create methods for:
    // - Admin actions in Filament (will be in Filament resources)
}
