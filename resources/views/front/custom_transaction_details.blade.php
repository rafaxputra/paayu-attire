@extends('front.layouts.app')
@section('title', 'Custom Order Details')

@section('content')
<main class="main-content-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.transactions') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Custom Order Details</p>
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
                <p class="text-muted mb-0 small">Your Custom Order ID</p>
            </div>
        </div>

        @if ($details->status !== \App\Enums\CustomTransactionStatus::REJECTED && $details->status !== \App\Enums\CustomTransactionStatus::CANCELLED && $details->status !== \App\Enums\CustomTransactionStatus::PAYMENT_FAILED)
            <div class="card p-3">
                <p class="fw-semibold mb-3 text-center">Order Progress</p>
                @php
                    $statuses = [ \App\Enums\CustomTransactionStatus::PENDING, \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION, \App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED, \App\Enums\CustomTransactionStatus::IN_PROGRESS, \App\Enums\CustomTransactionStatus::COMPLETED ];
                    $statusLabels = [
                        \App\Enums\CustomTransactionStatus::PENDING->value => 'Pending',
                        \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION->value => 'Payment',
                        \App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED->value => 'Validated',
                        \App\Enums\CustomTransactionStatus::IN_PROGRESS->value => 'In Progress',
                        \App\Enums\CustomTransactionStatus::COMPLETED->value => 'Completed',
                    ];
                     $currentStatusIndex = match ($details->status) {
                        \App\Enums\CustomTransactionStatus::PENDING => 0,
                        \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION, \App\Enums\CustomTransactionStatus::PAYMENT_FAILED => 1,
                        \App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED => 2,
                        \App\Enums\CustomTransactionStatus::IN_PROGRESS => 3,
                        \App\Enums\CustomTransactionStatus::COMPLETED => 4,
                        default => -1,
                    };
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
        
        @if($details->admin_price || $details->admin_estimated_completion_date)
            <div class="card p-3">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-chat-dots"></i> Admin Response</h2>
                <hr class="my-3">
                @if($details->admin_price)
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0">Price:</p>
                    <p class="fw-bold fs-5 mb-0">Rp {{ Number::format($details->admin_price, locale: 'id') }}</p>
                </div>
                @endif
                @if($details->admin_estimated_completion_date)
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <p class="mb-0">Estimated Completion:</p>
                    <p class="mb-0 fw-semibold">{{ $details->admin_estimated_completion_date->format('d M Y') }}</p>
                </div>
                @endif
            </div>
        @endif

        @php
            // Status Card Logic
            $statusInfo = match ($details->status) {
                \App\Enums\CustomTransactionStatus::PENDING => ['text' => 'Pending Verification', 'icon' => 'bi-hourglass-split', 'class' => 'bg-warning'],
                \App\Enums\CustomTransactionStatus::REJECTED => ['text' => 'Pesanan Ditolak', 'icon' => 'bi-x-circle', 'class' => 'bg-danger'],
                \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION => ['text' => 'Pending Payment Verification', 'icon' => 'bi-cash-coin', 'class' => 'bg-info'],
                \App\Enums\CustomTransactionStatus::PAYMENT_FAILED => ['text' => 'Payment Failed', 'icon' => 'bi-x-circle', 'class' => 'bg-danger'],
                \App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED => ['text' => 'Payment Validated', 'icon' => 'bi-check-circle', 'class' => 'bg-success'],
                \App\Enums\CustomTransactionStatus::IN_PROGRESS => ['text' => 'In Progress', 'icon' => 'bi-gear', 'class' => 'bg-primary'],
                \App\Enums\CustomTransactionStatus::COMPLETED => ['text' => 'Completed', 'icon' => 'bi-check-all', 'class' => 'bg-success'],
                \App\Enums\CustomTransactionStatus::CANCELLED => ['text' => 'Cancelled', 'icon' => 'bi-slash-circle', 'class' => 'bg-secondary'],
            };
            $additionalMessage = match ($details->status) {
                 \App\Enums\CustomTransactionStatus::PENDING => 'Pesanan custom Anda sedang menunggu verifikasi dan estimasi harga dari admin. Mohon tunggu pemberitahuan selanjutnya.',
                \App\Enums\CustomTransactionStatus::REJECTED => 'Maaf, pesanan custom Anda telah ditolak. Silakan hubungi layanan pelanggan untuk informasi lebih lanjut.',
                \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION => 'Admin telah memberikan estimasi harga dan tanggal penyelesaian. Silakan lakukan pembayaran dan unggah bukti pembayaran.',
                \App\Enums\CustomTransactionStatus::PAYMENT_FAILED => 'Bukti pembayaran Anda tidak valid atau pembayaran gagal. Mohon unggah ulang bukti pembayaran yang benar atau hubungi layanan pelanggan.',
                \App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED => 'Pembayaran Anda sudah kami terima. Pesanan custom Anda akan segera diproses.',
                \App\Enums\CustomTransactionStatus::IN_PROGRESS => 'Pesanan custom Anda sedang dalam proses pengerjaan. Kami akan memberitahu Anda jika sudah selesai.',
                \App\Enums\CustomTransactionStatus::COMPLETED => 'Pesanan custom Anda telah selesai dan siap diambil. Anda dapat mengambil pesanan custom Anda di alamat yang tertera <a href="' . route('front.contact') . '" class="text-white text-decoration-underline">di sini</a>.',
                \App\Enums\CustomTransactionStatus::CANCELLED => 'Pesanan custom Anda telah dibatalkan.',
            };
        @endphp
        
        <div class="card status-card p-3 d-flex flex-column gap-3 {{ $statusInfo['class'] }}">
            <div class="d-flex flex-row align-items-center gap-3">
                <div class="flex-shrink-0"><i class="bi {{ $statusInfo['icon'] }} fs-4"></i></div>
                <div class="flex-grow-1 d-flex flex-column gap-1">
                    <p class="fw-semibold mb-0">{{ $statusInfo['text'] }}</p>
                    <p class="mb-0 small">{!! $additionalMessage !!}</p>
                </div>
            </div>

            @if ($details->status === \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION || $details->status === \App\Enums\CustomTransactionStatus::PAYMENT_FAILED)
                <hr class="my-2 border-white opacity-25">
                <form id="payment-upload-form" action="{{ route('front.custom.uploadPaymentProof', $details->id) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3">
                    @csrf
                    <select name="payment_method" class="form-select" required><option value="">Pilih Bank</option><option value="BCA">BCA</option><option value="BRI">BRI</option></select>
                    <input type="file" name="payment_proof" class="form-control" accept=".jpg,.png" required>
                    <div class="form-check text-start"><input class="form-check-input" type="checkbox" id="confirm_payment" name="confirm_payment" required><label class="form-check-label" for="confirm_payment">Saya benar telah transfer</label></div>
                    <div class="d-flex gap-2">
                        <button type="submit" form="payment-upload-form" class="btn btn-light w-100">Confirm Payment</button>
                        <button type="button" class="btn btn-outline-light w-100" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel</button>
                    </div>
                </form>
            @endif
             @if($details->status === \App\Enums\CustomTransactionStatus::PENDING)
                <hr class="my-2 border-dark opacity-25">
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel Order</button>
            @endif
        </div>

        @if($details->payment_proof && $details->status !== \App\Enums\CustomTransactionStatus::PENDING && $details->status !== \App\Enums\CustomTransactionStatus::REJECTED)
            <div class="card p-3">
                <h2 class="h6 mb-2 fw-semibold"><i class="bi bi-image"></i> Uploaded Payment Proof</h2>
                <img src="{{ Storage::url($details->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded-3">
            </div>
        @endif

        <div class="card p-4">
            <h2 class="h6 mb-3 fw-semibold"><i class="bi bi-person-circle"></i> Customer Information</h2>
            <div class="d-flex flex-column gap-3">
                <p class="mb-0"><strong class="fw-semibold">Full Name:</strong><br>{{ $details->name }}</p>
                <p class="mb-0"><strong class="fw-semibold">Phone Number:</strong><br>{{ $details->phone_number }}</p>
            </div>
        </div>

        <div class="card p-4">
            <h2 class="h6 mb-3 fw-semibold"><i class="bi bi-file-earmark-text"></i> Order Details</h2>
            <div class="d-flex flex-column gap-3">
                <p class="mb-1"><strong class="fw-semibold">Image References:</strong></p>
                <div class="row g-2">
                    @if($details->image_reference)<div class="col-4"><img src="{{ Storage::url($details->image_reference) }}" class="img-fluid rounded-3"></div>@endif
                    @if($details->image_reference_2)<div class="col-4"><img src="{{ Storage::url($details->image_reference_2) }}" class="img-fluid rounded-3"></div>@endif
                    @if($details->image_reference_3)<div class="col-4"><img src="{{ Storage::url($details->image_reference_3) }}" class="img-fluid rounded-3"></div>@endif
                </div>
                <hr class="my-2">
                <p class="mb-0"><strong class="fw-semibold">Material:</strong> {{ $details->material ?? '-' }}</p>
                <p class="mb-0"><strong class="fw-semibold">Color:</strong> {{ $details->color ?? '-' }}</p>
                <p class="mb-0"><strong class="fw-semibold">Selected Size:</strong> {{ $details->selected_size_chart ?? '-' }}</p>
                @if($details->selected_size_chart === 'custom')
                    <p class="mb-0"><strong class="fw-semibold">Lebar Bahu:</strong> {{ $details->lebar_bahu_belakang }} cm</p>
                    <p class="mb-0"><strong class="fw-semibold">Lingkar Panggul:</strong> {{ $details->lingkar_panggul }} cm</p>
                    <p class="mb-0"><strong class="fw-semibold">Lingkar Pinggul:</strong> {{ $details->lingkar_pinggul }} cm</p>
                    <p class="mb-0"><strong class="fw-semibold">Lingkar Dada:</strong> {{ $details->lingkar_dada }} cm</p>
                    <p class="mb-0"><strong class="fw-semibold">Kerung Lengan:</strong> {{ $details->kerung_lengan }} cm</p>
                @endif
                <p class="mb-0"><strong class="fw-semibold">Kebaya Preference:</strong> {{ $details->kebaya_preference }}</p>
                <p class="mb-0"><strong class="fw-semibold">Quantity:</strong> {{ $details->amount_to_buy }}</p>
                <p class="mb-0"><strong class="fw-semibold">Date Needed:</strong> {{ $details->date_needed->format('d M Y') }}</p>
            </div>
        </div>

    </section>

    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5" id="cancelOrderModalLabel">Cancel Order</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this order? This action cannot be undone.
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="{{ route('front.custom.cancel', $details->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="https://wa.me/6285183004324" class="btn btn-primary w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> Hubungi Layanan Pelanggan</a>
            </div>
        </div>
    </div>
</main>
@endsection