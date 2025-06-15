@extends('front.layouts.app')
@section('title', 'Checkout')

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
@endpush

@section('content')
<main class="main-content-container py-3">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3 pt-3">
        <a href="{{ route('front.booking', $product->slug) }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Checkout</p>
        <button id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
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
        {{-- <input type="hidden" name="transaction_id" value="{{ $rentalTransaction->trx_id }}"> --}}
        <input type="hidden" name="product_slug" value="{{ $product->slug }}">
        <input type="hidden" name="duration" value="{{ $duration }}">
        <input type="hidden" name="started_at" value="{{ $startedDate->toDateString() }}">
        <input type="hidden" name="ended_at" value="{{ $endedDate->toDateString() }}">
        <input type="hidden" name="name" value="{{ $name }}">
        <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
        <input type="hidden" name="selected_size" value="{{ $selectedSize }}">

        <div class="checkout-timeline">

            <div class="timeline-step">
                <div class="timeline-icon"><i class="bi bi-box-seam"></i></div>
                <div class="card p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px;">
                            <img src="{{ Storage::url($product->thumbnail) }}" class="img-fluid rounded-2" alt="thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <p class="fw-bold mb-0">{{ $product->name }}</p>
                            <span class="badge bg-secondary mt-2">Ukuran yang dipilih: {{ $selectedSize }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-icon"><i class="bi bi-person"></i></div>
                <div class="card p-3">
                    <h2 class="h6 mb-2 fw-semibold">Customer Information</h2>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex flex-column gap-2">
                            <label for="name" class="form-label mb-0">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Write your full name" value="{{ $name }}" required>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label for="phone_number" class="form-label mb-0">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Write your phone number" value="{{ $phoneNumber }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-icon"><i class="bi bi-credit-card"></i></div>
                 <div class="card p-3">
                    <h2 class="h6 mb-2 fw-semibold">Payment</h2>
                     <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0">Grand total</p>
                            <p class="fw-bold fs-5 mb-0 text-decoration-underline">Rp {{ Number::format($grandTotal, locale: 'id') }}</p>
                        </div>
                        <hr class="my-1"/>
                        <p class="mb-0 small">Please transfer to one of the following accounts:</p>
                        <div>
                            <p class="fw-semibold mb-0">Bank BCA:</p>
                            <p class="mb-0">5545011970 a/n Niken Alfinanda Putri</p>
                        </div>
                        <div>
                            <p class="fw-semibold mb-0">Bank BRI:</p>
                            <p class="mb-0">626801015467534 a/n Niken Alfinanda Putri</p>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label for="payment_method" class="form-label mb-0">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">Select Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="BRI">BRI</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="timeline-step">
                <div class="timeline-icon"><i class="bi bi-check2"></i></div>
                <div class="card p-3">
                    <h2 class="h6 mb-2 fw-semibold">Confirm Payment</h2>
                     <div class="d-flex flex-column gap-3">
                         <div class="d-flex flex-column gap-2">
                            <label for="payment_proof" class="form-label mb-0">Upload Proof</label>
                            <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept=".jpg,.png" required>
                            <small class="form-text text-muted">Accepted formats: JPG, PNG</small>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="confirm_payment" id="confirm_payment" required>
                            <label class="form-check-label" for="confirm_payment">
                                I confirm that I have transferred the payment
                            </label>
                        </div>
                        @php
                            $penaltyPerDay = $grandTotal * 0.2;
                        @endphp
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="confirm_penalty" id="confirm_penalty" required>
                            <label class="form-check-label" for="confirm_penalty">
                                I understand that if I do not return the item(s) by <b>{{ $endedDate ? $endedDate->format('d M Y') : '-' }}</b>, I will be charged a penalty of <b>Rp {{ number_format($penaltyPerDay, 0, ',', '.') }}</b> x number of late days.
                            </label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="confirm_identity" id="confirm_identity" required>
                            <label class="form-check-label" for="confirm_identity">
                                I understand that when picking up the item, I must leave one of my ID cards (KTP or other valid identification) as a guarantee.
                            </label>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- @if($rentalTransaction->payment_proof)
            <div class="d-flex flex-column gap-2 px-3 mt-3">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-image"></i> Uploaded Payment Proof</h2>
                <img src="{{ Storage::url($rentalTransaction->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded-md" style="max-width: 100%; height: auto;">
            </div>
        @endif --}}

        <button type="submit" class="btn btn-primary w-100 mt-2">Complete Checkout</button>
    </form>
</main>
@endsection

@push('after-scripts')
<script src="{{ asset('customjs/checkout.js') }}"></script>
@endpush