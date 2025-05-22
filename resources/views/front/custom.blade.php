@extends('front.layouts.app')
@section('title', 'Custom Kebaya Order')

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.index') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Custom Kebaya Order</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0"> {{-- Added ID --}}
                <i class="bi bi-moon fs-4"></i> {{-- Initial icon (moon for light mode) --}}
            </button>
        </div>
        <section class="d-flex flex-column gap-4 mt-4 px-3">
            <div class="d-flex flex-column gap-2 align-items-center text-center mb-4"> {{-- Added margin-bottom --}}
                <h1 class="h4 fw-bold mb-2">Order Your Custom Kebaya</h1> {{-- Added margin-bottom --}}
                <p class="text-muted mb-0">Fill out the form below to order a custom kebaya.</p> {{-- Added margin-bottom --}}
            </div>

            <form action="{{ route('front.custom.order.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 d-flex flex-column gap-4">
                @csrf
                <div class="d-flex flex-column gap-2">
                    <label for="full_name" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</label> {{-- Added icon --}}
                    <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Enter your full name" value="{{ old('full_name', Auth::user()->name ?? '') }}" required>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="phone_number" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</label> {{-- Added icon --}}
                    <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Enter your phone number" value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" required>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="image_reference" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-image"></i> Kebaya Image Reference</label> {{-- Added icon --}}
                    <input type="file" name="image_reference" id="image_reference" class="form-control" accept=".jpg,.png" required>
                    <small class="form-text text-muted">Accepted formats: JPG, PNG</small>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="kebaya_preference" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-file-text"></i> Describe Kebaya Preference</label> {{-- Added icon --}}
                    <textarea name="kebaya_preference" id="kebaya_preference" class="form-control" rows="4" placeholder="Describe your desired kebaya, materials, details, etc." required></textarea>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="amount_to_buy" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-cart"></i> Quantity to Buy (max 15)</label> {{-- Added icon and max limit text --}}
                    <input type="number" name="amount_to_buy" id="amount_to_buy" class="form-control" placeholder="Enter quantity to buy" min="1" max="15" required>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="date_needed" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Date Kebaya Needed</label> {{-- Added icon --}}
                    <input type="date" name="date_needed" id="date_needed" class="form-control" required>
                </div>

                {{-- Delivery Type Selection --}}
                <div class="d-flex flex-column gap-2">
                    <label for="delivery_type" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-truck"></i> Delivery Type</label>
                    <select name="delivery_type" id="delivery_type" class="form-select" required>
                        <option value="pickup">Pickup at Store</option>
                        <option value="delivery">Home Delivery</option>
                    </select>
                </div>

                {{-- Address Field (initially hidden) --}}
                <div id="address-field" class="d-flex flex-column gap-2 d-none"> {{-- Added d-none class --}}
                    <label for="address" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-house-door"></i> Delivery Address</label>
                    <textarea name="address" id="address" class="form-control" rows="4" placeholder="Enter your delivery address"></textarea>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-check-circle"></i> Submit Custom Order</button> {{-- Added icon and flex/gap --}}
            </form>
        </section>

        @push('after-scripts')
        <script src="{{ asset('customjs/custom.js') }}"></script> {{-- Include custom.js --}}
        @endpush

        <div class="mb-5"></div> {{-- Added margin to push content above fixed navbar --}}

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
                            <a class="nav-link text-center text-dark" href="{{ route('front.custom') }}">
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
    </main>
@endsection
