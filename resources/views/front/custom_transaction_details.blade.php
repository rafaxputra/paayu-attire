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
<section class="d-flex flex-column gap-4 mt-4 px-3">
@if (session('success'))
<div class="alert alert-success" role="alert">
{{ session('success') }}
</div>
@endif
@if ($errors->any())1
<div class="alert alert-danger" role="alert">
<ul class="mb-0">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif
<div class="card p-4 d-flex flex-row2 align-items-center gap-3">
<div class="flex-shrink-0 rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
<i class="bi bi-card-heading text-white fs-4"></i>
</div>
<div class="flex-grow-1 d-flex flex-column">
<p class="fw-semibold mb-0">{{ $details->trx_id }}</p>
<p class="text-muted mb-0" style="font-size: 0.9rem;">Your Custom Order ID</p>
</div>
</div>
@if ($details->status !== \App\Enums\CustomTransactionStatus::REJECTED && $details->status !== \App\Enums\CustomTransactionStatus::CANCELLED && $details->status !== \App\Enums\CustomTransactionStatus::PAYMENT_FAILED)
<div class="card p-3">
<p class="fw-semibold mb-2">Order Progress</p>
<div class="d-flex align-items-start position-relative" style="width: 100%; padding: 10px 0;">
@php
$statuses = [
\App\Enums\CustomTransactionStatus::PENDING,
\App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION,
\App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED,
\App\Enums\CustomTransactionStatus::IN_PROGRESS,
\App\Enums\CustomTransactionStatus::COMPLETED,
];
$statusLabels = [
\App\Enums\CustomTransactionStatus::PENDING->value => 'Pending<br>Verification',
\App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION->value => 'Pending<br>Payment',
\App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED->value => 'Payment<br>Validated',
\App\Enums\CustomTransactionStatus::IN_PROGRESS->value => 'In<br>Progress',
\App\Enums\CustomTransactionStatus::COMPLETED->value => 'Completed',
];
$currentStatusIndex = match ($details->status) {
\App\Enums\CustomTransactionStatus::PENDING => 0,
\App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION, \App\Enums\CustomTransactionStatus::PAYMENT_FAILED => 1,
\App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED => 2,
\App\Enums\CustomTransactionStatus::IN_PROGRESS => 3,
\App\Enums\CustomTransactionStatus::COMPLETED => 4,
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
@if($details->admin_price || $details->admin_estimated_completion_date)
<section class="d-flex flex-column gap-4 card p-3">
<h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-chat-dots"></i> Admin Response</h2>
@if($details->admin_price)
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-currency-dollar"></i> Price:</p>
<p class="fw-bold fs-5 mb-0">Rp {{ Number::format($details->admin_price, locale: 'id') }}</p>
</div>
@endif
@if($details->admin_estimated_completion_date)
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-check"></i> Estimated Completion Date:</p>
<p class="mb-0">{{ $details->admin_estimated_completion_date->format('d m Y') }}</p>
</div>
@endif
</section>
@endif
@php
$statusText = '';
$statusIcon = '';
$statusClass = '';
$additionalMessage = '';
@endphp
<div class="card p-3 d-flex flex-row align-items-center gap-3
@php
switch ($details->status) {
case \App\Enums\CustomTransactionStatus::PENDING:
echo 'bg-warning text-dark';
$statusText = 'Pending Verification';
$statusIcon = 'bi-hourglass-split';
$additionalMessage = 'Pesanan custom Anda sedang menunggu verifikasi dan estimasi harga dari admin. Mohon tunggu pemberitahuan selanjutnya.';
break;
case \App\Enums\CustomTransactionStatus::REJECTED:
echo 'bg-danger text-white';
$statusText = 'Pesanan Ditolak';
$statusIcon = 'bi-x-circle';
$additionalMessage = 'Maaf, pesanan custom Anda telah ditolak. Silakan hubungi layanan pelanggan untuk informasi lebih lanjut.';
break;
case \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION:
echo 'bg-info text-dark';
$statusText = 'Pending Payment Verification';
$statusIcon = 'bi-cash-coin';
$additionalMessage = 'Admin telah memberikan estimasi harga dan tanggal penyelesaian. Silakan lakukan pembayaran dan unggah bukti pembayaran.';
break;
case \App\Enums\CustomTransactionStatus::PAYMENT_FAILED:
echo 'bg-danger text-white';
$statusText = 'Payment Failed';
$statusIcon = 'bi-x-circle';
$additionalMessage = 'Bukti pembayaran Anda tidak valid atau pembayaran gagal. Mohon unggah ulang bukti pembayaran yang benar atau hubungi layanan pelanggan.';
break;
case \App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED:
echo 'bg-success text-white';
$statusText = 'Payment Validated';
$statusIcon = 'bi-check-circle';
$additionalMessage = 'Pembayaran Anda sudah kami terima. Pesanan custom Anda akan segera diproses.';
break;
case \App\Enums\CustomTransactionStatus::IN_PROGRESS:
echo 'bg-primary text-white';
$statusText = 'In Progress';
$statusIcon = 'bi-gear';
$additionalMessage = 'Pesanan custom Anda sedang dalam proses pengerjaan. Kami akan memberitahu Anda jika sudah selesai.';
break;
case \App\Enums\CustomTransactionStatus::COMPLETED:
echo 'bg-success text-white';
$statusText = 'Completed';
$statusIcon = 'bi-check-all';
$additionalMessage = 'Pesanan custom Anda telah selesai dan siap diambil. Anda dapat mengambil pesanan custom Anda di alamat yang tertera <a href="' . route('front.contact') . '" class="text-white text-decoration-underline">di sini</a>.';
break;
case \App\Enums\CustomTransactionStatus::CANCELLED:
echo 'bg-secondary text-white';
$statusText = 'Cancelled';
$statusIcon = 'bi-slash-circle';
$additionalMessage = 'Pesanan custom Anda telah dibatalkan.';
break;
}
@endphp
">
<div class="flex-shrink-0">
<i class="bi {{ $statusIcon }} fs-4"></i>
</div>
<div class="flex-grow-1 d-flex flex-column gap-1">
<div class="d-flex align-items-center gap-1">
<p class="fw-semibold mb-0" style="font-size: 0.9rem;">{{ $statusText }}</p>
@if (in_array($details->status, [\App\Enums\CustomTransactionStatus::PAYMENT_VALIDATED, \App\Enums\CustomTransactionStatus::COMPLETED]))
<i class="bi bi-patch-check-fill"></i>
@endif
</div>
<p class="mb-0" style="font-size: 0.8rem;">{!! $additionalMessage !!}</p>
@if ($details->status === \App\Enums\CustomTransactionStatus::PENDING_PAYMENT_VERIFICATION || $details->status === \App\Enums\CustomTransactionStatus::PAYMENT_FAILED)
<form id="payment-upload-form" action="{{ route('front.custom.uploadPaymentProof', $details->id) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-4 mt-3">
@csrf
<div class="d-flex flex-column gap-2">
<label for="payment_method" class="form-label text-white fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-cash-coin"></i> Payment Method</label>
<select name="payment_method" id="payment_method" class="form-select" required>
<option value="">Pilih Bank</option>
<option value="BCA">BCA</option>
<option value="BRI">BRI</option>
</select>
</div>
<div class="d-flex flex-column gap-2">
<label for="payment_proof" class="form-label text-white fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-upload"></i> Upload Proof</label>
<div class="input-group">
<span class="input-group-text">
<i class="bi bi-upload"></i>
</span>
<input type="file" name="payment_proof" id="payment_proof" class="form-control" accept=".jpg,.png" required>
</div>
<small class="form-text text-white">Accepted formats: JPG, PNG</small>
</div>
<div class="form-check">
<input class="form-check-input" type="checkbox" id="confirm_payment" name="confirm_payment" required>
<label class="form-check-label text-white fw-semibold" for="confirm_payment">
Saya benar telah transfer pembayaran
</label>
</div>
<button type="submit" form="payment-upload-form" class="btn btn-light rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2">
<i class="bi bi-check-circle"></i> Confirm Payment
</button>
</form>
<form action="{{ route('front.custom.cancel', $details->id) }}" method="POST">
@csrf
@method('PUT')
<button type="submit" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-x-circle"></i> Cancel Order</button>
</form>
@endif
@if($details->status === \App\Enums\CustomTransactionStatus::PENDING)
<form action="{{ route('front.custom.cancel', $details->id) }}" method="POST">
@csrf
@method('PUT')
<button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-x-circle"></i> Cancel Order</button>
</form>
@endif
</div>
</div>
@if($details->payment_proof && $details->status !== \App\Enums\CustomTransactionStatus::PENDING && $details->status !== \App\Enums\CustomTransactionStatus::REJECTED)
<div class="d-flex flex-column gap-2 card p-3">
<h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-image"></i> Uploaded Payment Proof</h2>
<img src="{{ Storage::url($details->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded-md">
</div>
@endif
<section class="d-flex flex-column gap-4">
<h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-person-circle"></i> Customer Information</h2>
<div class="card p-4 d-flex flex-column gap-3">
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0">Full Name</p>
<div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
<i class="bi bi-person fs-5"></i>
<p class="fw-semibold mb-0">{{ $details->name }}</p>
</div>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0">Phone Number</p>
<div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
<i class="bi bi-telephone fs-5"></i>
<p class="fw-semibold mb-0">{{ $details->phone_number }}</p>
</div>
</div>
</div>
</section>
<section class="d-flex flex-column gap-4">
<h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-file-earmark-text"></i> Order Details</h2>
<div class="card p-4 d-flex flex-column gap-3">
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2">
<i class="bi bi-image"></i> Image Reference 1:
</p>
@if($details->image_reference)
<img src="{{ Storage::url($details->image_reference) }}" alt="Kebaya Reference Image 1" class="img-fluid rounded-md">
@else
<p class="mb-0">-</p>
@endif
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2 mt-3">
<i class="bi bi-image"></i> Image Reference 2:
</p>
@if($details->image_reference_2)
<img src="{{ Storage::url($details->image_reference_2) }}" alt="Kebaya Reference Image 2" class="img-fluid rounded-md">
@else
<p class="mb-0">-</p>
@endif
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2 mt-3">
<i class="bi bi-image"></i> Image Reference 3:
</p>
@if($details->image_reference_3)
<img src="{{ Storage::url($details->image_reference_3) }}" alt="Kebaya Reference Image 3" class="img-fluid rounded-md">
@else
<p class="mb-0">-</p>
@endif
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-palette"></i> Material:</p>
<p class="mb-0">{{ $details->material ?? '-' }}</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-droplet"></i> Color:</p>
<p class="mb-0">{{ $details->color ?? '-' }}</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-rulers"></i> Selected Size:</p>
<p class="mb-0">{{ $details->selected_size_chart ?? '-' }}</p>
</div>
@if($details->selected_size_chart === 'custom')
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person-fill"></i> Lebar Bahu Belakang:</p>
<p class="mb-0">{{ $details->lebar_bahu_belakang }} cm</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-people-fill"></i> Lingkar Panggul:</p>
<p class="mb-0">{{ $details->lingkar_panggul }} cm</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person-badge-fill"></i> Lingkar Pinggul:</p>
<p class="mb-0">{{ $details->lingkar_pinggul }} cm</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-heart-fill"></i> Lingkar Dada:</p>
<p class="mb-0">{{ $details->lingkar_dada }} cm</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-hand-index-thumb-fill"></i> Kerung Lengan:</p>
<p class="mb-0">{{ $details->kerung_lengan }} cm</p>
</div>
@endif
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-file-text"></i> Kebaya Preference:</p>
<p class="mb-0">{{ $details->kebaya_preference }}</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-cart"></i> Quantity to Buy:</p>
<p class="mb-0">{{ $details->amount_to_buy }}</p>
</div>
<div class="d-flex flex-column gap-2">
<p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Date Kebaya Needed:</p>
<p class="mb-0">{{ $details->date_needed->format('d m Y') }}</p>
</div>
</div>
</section>
@if($details->status === \App\Enums\CustomTransactionStatus::COMPLETED)
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
<a href="[suspicious link removed]" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> Hubungi Layanan Pelanggan</a>
</div>
</div>
</div>
</main>
@endsection