@extends('front.layouts.app')
@section('title', 'Paayu Attire') {{-- Updated title --}}

@push('after-styles')
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
@endpush

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3 pe-3">
            <a href="{{ route('front.index') }}" class="d-flex align-items-center ms-2">
                <img id="paayu-logo" src="{{ asset('assets/images/logos/paayu_light_mode.png') }}" alt="Paayu Attire Logo" style="height: 30px;" />
            </a>
            <div class="d-flex align-items-center gap-3"> {{-- Container for theme toggle and auth status --}}
                {{-- Dark/Light Mode Toggle Button --}}
                <button id="theme-toggle" class="btn p-0">
                    <i class="bi bi-moon fs-4"></i>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-3 py-1 d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                @endguest

                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary rounded-pill px-3 py-1 d-flex align-items-center gap-2 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Hi, {{ Auth::user()->name }}! 👋
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('front.customer.dashboard') }}"><i class="bi bi-person-circle me-2"></i> Kelola Profil</a></li> {{-- Need to create this route --}}
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
        <!-- Search Bar -->
        <div class="px-3 mb-4">
            <form action="{{ route('front.index') }}" method="GET"> {{-- Added form --}}
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-lg rounded-pill" placeholder="Search kebaya..." value="{{ request('search') }}"> {{-- Added name and value --}}
                    <button class="btn btn-outline-secondary rounded-pill ms-2" type="submit" id="button-addon2"><i class="bi bi-search"></i></button> {{-- Changed type to submit --}}
                </div>
            </form> {{-- Closed form --}}
        </div>
        <!-- Filter and Sort Controls -->
        <div class="px-3 mb-4">
            <form action="{{ route('front.index') }}" method="GET" class="d-flex align-items-center gap-3">
                <i class="bi bi-filter fs-5 text-muted"></i>
                <div class="flex-grow-1 d-flex gap-3">
                    {{-- Size Filter --}}
                    <select name="size" id="size-filter" class="form-select form-select-sm">
                        <option value="">All Sizes</option>
                        @foreach ($availableSizes as $size)
                            <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>Size {{ $size }}</option>
                        @endforeach
                    </select>

                    {{-- Price Sort --}}
                    <select name="sort_price" id="price-sort" class="form-select form-select-sm">
                        <option value="">Sort by Price</option>
                        <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Apply</button>
            </form>
        </div>

        <section id="All-Kebaya" class="mb-4 px-3">
            <h2 class="h5 mb-3 fw-bold d-flex align-items-center gap-2"><i class="bi bi-grid-fill"></i> All Kebaya</h2>
            <div class="row row-cols-2 row-cols-md-3 g-3">
                @forelse ($products as $product)
                    <div class="col">
                        <a href="{{ route('front.details', $product->slug) }}" class="card h-100 text-decoration-none text-dark">
                            <img src="{{ Storage::url($product->thumbnail) }}" class="card-img-top" alt="thumbnail" style="height: 180px; object-fit: cover; background-color: #F6F6F6;" />
                            <div class="card-body p-3 d-flex flex-column gap-2">
                                <h3 class="card-title h6 mb-0 fw-bold">{{ $product->name }}</h3>
                                <p class="card-text text-muted mb-0" style="font-size: 0.9rem;">Rp {{ number_format($product->price, 0, ',', '.') }}/day</p>
                                @if($product->productSizes->count() > 0)
                                    <div class="d-flex flex-wrap gap-1 mt-auto">
                                        @foreach($product->productSizes as $productSize)
                                            <span class="badge rounded-pill {{ $productSize->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $productSize->size }}: {{ $productSize->stock }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="card-text text-muted mt-auto" style="font-size: 0.75rem;">No size information available.</p>
                                @endif
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="px-3">belum ada data produk terbaru</p>
                @endforelse
            </div>
        </section>

        <div id="Bottom-nav" class="fixed-bottom bg-white border-top">
            <div class="container main-content-container">
                <ul class="nav justify-content-around py-3">
                    <li class="nav-item">
                        <a class="nav-link text-center text-dark" href="{{ route('front.index') }}">
                             <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-house-door-fill bottom-nav-icon"></i>
                                <p class="mb-0" style="font-size: 0.8rem;">Browse</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        @guest
                            <a class="nav-link text-center text-muted" href="{{ route('login') }}">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-receipt bottom-nav-icon"></i>
                                    <p class="mb-0" style="font-size: 0.8rem;">Orders</p>
                                </div>
                            </a>
                        @else
                            <a class="nav-link text-center text-muted" href="{{ route('front.transactions') }}">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-receipt bottom-nav-icon"></i>
                                    <p class="mb-0" style="font-size: 0.8rem;">Orders</p>
                                </div>
                            </a>
                        @endguest
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
    </main>
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('customjs/browse.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const htmlElement = document.documentElement;
            const paayuLogo = document.getElementById('paayu-logo');
            const themeToggle = document.getElementById('theme-toggle');

            const lightModeLogo = "{{ asset('assets/images/logos/paayu_light_mode.png') }}";
            const darkModeLogo = "{{ asset('assets/images/logos/paayu_dark_mode.png') }}";

            function updateLogo() {
                if (htmlElement.getAttribute('data-bs-theme') === 'dark') {
                    paayuLogo.src = darkModeLogo;
                } else {
                    paayuLogo.src = lightModeLogo;
                }
            }

            // Update logo on page load
            updateLogo();

            // Update logo when theme is toggled
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    // A small delay might be needed to ensure data-bs-theme is updated
                    setTimeout(updateLogo, 50);
                });
            }
        });
    </script>
@endpush
