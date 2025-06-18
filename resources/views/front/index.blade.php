@extends('front.layouts.app')
@section('title', 'Paayu Attire')

@push('after-styles')
<link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
@endpush

@section('content')

<main class="main-content-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3 pe-3">
        <a href="{{ route('front.index') }}" class="d-flex align-items-center ms-2">
            <img id="paayu-logo" src="{{ asset('assets/images/logos/paayu_light_mode.png') }}" alt="Paayu Attire Logo" style="height: 30px;" />
        </a>
        <div class="d-flex align-items-center gap-3">
            <button id="theme-toggle" class="btn p-0">
                <i class="bi bi-moon fs-4"></i>
            </button>
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary rounded-pill px-3 py-1 d-flex align-items-center gap-2 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Hi, {{ Auth::user()->name }}!
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        @if(Auth::user()->role === 'admin')
                            <li><a class="dropdown-item" href="{{ route('filament.admin.pages.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Admin Dashboard</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('front.customer.dashboard') }}"><i class="bi bi-person-circle me-2"></i> Kelola Profil</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-none" id="logout-form"> @csrf </form>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-in-right"></i> Login</a>
            @endguest
        </div>
    </div>
    
    @if ($errors->any())
        <div class="px-3 mb-3">
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="px-3 mb-3">
        <form action="{{ route('front.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search kebaya..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit" id="button-addon2"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    <div class="px-3 mb-4">
        <form action="{{ route('front.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <i class="bi bi-filter fs-5 text-muted"></i>
            <div class="flex-grow-1 d-flex gap-2">
                <select name="size" id="size-filter" class="form-select form-select-sm">
                    <option value="">All Sizes</option>
                    @foreach ($availableSizes as $size)
                        <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>Size {{ $size }}</option>
                    @endforeach
                </select>
                <select name="sort_price" id="price-sort" class="form-select form-select-sm">
                    <option value="">Sort by Price</option>
                    <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>Low to High</option>
                    <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>High to Low</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
        </form>
    </div>

    <section id="All-Kebaya" class="px-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0 fw-bold d-flex align-items-center gap-2"><i class="bi bi-grid-fill"></i> All Kebaya</h2>
        </div>
        
        <div class="row row-cols-2 row-cols-lg-3 g-4">
            @forelse ($products as $product)
                <div class="col">
                    <a href="{{ route('front.details', $product->slug) }}" class="card text-decoration-none h-100">
                        <img src="{{ Storage::url($product->thumbnail) }}" class="card-img-top" alt="thumbnail" />
                        <div class="card-body p-3 d-flex flex-column">
                            <h3 class="card-title h6 fw-bold product-card-title">{{ $product->name }}</h3>
                            @if($product->productSizes->count() > 0)
                                <div class="d-flex flex-wrap gap-1 mt-2">
                                    @foreach($product->productSizes as $productSize)
                                        <span class="badge rounded-pill {{ $productSize->stock > 0 ? 'bg-success-subtle text-success-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}" style="font-size: 0.7rem;">
                                            {{ $productSize->size }}: {{ $productSize->stock }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <p class="card-text text-muted mb-0 mt-auto pt-2" style="font-size: 0.9rem;">Rp {{ number_format($product->price, 0, ',', '.') }}/day</p>
                        </div>
                    </a>
                </div>
            @empty
                <p class="px-3">No products found.</p>
            @endforelse
        </div>
    </section>

    @include('front.components.bottom_navbar')
</main>
@endsection

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="{{ asset('customjs/browse.js') }}"></script>
@endpush