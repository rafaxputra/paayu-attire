@extends('front.layouts.app')
@section('title', 'Checkout')

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
@endpush

@section('content')
    <main class="main-content-container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
            <a href="{{ route('front.booking', $product->slug) }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Checkout</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0"> {{-- Added ID --}}
                <i class="bi bi-moon fs-4"></i> {{-- Initial icon (moon for light mode) --}}
            </button>
        </div>
        <form action="{{ route('front.checkout.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3 px-3">
            @csrf
            @if (session('success'))
                <div class="alert alert-success mb-3" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mb-3" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input type="hidden" name="transaction_id" value="{{ $rentalTransaction->trx_id }}">

            <section id="Product-name" class="mb-3">
                <h2 class="h6 mb-2 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-box-seam"></i> Product</h2>
                <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px; background-color: #F6F6F6;">
                        <div class="d-flex justify-content-center align-items-center h-100 w-100">
                            <img src="{{ Storage::url($product->thumbnail) }}" class="img-fluid" alt="thumbnail" style="max-height: 50px; object-fit: contain;" />
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column gap-2">
                        <p class="fw-bold mb-0">{{ $product->name }}</p>
                    </div>
                </div>
            </section>
            <hr class="mx-3 my-3" />
            <div id="Customer-info" class="d-flex flex-column gap-3">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-person-circle"></i> Customer Information</h2>
                <div class="d-flex flex-column gap-2">
                    <label for="name" class="form-label fw-semibold mb-0">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Write your full name" value="{{ $rentalTransaction->name }}" required>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="phone_number" class="form-label fw-semibold mb-0">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Write your phone number" value="{{ $rentalTransaction->phone_number }}" required>
                    </div>
                </div>
                 @if($rentalTransaction->delivery_type == 'delivery')
                    <div class="d-flex flex-column gap-2">
                        <label for="address" class="form-label fw-semibold mb-0">Delivery Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-house-door"></i>
                            </span>
                            <textarea name="address" id="address" class="form-control" rows="4" placeholder="Write your delivery address" required>{{ $rentalTransaction->address }}</textarea>
                        </div>
                    </div>
                @endif
            </div>
            <hr class="mx-3 my-3">
            <div id="Payment-details" class="d-flex flex-column gap-3">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-credit-card"></i> Payment Details</h2>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="mb-0">Grand total</p>
                        <p class="fw-bold fs-5 mb-0 text-decoration-underline">Rp {{ Number::format($grandTotal, locale: 'id') }}</p>
                    </div>
                </div>
            </div>
            <hr class="mx-3 my-3">
            <div id="Send-Payment" class="d-flex flex-column gap-3">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-bank"></i> Send Payment</h2>
                <p class="mb-0">Please transfer the total amount to one of the following accounts:</p>
                <div class="d-flex flex-column gap-2">
                    <p class="fw-semibold mb-0">Bank BCA:</p>
                    <p class="mb-0">5545011970 a/n Niken Alfinanda Putri</p>
                </div>
                <div class="d-flex flex-column gap-2">
                    <p class="fw-semibold mb-0">Bank BRI:</p>
                    <p class="mb-0">626801015467534 a/n Niken Alfinanda Putri</p>
                </div>
                 <div class="d-flex flex-column gap-2">
                    <label for="payment_method" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-cash-coin"></i> Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="">Select Bank</option>
                        <option value="BCA">BCA</option>
                        <option value="BRI">BRI</option>
                    </select>
                </div>
            </div>
            <hr class="mx-3 my-3">
            <div id="Confirm-Payment" class="d-flex flex-column gap-3">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-check-circle"></i> Confirm Payment</h2>
                <div class="d-flex flex-column gap-2">
                    <label for="payment_proof" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-upload"></i> Upload Proof</label>
                    <div class="input-group">
                         <span class="input-group-text">
                            <i class="bi bi-upload"></i>
                        </span>
                        <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept=".jpg,.png" required>
                    </div>
                    <small class="form-text text-muted">Accepted formats: JPG, PNG</small>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="confirm_payment" id="confirm_payment" required>
                    <label class="form-check-label fw-semibold" for="confirm_payment">
                        Saya benar telah transfer pembayaran
                    </label>
                </div>
            </div>

            @if($rentalTransaction->payment_proof)
                <div class="d-flex flex-column gap-2 px-3 mt-3">
                    <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-image"></i> Uploaded Payment Proof</h2>
                    <img src="{{ Storage::url($rentalTransaction->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded-md" style="max-width: 100%; height: auto;"> {{-- Adjusted image size --}}
                </div>
            @endif

            <div id="Bottom-nav" class="fixed-bottom bg-white border-top">
                <div class="container main-content-container">
                    <ul class="nav justify-content-around py-3">
                        <li class="nav-item">
                            <a class="nav-link text-center text-muted" href="{{ route('front.index') }}">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-house-door bottom-nav-icon"></i>
                                    <p class="mb-0" style="font-size: 0.8rem;">Browse</p>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center text-muted" href="{{ route('front.transactions') }}">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-receipt bottom-nav-icon"></i>
                                    <p class="mb-0" style="font-size: 0.8rem;">Orders</p>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            @guest
                                <a class="nav-link text-center text-muted" href="{{ route('login') }}">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-pencil-square bottom-nav-icon"></i>
                                        <p class="mb-0" style="font-size: 0.8rem;">Custom</p>
                                    </div>
                                </a>
                            @else
                                <a class="nav-link text-center text-muted" href="{{ route('front.custom') }}">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-pencil-square bottom-nav-icon"></i>
                                        <p class="mb-0" style="font-size: 0.8rem;">Custom</p>
                                    </div>
                                </a>
                            @endguest
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-center text-muted" href="{{ route('front.contact') }}">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-person bottom-nav-icon"></i>
                                    <p class="mb-0" style="font-size: 0.8rem;">Contact</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </form>
    </main>
@endsection

@push('after-scripts')
    <script src="{{ asset('customjs/checkout.js') }}"></script>
@endpush
