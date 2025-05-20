@extends('front.layouts.app')
@section('title', 'Check Booking')

@section('content')
    <main class="main-content-container py-4 text-center">
        <section id="CheckBook" class="d-flex flex-column gap-4 align-items-center pt-4 px-3"> {{-- Added px-3 --}}
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                <i class="bi bi-search text-white fs-1"></i>
            </div>
            <div class="d-flex flex-column gap-2 align-items-center">
                <h1 class="h4 fw-bold">Check Booking</h1>
                <p class="text-muted text-center mb-0">Masukkan details berikut untuk melihat status pemesanan Anda saat ini</p>
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

            <form action="{{ route('front.transactions.details') }}" method="POST"
                class="card p-4 d-flex flex-column gap-4 w-100">
                @csrf
                <div class="d-flex flex-column gap-2">
                    <label for="phone" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</label> {{-- Added icon --}}
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-telephone"></i>
                        </span>
                        <input type="tel" name="phone_number" id="phone" class="form-control" placeholder="Write your phone number" required />
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="bookId" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-card-heading"></i> Book ID</label> {{-- Added icon --}}
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-card-heading"></i>
                        </span>
                        <input type="text" name="trx_id" id="bookId" class="form-control" placeholder="Write your booking id" required />
                    </div>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-search"></i> Check My Booking</button> {{-- Added icon and flex/gap --}}
            </form>
        </section>
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
    </main>
@endsection
