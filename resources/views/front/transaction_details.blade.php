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
            @if ($details->status)
                <div class="card p-3">
                    <p class="fw-semibold mb-2">Progress Pemesanan</p>
                    <div class="d-flex align-items-center justify-content-between position-relative" style="width: 100%; padding: 10px 0;">
                        @php
                            // Using the RentalTransactionStatus enum for clarity
                            $statuses = [\App\Enums\RentalTransactionStatus::PENDING, \App\Enums\RentalTransactionStatus::PAID, \App\Enums\RentalTransactionStatus::IN_RENTAL, \App\Enums\RentalTransactionStatus::COMPLETED];
                            $statusLabels = [
                                \App\Enums\RentalTransactionStatus::PENDING->value => 'Menunggu Pembayaran',
                                \App\Enums\RentalTransactionStatus::PAID->value => 'Pembayaran Terverifikasi',
                                \App\Enums\RentalTransactionStatus::IN_RENTAL->value => 'Dalam Penyewaan',
                                \App\Enums\RentalTransactionStatus::COMPLETED->value => 'Selesai'
                            ];
                            // Map current status to the simplified progress steps
                            $currentStatusIndex = match ($details->status) {
                                \App\Enums\RentalTransactionStatus::PENDING, \App\Enums\RentalTransactionStatus::REJECTED, \App\Enums\RentalTransactionStatus::CANCELLED => 0,
                                \App\Enums\RentalTransactionStatus::ACCEPTED, \App\Enums\RentalTransactionStatus::PENDING_PAYMENT => 0, // Still waiting for payment
                                \App\Enums\RentalTransactionStatus::PAID => 1,
                                \App\Enums\RentalTransactionStatus::IN_RENTAL => 2,
                                \App\Enums\RentalTransactionStatus::COMPLETED => 3,
                                default => 0, // Fallback
                            };
                        @endphp

                        @foreach ($statuses as $index => $status)
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle
                                    @if ($details->status === \App\Enums\RentalTransactionStatus::REJECTED && $index === 0) bg-danger text-white
                                    @elseif ($details->status === \App\Enums\RentalTransactionStatus::CANCELLED && $index === 0) bg-danger text-white
                                    @elseif ($currentStatusIndex >= $index) bg-primary text-white
                                    @else bg-secondary text-dark
                                    @endif"
                                    style="width: 30px; height: 30px; display: flex; justify-content: center; align-items: center;">
                                    {{ $index + 1 }}
                                </div>
                                <p class="text-center" style="font-size: 0.8rem; margin-top: 5px;">
                                     @if ($details->status === \App\Enums\RentalTransactionStatus::REJECTED && $index === 0)
                                        Ditolak
                                    @elseif ($details->status === \App\Enums\RentalTransactionStatus::CANCELLED && $index === 0)
                                        Dibatalkan
                                    @else
                                        {{ $statusLabels[$status->value] ?? $status->getLabel() }}
                                    @endif
                                </p>
                            </div>

                            @if ($index < count($statuses) - 1)
                                <div class="flex-grow-1 bg-secondary" style="height: 5px;"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
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

            {{-- Show Pickup Info if status is completed --}}
            @if($details->status === \App\Enums\RentalTransactionStatus::COMPLETED)
                <section class="d-flex flex-column gap-4">
                    <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-shop"></i> Pickup Information</h2> {{-- Changed icon and label --}}
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-building"></i> Pickup Location:</p> {{-- Added icon --}}
                        <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                            <i class="bi bi-building fs-5"></i>
                            <div class="d-flex flex-column gap-1">
                                <p class="fw-semibold mb-0">Main Business Address</p>
                                <p class="text-muted mb-0" style="font-size: 0.9rem;">Jl. Puspowarno Ds. Tales Dsn. Cakruk Kec. Ngadiluwih Kab. Kediri</p>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

        </section>
        <div id="Bottom-nav" class="fixed-bottom bg-white border-top">
            <div class="container main-content-container">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <a href="https://wa.me/6285183004324" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> Contact Customer Service</a> {{-- Added icon and flex/gap --}}
                </div>
            </div>
        </div>
    </main>
@endsection
