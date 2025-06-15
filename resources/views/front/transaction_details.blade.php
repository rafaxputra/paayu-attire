@extends('front.layouts.app')
@section('title', 'Booking Details')

@section('content')
<main class="main-content-container py-4">
    <div id="Top-navbar" class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.customer.dashboard') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Booking Details</p>
        <button id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>

    <section class="d-flex flex-column gap-4">
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

        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));">
                <i class="bi bi-card-heading fs-4" style="color: white;"></i>
            </div>
            <div class="flex-grow-1 d-flex flex-column">
                <p class="fw-semibold mb-0">{{ $details->trx_id }}</p>
                <p class="text-muted mb-0 small">Lindungi ID pesanan Anda</p>
            </div>
        </div>

        @if ($details->status !== \App\Enums\RentalTransactionStatus::REJECTED && $details->status !== \App\Enums\RentalTransactionStatus::CANCELLED && $details->status !== \App\Enums\RentalTransactionStatus::PAYMENT_FAILED)
            <div class="card p-3">
                <p class="fw-semibold mb-3 text-center">Order Progress</p>
                @php
                    $statuses = [
                        \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION,
                        \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED,
                        \App\Enums\RentalTransactionStatus::IN_RENTAL,
                        \App\Enums\RentalTransactionStatus::COMPLETED,
                    ];
                    $statusLabels = [
                        \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION->value => 'Verifying',
                        \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED->value => 'Verified',
                        \App\Enums\RentalTransactionStatus::IN_RENTAL->value => 'Rented',
                        \App\Enums\RentalTransactionStatus::COMPLETED->value => 'Completed'
                    ];
                    $currentStatusIndex = array_search($details->status, $statuses);
                @endphp
                <div class="progress-stepper">
                    @foreach ($statuses as $index => $status)
                        <div class="step {{ $currentStatusIndex >= $index ? 'active' : '' }}">
                            <div class="step-icon">{{ $index + 1 }}</div>
                            <div class="step-label">{!! $statusLabels[$status->value] !!}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @php
            // Status Card Logic
            $statusInfo = match ($details->status) {
                \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION => ['text' => 'Menunggu Verifikasi Pembayaran', 'icon' => 'bi-hourglass-split', 'class' => 'bg-warning'],
                \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED => ['text' => 'Pembayaran Terverifikasi', 'icon' => 'bi-check-circle', 'class' => 'bg-success'],
                \App\Enums\RentalTransactionStatus::PAYMENT_FAILED => ['text' => 'Pembayaran Gagal/Tidak Valid', 'icon' => 'bi-x-circle', 'class' => 'bg-danger'],
                \App\Enums\RentalTransactionStatus::IN_RENTAL => ['text' => 'Dalam Penyewaan', 'icon' => 'bi-person-gear', 'class' => 'bg-info'],
                \App\Enums\RentalTransactionStatus::COMPLETED => ['text' => 'Penyewaan Selesai', 'icon' => 'bi-check-all', 'class' => 'bg-success'],
                \App\Enums\RentalTransactionStatus::REJECTED => ['text' => 'Pesanan Ditolak', 'icon' => 'bi-x-circle', 'class' => 'bg-danger'],
                \App\Enums\RentalTransactionStatus::CANCELLED => ['text' => 'Pesanan Dibatalkan', 'icon' => 'bi-slash-circle', 'class' => 'bg-secondary'],
            };
            $additionalMessage = match ($details->status) {
                \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION => 'Pembayaran Anda sedang dalam proses verifikasi oleh tim kami. Mohon tunggu konfirmasi.',
                \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED => 'Pembayaran Anda sudah kami terima. Kebaya sewaan siap diambil pada tanggal ' . $details->started_at->format('d M Y') . ' di alamat yang tertera <a href="' . route('front.contact') . '" class="text-white text-decoration-underline">di sini</a>. <br><b>Jangan lupa membawa kartu identitas (KTP atau identitas lain) sebagai jaminan saat pengambilan barang.</b>',
                \App\Enums\RentalTransactionStatus::PAYMENT_FAILED => 'Bukti pembayaran Anda tidak valid atau pembayaran gagal. Mohon unggah ulang bukti pembayaran yang benar atau hubungi layanan pelanggan.',
                \App\Enums\RentalTransactionStatus::IN_RENTAL => 'Kebaya sedang dalam masa penyewaan Anda. Mohon dikembalikan pada tanggal ' . $details->ended_at->format('d M Y') . '. Jika tidak dikembalikan pada tanggal tersebut maka akan terkena denda sebesar <b>Rp ' . number_format($details->total_amount * 0.2, 0, ',', '.') . '</b> x jumlah hari keterlambatan.',
                \App\Enums\RentalTransactionStatus::COMPLETED => 'Penyewaan kebaya telah selesai. Terima kasih telah menggunakan layanan kami.',
                \App\Enums\RentalTransactionStatus::REJECTED => 'Maaf, pesanan Anda telah ditolak. Silakan hubungi layanan pelanggan untuk informasi lebih lanjut.',
                \App\Enums\RentalTransactionStatus::CANCELLED => 'Pesanan Anda telah dibatalkan. Jika ini adalah kesalahan, mohon hubungi layanan pelanggan.',
            };
        @endphp

        <div class="card status-card p-3 d-flex flex-row align-items-center gap-3 {{ $statusInfo['class'] }}">
            <div class="flex-shrink-0"><i class="bi {{ $statusInfo['icon'] }} fs-4"></i></div>
            <div class="flex-grow-1 d-flex flex-column gap-1">
                <p class="fw-semibold mb-0">{{ $statusInfo['text'] }}</p>
                <p class="mb-0 small">{!! $additionalMessage !!}</p>
            </div>
        </div>
        
        <div class="card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px;">
                    <img src="{{ Storage::url($details->product->thumbnail) }}" class="img-fluid rounded-2" alt="thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="flex-grow-1">
                    <p class="fw-bold mb-0">{{ $details->product->name }}</p>
                    <span class="badge bg-secondary mt-2">Ukuran yang dipilih: {{ $details->selected_size }}</span>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <h2 class="h6 mb-3 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-person-circle"></i> Customer Information</h2>
            <div class="d-flex flex-column gap-3">
                <p class="mb-0"><strong class="fw-semibold">Full Name:</strong><br>{{ $details->name }}</p>
                <p class="mb-0"><strong class="fw-semibold">Phone Number:</strong><br>{{ $details->phone_number }}</p>
                <p class="mb-0"><strong class="fw-semibold">Started At:</strong><br>{{ $details->started_at->format('d M Y') }}</p>
                <p class="mb-0"><strong class="fw-semibold">Ended At:</strong><br>{{ $details->ended_at->format('d M Y') }}</p>
            </div>
        </div>
        
        <div class="card p-4">
            <h2 class="h6 mb-3 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-credit-card"></i> Payment Details</h2>
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0">Grand total</p>
                <p class="fw-bold fs-5 mb-0 text-decoration-underline">Rp {{ Number::format($details->total_amount, locale: 'id') }}</p>
            </div>
        </div>

        @php
            $lateDays = $details->late_days;
            $lateFee = $details->late_fee;
            if ($details->status === 'late_returned') {
                if ($lateDays === null) {
                    $lateDays = now()->diffInDays($details->ended_at);
                }
                if ($lateFee === null && $lateDays > 0) {
                    $lateFee = $lateDays * ($details->total_amount * 0.2);
                }
            }
        @endphp
        @if($details->status === 'late_returned' && $lateDays > 0 && $lateFee > 0)
        <div class="card status-card p-3 d-flex flex-row align-items-center gap-3 bg-danger">
            <div class="flex-shrink-0"><i class="bi bi-exclamation-triangle fs-4"></i></div>
            <div class="flex-grow-1 d-flex flex-column gap-1">
                <p class="fw-semibold mb-0">Denda Keterlambatan</p>
                <p class="mb-0 small">Anda terlambat mengembalikan barang selama <b>{{ $lateDays }}</b> hari.<br>Total denda yang harus dibayar: <b>Rp {{ number_format($lateFee, 0, ',', '.') }}</b>.<br>Silakan bayar denda ini saat mengembalikan barang sewaan.</p>
            </div>
        </div>
        @elseif($details->status === 'completed')
        <div class="card status-card p-3 d-flex flex-row align-items-center gap-3 bg-success">
            <div class="flex-shrink-0"><i class="bi bi-check-all fs-4"></i></div>
            <div class="flex-grow-1 d-flex flex-column gap-1">
                <p class="fw-semibold mb-0">Penyewaan Selesai</p>
                <p class="mb-0 small">Penyewaan kebaya telah selesai. Terima kasih telah menggunakan layanan kami.</p>
            </div>
        </div>
        @endif

    </section>

    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="https://wa.me/6285183004324" class="btn btn-primary w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> Hubungi Layanan Pelanggan</a>
            </div>
        </div>
    </div>
</main>
@endsection