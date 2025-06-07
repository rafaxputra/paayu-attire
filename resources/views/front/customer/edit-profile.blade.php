@extends('front.layouts.app')
@section('title', 'Edit Profile')

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.customer.dashboard') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Edit Profile</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0">
                <i class="bi bi-moon fs-4"></i>
            </button>
        </div>
        <section class="d-flex flex-column gap-4 mt-4 px-3">
            <div class="d-flex flex-column gap-2 align-items-center text-center mb-4">
                <h1 class="h4 fw-bold mb-2">Edit Your Profile</h1>
                <p class="text-muted mb-0">Update your personal information.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('front.customer.updateProfile') }}" method="POST" class="card p-4 d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="d-flex flex-column gap-2">
                    <label for="name" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" value="{{ old('name', $user->name) }}" required autofocus>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="phone_number" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter your phone number" value="{{ old('phone_number', $user->phone_number) }}" pattern="\d*">
                    <small class="form-text text-muted">Hanya bisa diisi dengan nomor/angka.</small>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-check-circle"></i> Update Profile</button>
            </form>
        </section>

        <div class="mb-5"></div> {{-- Added margin to push content above fixed navbar --}}

        {{-- Bottom Navigation --}}
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
                        <a class="nav-link text-center text-dark" href="{{ route('front.transactions') }}">
                             <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-receipt-cutoff bottom-nav-icon"></i>
                                <p class="mb-0" style="font-size: 0.8rem;">Orders</p>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-center text-muted" href="{{ route('front.custom') }}">
                            <div class="d-flex flex-column align-items-center">
                                <i class="bi bi-pencil-square bottom-nav-icon"></i>
                                <p class="mb-0" style="font-size: 0.8rem;">Custom</p>
                            </div>
                        </a>
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
