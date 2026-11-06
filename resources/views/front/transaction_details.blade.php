@extends('front.layouts.app')
@section('title', 'Booking Details')

@section('content')
<main class="main-content-container py-4">
    <div id="Top-navbar" class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.customer.dashboard') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Booking Details</p>
        <button type="button" id="theme-toggle" class="btn p-0">
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
                <p class="text-muted mb-0 small">Keep your order ID safe</p>
            </div>
        </div>

        @if ($details->status !== \App\Enums\RentalTransactionStatus::REJECTED && $details->status !== \App\Enums\RentalTransactionStatus::CANCELLED && $details->status !== \App\Enums\RentalTransactionStatus::PAYMENT_FAILED)
            <div class="card p-3">
                <p class="fw-semibold mb-3 text-center">Order Progress</p>
                @php
                    $progressStatus = $details->status === \App\Enums\RentalTransactionStatus::LATE_RETURNED ? \App\Enums\RentalTransactionStatus::IN_RENTAL : $details->status;
                    $statuses = [
                        \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION,
                        \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED,
                        \App\Enums\RentalTransactionStatus::READY_FOR_PICKUP,
                        \App\Enums\RentalTransactionStatus::IN_RENTAL,
                        \App\Enums\RentalTransactionStatus::COMPLETED,
                    ];
                    $statusLabels = [
                        \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION->value => 'Verifying',
                        \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED->value => 'Verified',
                        \App\Enums\RentalTransactionStatus::READY_FOR_PICKUP->value => 'Ready',
                        \App\Enums\RentalTransactionStatus::IN_RENTAL->value => 'Rented',
                        \App\Enums\RentalTransactionStatus::COMPLETED->value => 'Completed'
                    ];
                    $currentStatusIndex = array_search($progressStatus, $statuses);
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
            $lateInfo = $details->late_info;
        @endphp
        @if($details->status === \App\Enums\RentalTransactionStatus::LATE_RETURNED && $lateInfo && $lateInfo['late_days'] > 0 && $lateInfo['late_fee'] > 0)
        <div class="card status-card p-3 d-flex flex-row align-items-center gap-3 bg-danger mb-3">
            <div class="flex-shrink-0"><i class="bi bi-exclamation-triangle fs-4"></i></div>
            <div class="flex-grow-1 d-flex flex-column gap-1">
                <p class="fw-semibold mb-0">Late Fee</p>
                <p class="mb-0 small">You returned the item <b>{{ $lateInfo['late_days'] }}</b> days late.<br>Total late fee to pay: <b>Rp {{ number_format($lateInfo['late_fee'], 0, ',', '.') }}</b>.<br>Please pay this fee when returning the rented item.</p>
            </div>
        </div>
        @endif
        @php
            $statusInfo = match ($details->status) {
                \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION => ['text' => 'Awaiting Payment Verification', 'icon' => 'bi-hourglass-split', 'class' => 'bg-warning'],
                \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED => ['text' => 'Payment Verified', 'icon' => 'bi-check-circle', 'class' => 'bg-success'],
                \App\Enums\RentalTransactionStatus::PAYMENT_FAILED => ['text' => 'Payment Failed/Invalid', 'icon' => 'bi-x-circle', 'class' => 'bg-danger'],
                \App\Enums\RentalTransactionStatus::IN_RENTAL => ['text' => 'In Rental', 'icon' => 'bi-person-gear', 'class' => 'bg-info'],
                \App\Enums\RentalTransactionStatus::COMPLETED => ['text' => 'Rental Completed', 'icon' => 'bi-check-all', 'class' => 'bg-success'],
                \App\Enums\RentalTransactionStatus::REJECTED => ['text' => 'Order Rejected', 'icon' => 'bi-x-circle', 'class' => 'bg-danger'],
                \App\Enums\RentalTransactionStatus::CANCELLED => ['text' => 'Order Cancelled', 'icon' => 'bi-slash-circle', 'class' => 'bg-secondary'],
                \App\Enums\RentalTransactionStatus::LATE_RETURNED => ['text' => '', 'icon' => '', 'class' => ''],
                \App\Enums\RentalTransactionStatus::READY_FOR_PICKUP => ['text' => 'Ready for Pickup', 'icon' => 'bi-box-seam', 'class' => 'bg-primary'],
            };
            $additionalMessage = match ($details->status) {
                \App\Enums\RentalTransactionStatus::PENDING_PAYMENT_VERIFICATION => 'Your payment is being verified by our team. Please wait for confirmation.',
                \App\Enums\RentalTransactionStatus::PAYMENT_VALIDATED => 'We have received your payment. Your rented kebaya is ready for pickup on ' . $details->started_at->format('d M Y') . ' at the address listed <a href="' . route('front.contact') . '" class="text-white text-decoration-underline">here</a>. <br><b>Please bring an ID card (KTP or other valid identification) as a guarantee when picking up the item.</b>',
                \App\Enums\RentalTransactionStatus::PAYMENT_FAILED => 'Your payment proof is invalid or the payment failed. Please re-upload a valid payment proof or contact customer service.',
                \App\Enums\RentalTransactionStatus::IN_RENTAL => 'The kebaya is currently in your rental period. Please return it by ' . $details->ended_at->format('d M Y') . '. If it is not returned by that date, a penalty of <b>Rp ' . number_format($details->total_amount * 0.2, 0, ',', '.') . '</b> x number of late days will apply.',
                \App\Enums\RentalTransactionStatus::COMPLETED => 'Your kebaya rental is complete. Thank you for using our service.',
                \App\Enums\RentalTransactionStatus::REJECTED => 'Sorry, your order has been rejected. Please contact customer service for more information.',
                \App\Enums\RentalTransactionStatus::CANCELLED => 'Your order has been cancelled. If this is a mistake, please contact customer service.',
                \App\Enums\RentalTransactionStatus::LATE_RETURNED => '',
                \App\Enums\RentalTransactionStatus::READY_FOR_PICKUP => 'Your item is ready for pickup. Please come to our location and bring an ID card as a guarantee.',
            };
        @endphp
        @if($details->status !== \App\Enums\RentalTransactionStatus::LATE_RETURNED && $details->status !== \App\Enums\RentalTransactionStatus::PAYMENT_FAILED)
        <div class="card status-card p-3 d-flex flex-row align-items-center gap-3 {{ $statusInfo['class'] }}">
            <div class="flex-shrink-0"><i class="bi {{ $statusInfo['icon'] }} fs-4"></i></div>
            <div class="flex-grow-1 d-flex flex-column gap-1">
                <p class="fw-semibold mb-0">{{ $statusInfo['text'] }}</p>
                <p class="mb-0 small">{!! $additionalMessage !!}</p>
            </div>
        </div>
        @endif

        @if ($details->status === \App\Enums\RentalTransactionStatus::PAYMENT_FAILED)
            <div class="card status-card p-3 d-flex flex-column gap-3 {{ $statusInfo['class'] }}">
                <div class="d-flex flex-row align-items-center gap-3">
                    <div class="flex-shrink-0"><i class="bi {{ $statusInfo['icon'] }} fs-4"></i></div>
                    <div class="flex-grow-1 d-flex flex-column gap-1">
                        <p class="fw-semibold mb-0">{{ $statusInfo['text'] }}</p>
                        <p class="mb-0 small">{!! $additionalMessage !!}</p>
                    </div>
                </div>
                <hr class="my-2 border-white opacity-25">
                <form action="{{ route('front.checkout.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3">
                    @csrf
                    <input type="hidden" name="product_slug" value="{{ $details->product->slug ?? '' }}">
                    <input type="hidden" name="duration" value="{{ $details->duration }}">
                    <input type="hidden" name="started_at" value="{{ $details->started_at->toDateString() }}">
                    <input type="hidden" name="ended_at" value="{{ $details->ended_at->toDateString() }}">
                    <input type="hidden" name="name" value="{{ $details->name }}">
                    <input type="hidden" name="phone_number" value="{{ $details->phone_number }}">
                    <input type="hidden" name="selected_size" value="{{ $details->selected_size }}">
                    <div class="d-flex flex-column gap-2">
                        <label for="payment_method" class="form-label mb-0">Choose Bank</label>
                        <select name="payment_method" id="payment_method" class="form-select" required>
                            <option value="">Choose Bank</option>
                            <option value="BCA">BCA</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label for="payment_proof" class="form-label mb-0">Upload Proof</label>
                        <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept=".jpg,.png" required>
                        <small class="form-text text-muted">Accepted formats: JPG, PNG</small>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm_payment" id="confirm_payment" required>
                        <label class="form-check-label" for="confirm_payment">
                            I confirm that I have transferred the payment
                        </label>
                    </div>
                    {{-- Same two confirmations as the main checkout form:
                         StorePaymentRequest now requires them server-side,
                         so the re-upload form must carry them too. --}}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm_penalty" id="confirm_penalty" required>
                        <label class="form-check-label" for="confirm_penalty">
                            I understand that if I do not return the item(s) by <b>{{ $details->ended_at->format('d M Y') }}</b>, I will be charged a penalty of <b>Rp {{ number_format($details->total_amount * 0.2, 0, ',', '.') }}</b> x number of late days.
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm_identity" id="confirm_identity" required>
                        <label class="form-check-label" for="confirm_identity">
                            I understand that when picking up the item, I must leave one of my ID cards (KTP or other valid identification) as a guarantee.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-light w-100">Re-upload & Resubmit Payment</button>
                </form>
                {{-- The cancel form must NOT be nested inside the re-upload
                     form above: nested forms are invalid HTML and browsers
                     drop the inner one, so "Cancel Order" submitted the
                     re-upload form instead of cancelling. --}}
                <form action="{{ route('front.rental.cancel', $details->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-outline-light w-100">Cancel Order</button>
                </form>
            </div>
        @endif

        <div class="card p-3">
            <div class="d-flex align-items-center gap-3">
                @if ($details->product)
                <div class="flex-shrink-0 rounded-2 overflow-hidden" style="width: 80px; height: 80px;">
                    <img src="{{ Storage::url($details->product->thumbnail) }}" class="img-fluid rounded-2" alt="thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endif
                <div class="flex-grow-1">
                    <p class="fw-bold mb-0">{{ $details->product->name ?? 'Product unavailable' }}</p>
                    <span class="badge bg-secondary mt-2">Selected size: {{ $details->selected_size }}</span>
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

    </section>

    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="https://wa.me/6285183004324" class="btn btn-primary w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> Contact Customer Service</a>
            </div>
        </div>
    </div>
</main>
@endsection