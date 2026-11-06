@extends('front.layouts.app')
@section('title', 'Set Password')

@section('content')
<main class="main-content-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.customer.dashboard') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Set Password</p>
        <button type="button" id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>

    <div class="vh-center-flex">
        <section class="d-flex flex-column gap-4 w-100">
            <div class="d-flex flex-column gap-2 align-items-center text-center">
                <h1 class="h4 fw-bold mb-2">Set Your Password</h1>
                <p class="text-muted mb-0">Buat password agar kamu bisa login tanpa Google.</p>
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

            <form action="{{ route('front.customer.setPassword.save') }}" method="POST" class="card p-4 d-flex flex-column gap-4">
                @csrf

                <div class="d-flex flex-column gap-2">
                    <label for="password" class="form-label fw-semibold mb-0">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8" required autofocus>
                </div>

                <div class="d-flex flex-column gap-2">
                    <label for="password_confirmation" class="form-label fw-semibold mb-0">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ketik ulang password barumu" minlength="8" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-2"><i class="bi bi-check-circle"></i> Simpan Password</button>
            </form>
        </section>
    </div>

    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <ul class="nav justify-content-around py-3">
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.index') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-house-door bottom-nav-icon"></i><p class="mb-0 small">Browse</p></div></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.transactions') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-receipt bottom-nav-icon"></i><p class="mb-0 small">Orders</p></div></a>
                </li>
                <li class="nav-item">
                     <a class="nav-link text-center" href="{{ route('front.custom') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-pencil-square bottom-nav-icon"></i><p class="mb-0 small">Custom</p></div></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center active" href="{{ route('front.customer.dashboard') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-person-fill bottom-nav-icon"></i><p class="mb-0 small">Profile</p></div></a>
                </li>
            </ul>
        </div>
    </div>
</main>
@endsection
