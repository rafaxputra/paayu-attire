@extends('front.layouts.app')
@section('title', 'Booking Details')

@section('content')
    <main class="main-content-container py-4">
        <div id="Top-navbar" class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.transactions') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Booking Details</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0"> {{-- Added ID --}}
                <i class="bi bi-moon fs-4"></i> {{-- Initial icon (moon for light mode) --}}
            </button>
        </div>
        <section class="d-flex flex-column gap-4 px-3">
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

            <div class="card p-4 d-flex flex-row align-items-center gap-3">
                <div class="flex-shrink-0 rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-card-heading text-white fs-4"></i>
                </div>
                <div class="flex-grow-1 d-flex flex-column">
                    <p class="fw-semibold mb-0">{{ $details->trx_id }}</p>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Protect your booking ID</p>
                </div>
            </div>
            @if ($details->is_paid)
                <div class="card p-3 d-flex flex-row align-items-center gap-3 bg-success text-white">
                    <div class="flex-shrink-0">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column gap-1">
                        <div class="d-flex align-items-center gap-1">
                            <p class="fw-semibold mb-0" style="font-size: 0.9rem;">Payment Success</p>
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <p class="mb-0" style="font-size: 0.8rem;">Pembayaran Anda sudah kami terima dan silahkan menunggu instruksi selanjutnya</p>
                    </div>
                </div>
            @else
                <div class="card p-3 d-flex flex-row align-items-center gap-3 bg-warning text-dark">
                    <div class="flex-shrink-0">
                        <i class="bi bi-clock fs-fill fs-4"></i>
                    </div>
                    <div class="flex-grow-1 d-flex flex-column gap-1">
                        <div class="d-flex align-items-center gap-1">
                            <p class="fw-semibold mb-0" style="font-size: 0.9rem;">Payment Pending</p>
                        </div>
                        <p class="mb-0" style="font-size: 0.8rem;">Tim kami sedang memeriksa transaksi ada pada booking berikut</p>
                    </div>
                </div>
            @endif
            <hr class="my-3">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px; background-color: #F6F6F6;">
                    <div class="d-flex justify-content-center align-items-center h-100 w-100">
                        <img src="{{ Storage::url($details->product->thumbnail) }}" class="img-fluid object-fit-contain" alt="thumbnail">
                    </div>
                </div>
                <div class="flex-grow-1 d-flex flex-column gap-2">
                    <p class="fw-bold mb-0">{{ $details->product->name }}</p>
                </div>
            </div>
            <section class="d-flex flex-column gap-4">
                <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-person-circle"></i> Customer Information</h2> {{-- Added icon --}}
                <div class="card p-4 d-flex flex-column gap-3">
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</p> {{-- Added icon --}}
                        <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                            <i class="bi bi-person fs-5"></i>
                            <p class="fw-semibold mb-0">{{ $details->name }}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</p> {{-- Added icon --}}
                        <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                            <i class="bi bi-telephone fs-5"></i>
                            <p class="fw-semibold mb-0">{{ $details->phone_number }}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Started At</p> {{-- Added icon --}}
                        <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                            <i class="bi bi-calendar-event fs-5"></i>
                            <p class="fw-semibold mb-0">{{ $details->started_at->format('d m Y') }}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Ended At</p> {{-- Added icon --}}
                        <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                            <i class="bi bi-calendar-event fs-5"></i>
                            <p class="fw-semibold mb-0">{{ $details->ended_at->format('d m Y') }}</p>
                        </div>
                    </div>
                    @if ($details->delivery_type == 'pickup')
                        <div class="d-flex flex-column gap-2">
                            <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-building"></i> Pickup Location</p> {{-- Added icon --}}
                            <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                                <i class="bi bi-building fs-5"></i>
                                <div class="d-flex flex-column gap-1">
                                    <p class="fw-semibold mb-0">Main Business Address</p>
                                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Jl. Puspowarno Ds. Tales Dsn. Cakruk Kec. Ngadiluwih Kab. Kediri</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-2">
                            <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-house-door"></i> Home Deliver to</p> {{-- Added icon --}}
                            <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                                <i class="bi bi-house-door fs-5"></i>
                                <div class="d-flex flex-column gap-1">
                                    <p class="fw-semibold mb-0">{{ $details->address }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
            <hr class="my-3">
            <div id="Payment-details" class="d-flex flex-column gap-3">
                <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-credit-card"></i> Payment Details</h2> {{-- Added icon --}}
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0">Grand total</p>
                        <p class="fw-bold fs-5 mb-0 text-decoration-underline">Rp {{ Number::format($details->total_amount, locale: 'id') }}</p>
                    </div>
                </div>
            </div>
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
