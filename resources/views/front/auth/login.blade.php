@extends('front.layouts.app')
@section('title', 'Customer Login')

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.index') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Customer Login</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0">
                <i class="bi bi-moon fs-4"></i>
            </button>
        </div>
        <section class="d-flex flex-column gap-4 mt-4 px-3">
            <div class="d-flex flex-column gap-2 align-items-center text-center mb-4">
                <h1 class="h4 fw-bold mb-2">Welcome Back!</h1>
                <p class="text-muted mb-0">Login to access your account.</p>
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

            <form action="{{ route('login') }}" method="POST" class="card p-4 d-flex flex-column gap-4">
                @csrf
                <div class="d-flex flex-column gap-2">
                    <label for="email" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-envelope"></i> Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="password" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-lock"></i> Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label fw-semibold" for="remember">
                        Remember Me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-box-arrow-in-right"></i> Login</button>

                <div class="text-center mt-3">
                    <p class="text-muted mb-2">Or login with:</p>
                    <a href="{{ route('front.auth.google') }}" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-google"></i> Google
                    </a>
                </div>

                <div class="text-center mt-3">
                    <p class="text-muted mb-0">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                </div>
            </form>
        </section>
    </main>
@endsection
