@extends('front.layouts.app')
@section('title', 'Booking')

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
@endpush

@section('content')
<main class="main-content-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.details', $product->slug) }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Booking</p>
        <button id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>

    <section class="card p-3 mx-3 mb-4">
        <h2 class="h6 mb-3 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-box-seam"></i> Product</h2>
        <div class="d-flex align-items-center gap-3">
            <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px;">
                <div class="d-flex justify-content-center align-items-center h-100 w-100">
                    <img src="{{ Storage::url($product->thumbnail) }}" class="img-fluid rounded-2" alt="thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
            <div class="flex-grow-1 d-flex flex-column gap-2">
                <p class="fw-bold mb-0">{{ $product->name }}</p>
            </div>
        </div>
    </section>

    <div class="d-flex flex-column gap-3 px-3 mb-4">
        <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-info-circle"></i> Details</h2>
        <p class="text-muted mb-0">Silahkan tuliskan detail booking dengan teliti untuk menghindari kesalahan biaya sewa barang.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mx-3" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="booking-form" action="{{ route('front.booking_save', $product->slug) }}" method="POST" class="d-flex flex-column gap-4 px-3">
        @csrf
        <input type="hidden" value="{{ $product->price }}" id="productPrice" />

        <div class="card p-3 d-flex flex-column gap-3">
            <label for="duration" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-clock"></i> How many days?</label>
            <div class="d-flex align-items-center justify-content-center gap-4">
                <button type="button" id="Minus" class="btn btn-primary btn-sm p-0 d-flex justify-content-center align-items-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-dash fs-5"></i>
                </button>
                <p id="CountDays" class="fw-semibold fs-5 mb-0">1</p>
                <input type="number" name="duration" id="DurationInput" value="1" class="form-control d-none" required />
                <button type="button" id="Plus" class="btn btn-primary btn-sm p-0 d-flex justify-content-center align-items-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-plus fs-5"></i>
                </button>
            </div>
        </div>

        <div class="card p-3 d-flex flex-column gap-2">
            <label for="date" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Started At</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-calendar-event"></i>
                </span>
                <input type="date" name="started_at" id="date" class="form-control" min="{{ now()->addDay()->toDateString() }}" required />
            </div>
        </div>

        <div class="card p-3 d-flex flex-column gap-2">
            <label for="product_size_id" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-arrows-fullscreen"></i> Select Size</label>
            <select name="product_size_id" id="product_size_id" class="form-select" required>
                <option value="">Select a size</option>
                @foreach($product->productSizes as $productSize)
                    <option value="{{ $productSize->id }}" @if($productSize->stock == 0) disabled @endif>
                        {{ $productSize->size }} (@if($productSize->stock > 0) Stock: {{ $productSize->stock }} @else Out of Stock @endif)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="card p-3 d-flex flex-column gap-2">
            <label for="name" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
        </div>

        <div class="card p-3 d-flex flex-column gap-2">
            <label for="phone_number" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</label>
            <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Enter your phone number" value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" required>
        </div>
    </form>
</main>

<div id="Bottom-nav" class="fixed-bottom">
    <div class="container main-content-container">
        <div class="d-flex items-center justify-content-between p-3">
            <div class="d-flex flex-column gap-1">
                <p class="mb-0 text-muted" style="font-size: 0.9rem;">Total Harga</p>
                <p id="Total" class="fw-bold fs-5 mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <button type="submit" form="booking-form" class="btn btn-primary">Checkout</button>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script src="{{ asset('customjs/booking.js') }}"></script>
@endpush