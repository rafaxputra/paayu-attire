@extends('front.layouts.app')
@section('title', 'Check Booking')

@section('content')
<main class="main-content-container py-4 text-center">
    <div class="vh-center-flex">
        <section id="CheckBook" class="d-flex flex-column gap-4 align-items-center w-100">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));">
                <i class="bi bi-search" style="font-size: 3.5rem; color: white;"></i>
            </div>
            <div class="d-flex flex-column gap-2 align-items-center">
                <h1 class="h4 fw-bold">Check Your Booking</h1>
                <p class="text-muted text-center mb-0">Enter the details below to see your order status.</p>
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

            <form action="{{ route('front.transactions.details') }}" method="GET" class="card p-4 d-flex flex-column gap-4 w-100">
                <div class="d-flex flex-column gap-2 text-start">
                    <label for="phone" class="form-label fw-semibold mb-0">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="tel" name="phone_number" id="phone" class="form-control" placeholder="Write your phone number" required />
                    </div>
                </div>
                <div class="d-flex flex-column gap-2 text-start">
                    <label for="bookId" class="form-label fw-semibold mb-0">Booking ID</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                        <input type="text" name="trx_id" id="bookId" class="form-control" placeholder="Write your booking id" required />
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-search"></i> Check My Booking</button>
            </form>
        </section>
    </div>

    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <ul class="nav justify-content-around py-2">
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.index') }}">
                        <div class="d-flex flex-column align-items-center"><i class="bi bi-house-door bottom-nav-icon"></i><span class="small">Browse</span></div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center active" href="{{ route('front.transactions') }}">
                        <div class="d-flex flex-column align-items-center"><i class="bi bi-receipt bottom-nav-icon"></i><span class="small">Orders</span></div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.custom') }}">
                        <div class="d-flex flex-column align-items-center"><i class="bi bi-pencil-square bottom-nav-icon"></i><span class="small">Custom</span></div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.contact') }}">
                        <div class="d-flex flex-column align-items-center"><i class="bi bi-person bottom-nav-icon"></i><span class="small">Contact</span></div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</main>
@endsection