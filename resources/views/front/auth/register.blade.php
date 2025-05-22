@extends('front.layouts.app')
@section('title', 'Customer Registration')

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.index') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Customer Registration</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0">
                <i class="bi bi-moon fs-4"></i>
            </button>
        </div>
        <section class="d-flex flex-column gap-4 mt-4 px-3">
            <div class="d-flex flex-column gap-2 align-items-center text-center mb-4">
                <h1 class="h4 fw-bold mb-2">Join Us!</h1>
                <p class="text-muted mb-0">Create an account to get started.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="card p-4 d-flex flex-column gap-4">
                @csrf
                <div class="d-flex flex-column gap-2">
                    <label for="name" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="phone_number" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Enter your phone number" value="{{ old('phone_number') }}" required>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="email" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-envelope"></i> Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="password" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-lock"></i> Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="password_confirmation" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-lock-fill"></i> Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-person-plus"></i> Register</button>

                 <div class="text-center mt-3">
                    <p class="text-muted mb-2">Or register with:</p>
                    <a href="{{ route('front.auth.google') }}" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-google"></i> Google
                    </a>
                </div>

                <div class="text-center mt-3">
                    <p class="text-muted mb-0">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                </div>
            </form>
        </section>
    </main>
@endsection
