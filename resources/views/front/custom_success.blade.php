@extends('front.layouts.app')
@section('title', 'Custom Order Success')

@section('content')
<main class="main-content-container py-4 text-center">
<section id="SuccessOrder" class="d-flex flex-column gap-4 align-items-center pt-4 px-3">
<div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
<i class="bi bi-check-circle-fill text-white fs-1"></i>
</div>
<div class="card p-4 d-flex flex-column gap-2 w-100">
<div class="d-flex flex-column gap-2 align-items-center">
<h1 class="h4 fw-bold">Custom Order Submitted!</h1>
<p class="text-muted mb-0">Your custom kebaya order has been successfully submitted. Please save your Order ID:</p>
<div class="d-flex align-items-center gap-3 justify-content-center">
<p id="orderId" class="fs-5 fw-bold text-body mb-0">{{ $transaction->trx_id }}</p>
<button class="btn btn-sm btn-outline-secondary rounded-pill" onclick="copyOrderId()">
<i class="bi bi-clipboard"></i> Copy
</button>
</div>
</div>
</div>

        <a href="{{ route('front.custom.details', $transaction->trx_id) }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-file-earmark-text"></i> View Order Details</a>

        <a href="{{ route('front.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-house-door"></i> Back to Browse</a>

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
                    <a class="nav-link text-center text-muted" href="{{ route('front.transactions') }}">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-receipt bottom-nav-icon"></i>
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
                        <a class="nav-link text-center text-dark" href="{{ route('front.custom') }}">
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

@push('after-scripts')
<script>
function copyOrderId() {
const orderId = document.getElementById('orderId').innerText;
navigator.clipboard.writeText(orderId).then(() => {
alert('Order ID copied to clipboard!');
}).catch(err => {
console.error('Failed to copy Order ID: ', err);
});
}
</script>
@endpush
