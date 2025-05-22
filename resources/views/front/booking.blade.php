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
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0"> {{-- Added ID --}}
                <i class="bi bi-moon fs-4"></i> {{-- Initial icon (moon for light mode) --}}
            </button>
        </div>
        <section id="Product-name" class="mb-4 px-3">
            <h2 class="h5 mb-3 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-box-seam"></i> Product</h2> {{-- Added icon --}}
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 100px; height: 100px; background-color: #F6F6F6;">
                    <div class="d-flex justify-content-center align-items-center h-100 w-100">
                        <img src="{{ Storage::url($product->thumbnail) }}" class="img-fluid" alt="thumbnail" style="max-height: 100%; object-fit: contain;" />
                    </div>
                </div>
                <div class="flex-grow-1 d-flex flex-column gap-2">
                    <p class="fw-bold mb-0">{{ $product->name }}</p>
                </div>
            </div>
        </section>
        <hr class="mx-3 my-4" />
        <div class="d-flex flex-column gap-3 px-3 mb-4">
            <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-info-circle"></i> Details</h2> {{-- Added icon --}}
            <p class="text-muted mb-0">Silahkan tuliskan details booking dengan teliti untuk
                menghindari kesalahan biaya sewa barang</p>
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
        <form action="{{ route('front.booking_save', $product->slug) }}" method="POST" class="d-flex flex-column gap-4 px-3">
            @csrf
            <input type="hidden" value="{{ $product->price }}" id="productPrice" />
            <div class="d-flex justify-content-between align-items-center">
                <label for="duration" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-clock"></i> How many days?</label> {{-- Added icon --}}
                <div class="d-flex align-items-center gap-3">
                    <button type="button" id="Minus"
                        class="btn btn-outline-secondary rounded-circle p-0 d-flex justify-content-center align-items-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-dash fs-5"></i>
                    </button>
                    <p id="CountDays" class="fw-semibold fs-5 mb-0">1</p>
                    <input type="number" name="duration" id="DurationInput" value="1" class="form-control d-none" required />
                    <button type="button" id="Plus"
                        class="btn btn-outline-secondary rounded-circle p-0 d-flex justify-content-center align-items-center" style="width: 44px; height: 44px;">
                        <i class="bi bi-plus fs-5"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <label for="date" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Started At</label> {{-- Added icon --}}
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-calendar-event"></i>
                    </span>
                    <input type="date" name="started_at" id="date" class="form-control" min="{{ now()->addDay()->toDateString() }}" required />
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <label for="product_size_id" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-arrows-fullscreen"></i> Select Size</label> {{-- Added icon --}}
                <select name="product_size_id" id="product_size_id" class="form-select" required>
                    <option value="">Select a size</option>
                    @foreach($product->productSizes as $productSize)
                        <option value="{{ $productSize->id }}" @if($productSize->stock == 0) disabled @endif>
                            {{ $productSize->size }} (@if($productSize->stock > 0) Stock: {{ $productSize->stock }} @else Out of Stock @endif)
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex flex-column gap-2">
                <label for="name" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</label> {{-- Added icon --}}
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
            </div>
            <div class="d-flex flex-column gap-2">
                <label for="phone_number" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</label> {{-- Added icon --}}
                <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Enter your phone number" value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" required>
            </div>
            <div class="d-flex flex-column gap-2">
                <label for="delivery_type" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-truck"></i> Delivery Type</label> {{-- Added icon --}}
                <select name="delivery_type" id="delivery_type" class="form-select" onchange="toggleAddressField()" required>
                    <option value="pickup">Pickup</option>
                    <option value="delivery">Delivery</option>
                </select>
            </div>
            <div class="d-none flex-column gap-2" id="address-field">
                <label for="address" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-house-door"></i> Delivery Address</label> {{-- Added icon --}}
                <textarea name="address" id="address" class="form-control" rows="4" placeholder="Enter delivery address"></textarea>
            </div>

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
    <script src="{{ asset('customjs/booking.js') }}"></script>
@endpush
