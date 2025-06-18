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

    @if ($errors->has('error'))
    <div id="not-found-popup" class="popup d-flex align-items-center justify-content-center" style="display:block;">
        <div class="popup-content" style="min-width:280px;max-width:90vw;">
            <div class="d-flex flex-column align-items-center">
                <i class="bi bi-exclamation-circle text-warning mb-2" style="font-size:2.5rem;"></i>
                <p class="mb-2" style="font-weight:600;">{{ $errors->first('error') }}</p>
                <button id="close-popup-btn" class="btn btn-primary mt-2">Tutup</button>
            </div>
        </div>
    </div>
    @endif
    @include('front.components.bottom_navbar')
    <script src="{{ asset('customjs/popup.js') }}"></script>
    <style>
    .popup { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; z-index: 200; }
    .popup-content { background: var(--card-bg); padding: 24px 32px; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.15); text-align: center; color: var(--text-color); }
    </style>
</main>
@endsection