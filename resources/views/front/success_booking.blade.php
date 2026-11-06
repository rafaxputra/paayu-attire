@extends('front.layouts.app')
@section('title', 'Booking Success')
@section('content')
<main class="main-content-container py-4">
    <div class="vh-center-flex">
        <section id="finishBook" class="d-flex flex-column gap-4 align-items-center w-100">

            <div class="card p-4 p-md-5 w-100 text-center position-relative">
                
                <button type="button" id="theme-toggle" class="btn p-0 position-absolute" style="top: 1.25rem; right: 1.25rem;">
                    <i class="bi bi-moon fs-4"></i>
                </button>
                
                <div class="d-flex flex-column align-items-center gap-2 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));">
                        <i class="bi bi-check-lg" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <h1 class="h4 fw-bold mb-0 mt-3">Booking Successful!</h1>
                    <p class="mb-0 mt-1">Thank you for your order. Please save your Booking ID.</p>
                </div>

                <hr class="hr-dotted my-3">

                <div class="d-flex flex-column gap-4">
                    <div class="text-center">
                        <p class="small text-muted mb-2">You have booked:</p>
                        @if ($transaction->product)
                        <img src="{{ Storage::url($transaction->product->thumbnail) }}" alt="Thumbnail" class="img-fluid rounded-3 mx-auto" style="max-height: 150px; object-fit: contain;" />
                        @endif
                        <p class="fw-bold fs-5 mt-2 mb-0">{{ $transaction->product->name ?? 'Product unavailable' }}</p>
                    </div>

                    <div class="card p-3">
                         <p class="fw-semibold mb-1 small text-muted">Your Booking ID</p>
                        <div class="d-flex align-items-center gap-2 justify-content-center">
                            <p id="bookingId" class="fw-bold fs-4 mb-0" style="letter-spacing: 2px;">{{ $transaction->trx_id }}</p>
                            <button id="copyBtn" class="btn btn-primary btn-sm" onclick="copyBookingId()">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <hr class="hr-dotted my-4">

                <div class="w-100 d-grid gap-2">
                    <a href="{{ route('front.transactions.details', ['trx_id' => $transaction->trx_id, 'phone_number' => $transaction->phone_number]) }}" class="btn btn-primary">
                        View Booking Details
                    </a>
                    <a href="{{ route('front.index') }}" class="btn btn-outline-secondary rounded-pill">
                        Back to Home
                    </a>
                </div>
            </div>
        </section>
    </div>
    
    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <ul class="nav justify-content-around py-3">
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.index') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-house-door bottom-nav-icon"></i><p class="mb-0 small">Browse</p></div></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center active" href="{{ route('front.transactions') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-receipt bottom-nav-icon"></i><p class="mb-0 small">Orders</p></div></a>
                </li>
                <li class="nav-item">
                     <a class="nav-link text-center" href="{{ route('front.custom') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-pencil-square bottom-nav-icon"></i><p class="mb-0 small">Custom</p></div></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.contact') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-person bottom-nav-icon"></i><p class="mb-0 small">Contact</p></div></a>
                </li>
            </ul>
        </div>
    </div>
</main>
@endsection

@push('after-scripts')
<script>
function copyBookingId() {
    const bookingId = document.getElementById('bookingId').innerText;
    const copyBtn = document.getElementById('copyBtn');
    const originalIcon = copyBtn.innerHTML;

    function showCopied() {
        copyBtn.innerHTML = '<i class="bi bi-check-lg"></i>';
        copyBtn.disabled = true;
        setTimeout(() => {
            copyBtn.innerHTML = originalIcon;
            copyBtn.disabled = false;
        }, 2000);
    }

    function showFailed() {
        // navigator.clipboard needs a secure context (https); on plain
        // http (localhost) it is undefined and the old code failed
        // silently. Show the failure on the button instead.
        copyBtn.innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
        copyBtn.title = 'Automatic copy failed — please copy it manually from the text above.';
        setTimeout(() => {
            copyBtn.innerHTML = originalIcon;
            copyBtn.removeAttribute('title');
        }, 3000);
    }

    function copyFallback(text) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        try {
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            ok ? showCopied() : showFailed();
        } catch (e) {
            document.body.removeChild(ta);
            showFailed();
        }
    }

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(bookingId).then(showCopied).catch(() => copyFallback(bookingId));
    } else {
        copyFallback(bookingId);
    }
}
</script>
@endpush