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
<button id="theme-toggle" class="btn p-0">
<i class="bi bi-moon fs-4"></i>
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
<i class="bi bi-card-heading1 text-white fs-4"></i>
</div>
<div class="flex-grow-1 d-flex flex-column">
<p class="fw-semibold mb-0">{{ $details->trx_id }}</p>
<p class="text-muted mb-0" style="font-size: 0.9rem;">Lindungi ID pesanan Anda</p>
</div>
</div>
    @if ($details->status !== \App\Enums\RentalTransactionStatus::REJECTED && $details->status !== \App\Enums\RentalTransactionStatus::CANCELLED && $details->status !== \App\Enums\RentalTransactionStatus::PAYMENT_FAILED)
        <div class="card p-3">
            <p class="fw-semibold mb-2">Progress Pemesanan</p>
            <div class="d-flex align-items-start position-relative" style="width: 100%; padding: 10px 0;">
                @php
                    $statuses = [
                        \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION,
                        \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED,
                        \App\Enums\RentalTransactionStatus::IN_RENTAL,
                        \App\Enums\RentalTransactionStatus::COMPLETED,
                    ];
                    $statusLabels = [
                        \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION->value => 'Menunggu<br>Verifikasi',
                        \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED->value => 'Pembayaran<br>Terverifikasi',
                        \App\Enums\RentalTransactionStatus::IN_RENTAL->value => 'Dalam<br>Penyewaan',
                        \App\Enums\RentalTransactionStatus::COMPLETED->value => 'Selesai'
                    ];

                    $currentStatusIndex = match ($details->status) {
                        \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION => 0,
                        \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED,
                        \App\Enums\RentalTransactionStatus::READY_FOR_PICKUP => 1,
                        \App\Enums\RentalTransactionStatus::IN_RENTAL => 2,
                        \App\Enums\RentalTransactionStatus::COMPLETED => 3,
                        default => 0,
                    };
                @endphp

                @foreach ($statuses as $index => $status)
                    <div class="d-flex flex-column align-items-center" style="flex: 1;">
                        <div class="rounded-circle mb-1
                            @if ($currentStatusIndex >= $index) bg-primary text-white
                            @else bg-secondary text-dark
                            @endif"
                            style="width: 30px; height: 30px; display: flex; justify-content: center; align-items: center;">
                            {{ $index + 1 }}
                        </div>
                        <p class="text-center" style="font-size: 0.85rem; margin-top: 5px;">
                            {!! $statusLabels[$status->value] ?? $status->getLabel() !!}
                        </p>
                    </div>

                    @if ($index < count($statuses) - 1)
                        <div class="flex-grow-1 bg-secondary mt-3" style="height: 5px;"></div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Dynamic Status Messages and Actions --}}
    @php
        $statusText = '';
        $statusIcon = '';
        $statusClass = '';
        $additionalMessage = '';
        $actionButton = '';

        switch ($details->status) {
            case \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION:
                $statusText = 'Menunggu Verifikasi Pembayaran';
                $statusIcon = 'bi-hourglass-split';
                $statusClass = 'bg-warning text-dark';
                $additionalMessage = 'Pembayaran Anda sedang dalam proses verifikasi oleh tim kami. Mohon tunggu konfirmasi.';
                break;
            case \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED:
                $statusText = 'Pembayaran Terverifikasi';
                $statusIcon = 'bi-check-circle';
                $statusClass = 'bg-success text-white';
                $additionalMessage = 'Pembayaran Anda sudah kami terima. Kebaya sewaan siap diambil pada tanggal ' . $details->started_at->format('d M Y') . ' di alamat yang tertera <a href="' . route('front.contact') . '" class="text-white text-decoration-underline">di sini</a>.';
                break;
            case \App\Enums\RentalTransactionStatus::PAYMENT_FAILED:
                $statusText = 'Pembayaran Gagal/Tidak Valid';
                $statusIcon = 'bi-x-circle';
                $statusClass = 'bg-danger text-white';
                $additionalMessage = 'Bukti pembayaran Anda tidak valid atau pembayaran gagal. Mohon unggah ulang bukti pembayaran yang benar atau hubungi layanan pelanggan.';
                $actionButton = '<a href="{{ route("front.checkout", ["trx_id" => $details->trx_id]) }}" class="btn btn-light btn-sm mt-2">Unggah Ulang Bukti Pembayaran</a>';
                break;
            case \App\Enums\RentalTransactionStatus::READY_FOR_PICKUP:
                $statusText = 'Siap Diambil';
                $statusIcon = 'bi-box-seam';
                $statusClass = 'bg-primary text-white';
                $additionalMessage = 'Kebaya sewaan Anda siap diambil di alamat yang tertera <a href="' . route('front.contact') . '" class="text-white text-decoration-underline">di sini</a> pada tanggal ' . $details->started_at->format('d M Y') . '.';
                break;
            case \App\Enums\RentalTransactionStatus::IN_RENTAL:
                $statusText = 'Dalam Penyewaan';
                $statusIcon = 'bi-person-gear';
                $statusClass = 'bg-secondary text-white';
                $additionalMessage = 'Kebaya sedang dalam masa penyewaan Anda. Mohon dikembalikan pada tanggal ' . $details->ended_at->format('d M Y') . '.';
                break;
            case \App\Enums\RentalTransactionStatus::COMPLETED:
                $statusText = 'Penyewaan Selesai';
                $statusIcon = 'bi-check-all';
                $statusClass = 'bg-success text-white';
                $additionalMessage = 'Penyewaan kebaya telah selesai. Terima kasih telah menggunakan layanan kami.';
                break;
            case \App\Enums\RentalTransactionStatus::REJECTED:
                $statusText = 'Pesanan Ditolak';
                $statusIcon = 'bi-x-circle';
                $statusClass = 'bg-danger text-white';
                $additionalMessage = 'Maaf, pesanan Anda telah ditolak. Silakan hubungi layanan pelanggan untuk informasi lebih lanjut.';
                break;
            case \App\Enums\RentalTransactionStatus::CANCELLED:
                $statusText = 'Pesanan Dibatalkan';
                $statusIcon = 'bi-slash-circle';
                $statusClass = 'bg-secondary text-white';
                $additionalMessage = 'Pesanan Anda telah dibatalkan. Jika ini adalah kesalahan, mohon hubungi layanan pelanggan.';
                break;
        }
    @endphp

    <div class="card p-3 d-flex flex-row align-items-center gap-3 {{ $statusClass }}">
        <div class="flex-shrink-0">
            <i class="bi {{ $statusIcon }} fs-4"></i>
        </div>
        <div class="flex-grow-1 d-flex flex-column gap-1">
            <div class="d-flex align-items-center gap-1">
                <p class="fw-semibold mb-0" style="font-size: 0.9rem;">{{ $statusText }}</p>
                @if (in_array($details->status, [\App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED, \App\Enums\RentalTransactionStatus::COMPLETED]))
                    <i class="bi bi-patch-check-fill"></i>
                @endif
            </div>
            <p class="mb-0" style="font-size: 0.8rem;">{!! $additionalMessage !!}</p>
            @if ($actionButton)
                {!! $actionButton !!}
            @endif
        </div>
    </div>

    <hr class="my-3">
    <div class="d-flex align-items-center gap-3">
        <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px; background-color: #F6F6F6;">
            <div class="d-flex justify-content-center align-items-center h-100 w-100">
                <img src="{{ Storage::url($details->product->thumbnail) }}" class="img-fluid" alt="thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
        <div class="flex-grow-1 d-flex flex-column gap-2">
            <p class="fw-bold mb-0">{{ $details->product->name }}</p>
        </div>
    </div>
    <section class="d-flex flex-column gap-4">
        <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-person-circle"></i> Customer Information</h2>
        <div class="card p-4 d-flex flex-column gap-3">
            <div class="d-flex flex-column gap-2">
                <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</p>
                <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                    <i class="bi bi-person fs-5"></i>
                    <p class="fw-semibold mb-0">{{ $details->name }}</p>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</p>
                <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                    <i class="bi bi-telephone fs-5"></i>
                    <p class="fw-semibold mb-0">{{ $details->phone_number }}</p>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Started At</p>
                <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                    <i class="bi bi-calendar-event fs-5"></i>
                    <p class="fw-semibold mb-0">{{ $details->started_at->format('d m Y') }}</p>
                </div>
            </div>
            <div class="d-flex flex-column gap-2">
                <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Ended At</p>
                <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                    <i class="bi bi-calendar-event fs-5"></i>
                    <p class="fw-semibold mb-0">{{ $details->ended_at->format('d m Y') }}</p>
                </div>
            </div>
        </div>
    </section>
    <hr class="my-3">
    <div id="Payment-details" class="d-flex flex-column gap-3">
        <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-credit-card"></i> Payment Details</h2>
        <div class="d-flex flex-column gap-4">
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0">Grand total</p>
                <p class="fw-bold fs-5 mb-0 text-decoration-underline">Rp {{ Number::format($details->total_amount, locale: 'id') }}</p>
            </div>
        </div>
    </div>

    {{-- Show Pickup Info if status is PAYMENT_VALIDATED or READY_FOR_PICKUP or IN_RENTAL or COMPLETED --}}
    @if(in_array($details->status, [\App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED, \App\Enums\RentalTransactionStatus::READY_FOR_PICKUP, \App\Enums\RentalTransactionStatus::IN_RENTAL, \App\Enums\RentalTransactionStatus::COMPLETED]))
        <section class="d-flex flex-column gap-4">
            <h2 class="h6 mb-2 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-shop"></i> Pickup Information</h2>
            <div class="card p-4 d-flex flex-column gap-3">
                <div class="d-flex flex-column gap-1">
                    <p class="fw-semibold mb-1 d-flex align-items-center gap-2"><i class="bi bi-geo-alt"></i> Address:</p>
                    <p class="mb-0 text-muted">Jl. Puspowarno Ds. Tales Dsn. Cakruk Kec. Ngadiluwih Kab. Kediri</p>
                </div>
            </div>
        </section>
    @endif
</section>
<div id="Bottom-nav" class="fixed-bottom bg-white border-top">
    <div class="container main-content-container">
        <div class="d-flex align-items-center justify-content-between p-3">
            <a href="[https://wa.me/6285183004324](https://wa.me/6285183004324)" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> Hubungi Layanan Pelanggan</a>
        </div>
    </div>
</div>

</main>
@endsection
