@extends('front.layouts.app')
@section('title', 'Custom Order Details')

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.transactions') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Custom Order Details</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0"> {{-- Added ID --}}
                <i class="bi bi-moon fs-4"></i> {{-- Initial icon (moon for light mode) --}}
            </button>
        </div>
        <section class="d-flex flex-column gap-4 mt-4 px-3">
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
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">Your Custom Order ID</p>
                </div>
            </div>

            <div class="card p-3 d-flex flex-row align-items-center gap-3
                @if($details->status == 'pending') bg-warning text-dark
                @elseif($details->status == 'accepted') bg-success text-white
                @elseif($details->status == 'rejected') bg-danger text-white
                @else bg-secondary text-white @endif">
                <div class="flex-shrink-0">
                    @if($details->status == 'pending')
                        <i class="bi bi-clock fs-4"></i>
                    @elseif($details->status == 'accepted')
                         <i class="bi bi-check-circle fs-4"></i>
                    @elseif($details->status == 'rejected')
                         <i class="bi bi-x-circle fs-4"></i>
                    @else
                         <i class="bi bi-info-circle fs-4"></i>
                    @endif
                </div>
                <div class="flex-grow-1 d-flex flex-column gap-1">
                    <p class="fw-semibold mb-0" style="font-size: 0.9rem;">Status: {{ $details->status->getLabel() }}</p>
                    <p class="mb-0" style="font-size: 0.8rem;">
                        @if($details->status->value == 'pending')
                            Your custom order request is pending review by our team.
                        @elseif($details->status->value == 'accepted')
                            Your custom order has been accepted. Please review the details below.
                        @elseif($details->status->value == 'rejected')
                            Your custom order has been rejected. Please contact customer service for more information.
                        @elseif($details->status->value == 'in_progress')
                            Your custom order is currently in progress.
                        @elseif($details->status->value == 'completed')
                            Your custom order is completed and ready for pickup/delivery.
                        @elseif($details->status->value == 'cancelled')
                            Your custom order has been cancelled.
                        @elseif($details->status->value == 'pending_payment')
                            Your custom order is pending payment. Please complete the payment below.
                        @elseif($details->status->value == 'pending_payment_verification')
                            Your payment proof has been uploaded. Waiting for admin verification.
                        @endif
                    </p>
                </div>
            </div>

            <section class="d-flex flex-column gap-4">
                <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-person-circle"></i> Customer Information</h2> {{-- Added icon --}}
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
                     @if($details->delivery_type == 'delivery')
                        <div class="d-flex flex-column gap-2">
                            <p class="fw-semibold mb-0">Delivery Address</p>
                            <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                                <i class="bi bi-house-door fs-5"></i>
                                <p class="fw-semibold mb-0">{{ $details->address }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <section class="d-flex flex-column gap-4">
                <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-file-earmark-text"></i> Order Details</h2> {{-- Added icon --}}
                 @if($details->image_reference)
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-image"></i> Image Reference 1:
                        </p>
                        <img src="{{ Storage::url($details->image_reference) }}" alt="Kebaya Reference Image 1" class="img-fluid rounded-md">

                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2 mt-3">
                            <i class="bi bi-image"></i> Image Reference 2:
                        </p>
                        <img src="{{ Storage::url($details->image_reference_2) }}" alt="Kebaya Reference Image 2" class="img-fluid rounded-md">

                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2 mt-3">
                            <i class="bi bi-image"></i> Image Reference 3:
                        </p>
                        <img src="{{ Storage::url($details->image_reference_3) }}" alt="Kebaya Reference Image 3" class="img-fluid rounded-md">
                    </div>

                @endif
                <div class="d-flex flex-column gap-2">
                    <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-file-text"></i> Kebaya Preference:</p> {{-- Added icon --}}
                    <p class="mb-0">{{ $details->kebaya_preference }}</p>
                </div>
                 <div class="d-flex flex-column gap-2">
                    <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-cart"></i> Quantity to Buy:</p> {{-- Added icon --}}
                    <p class="mb-0">{{ $details->amount_to_buy }}</p>
                </div>
                 <div class="d-flex flex-column gap-2">
                    <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Date Kebaya Needed:</p> {{-- Added icon --}}
                    <p class="mb-0">{{ $details->date_needed->format('d m Y') }}</p>
                </div>
                </section>

            @if($details->status->value === 'accepted' || $details->status->value === 'in_progress' || $details->status->value === 'completed' || $details->status->value === 'pending_payment' || $details->status->value === 'pending_payment_verification')
                <section class="d-flex flex-column gap-4">
                    <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-chat-dots"></i> Admin Response</h2> {{-- Added icon --}}
                    @if($details->admin_price)
                        <div class="d-flex flex-column gap-2">
                            <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-currency-dollar"></i> Price:</p> {{-- Added icon --}}
                            <p class="fw-bold fs-5 mb-0">Rp {{ Number::format($details->admin_price, locale: 'id') }}</p>
                        </div>
                    @endif
                    @if($details->admin_estimated_completion_date)
                        <div class="d-flex flex-column gap-2">
                            <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-check"></i> Estimated Completion Date:</p> {{-- Added icon --}}
                            <p class="mb-0">{{ $details->admin_estimated_completion_date->format('d m Y') }}</p>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Show Delivery Info if status is completed --}}
            @if($details->status->value === 'completed')
                <section class="d-flex flex-column gap-4">
                    <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-truck"></i> Delivery Information</h2> {{-- Added icon --}}
                    @if($details->delivery_type === 'pickup')
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
                    @else {{-- delivery --}}
                         <div class="d-flex flex-column gap-2">
                            <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-house-door"></i> Delivery Address:</p> {{-- Added icon --}}
                            <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-2">
                                <i class="bi bi-house-door fs-5"></i>
                                <div class="d-flex flex-column gap-1">
                                    <p class="fw-semibold mb-0">{{ $details->address }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </section>
            @endif

            {{-- Show Approve/Cancel buttons if status is accepted and not paid --}}
            @if($details->status->value === 'accepted' && !$details->is_paid)
                <section class="d-flex flex-column gap-4">
                    <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-question-circle"></i> Action Required</h2> {{-- Added icon --}}
                    <p class="mb-0">Your custom order has been accepted with the details above. Please approve to proceed to payment or cancel the order.</p>

                    <form action="{{ route('front.custom.approve', $details->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-check-circle"></i> Approve Order and Proceed to Payment</button> {{-- Added icon and flex/gap --}}
                    </form>

                    <form action="{{ route('front.custom.cancel', $details->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-x-circle"></i> Cancel Order</button> {{-- Added icon and flex/gap --}}
                    </form>
                </section>
            @endif

            {{-- Show payment instructions and upload form if status is pending_payment and not paid --}}
            @if($details->status->value === 'pending_payment' && !$details->is_paid)
                <section class="d-flex flex-column gap-4">
                    <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-wallet"></i> Payment</h2> {{-- Added icon --}}
                    <p class="mb-0">Please make the payment of Rp {{ Number::format($details->admin_price, locale: 'id') }} to one of the following accounts:</p>
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-bank"></i> Bank BCA:</p> {{-- Added icon --}}
                        <p class="mb-0">5545011970 a/n Niken Alfinanda Putri</p>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-bank"></i> Bank BRI:</p> {{-- Added icon --}}
                        <p class="mb-0">626801015467534 a/n Niken Alfinanda Putri</p>
                    </div>

                    <form id="payment-upload-form" action="{{ route('front.custom.uploadPaymentProof', $details->id) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-4 mt-3">
                        @csrf
                        <div class="d-flex flex-column gap-2">
                            <label for="payment_method" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-cash-coin"></i> Payment Method</label> {{-- Added icon --}}
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">Select Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="BRI">BRI</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <label for="payment_proof" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-upload"></i> Upload Proof</label> {{-- Added icon --}}
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-upload"></i>
                                </span>
                                <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept=".jpg,.png" required>
                            </div>
                            <small class="form-text text-muted">Accepted formats: JPG, PNG</small>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirm_payment" name="confirm_payment" required>
                            <label class="form-check-label fw-semibold" for="confirm_payment">
                                Saya benar telah transfer pembayaran
                            </label>
                        </div>
                        <button type="submit" form="payment-upload-form" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-check-circle"></i> Confirm Payment</button> {{-- Moved button here --}}
                    </form>
                </section>
            @endif

            @if($details->payment_proof && $details->status->value !== 'pending_payment') {{-- Show uploaded proof if exists and not in pending_payment state --}}
                <div class="d-flex flex-column gap-2">
                    <h2 class="h5 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-image"></i> Uploaded Payment Proof</h2>
                    <img src="{{ Storage::url($details->payment_proof) }}" alt="Payment Proof" class="img-fluid rounded-md">
                </div>
            @endif

            @if($details->is_paid)
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
            @endif

            {{-- Keep Cancel button visible for pending status --}}
            @if($details->status->value === 'pending')
                <section class="d-flex flex-column gap-4">
                    <form action="{{ route('front.custom.cancel', $details->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-x-circle"></i> Cancel Order</button> {{-- Added icon and flex/gap --}}
                    </form>
                </section>
            @endif

            <div class="p-3">
                <a href="https://wa.me/6285183004324?text={{ urlencode('Hello, I would like to inquire about my custom order with ID: ' . $details->trx_id) }}" class="btn btn-success rounded-pill px-4 py-2 fw-bold w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> WhatsApp Consultation</a> {{-- Added icon and flex/gap --}}
            </div>

        </section>

        {{-- Removed Fixed bottom bar for payment confirmation --}}


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
