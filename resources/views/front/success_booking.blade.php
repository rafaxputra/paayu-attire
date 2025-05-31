@extends('front.layouts.app')
@section('title', 'Booking Success')

@section('content')
    <main class="main-content-container py-4 text-center">
        <section id="finishBook" class="d-flex flex-column gap-4 align-items-center pt-4 px-3"> {{-- Added px-3 --}}
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                <i class="bi bi-check-circle-fill text-white fs-1"></i>
            </div>
            <div class="d-flex flex-column gap-2 align-items-center">
                <h1 class="h4 fw-bold">Finish Booking</h1>
                <p class="text-muted text-center mb-0">Kami akan segera menghubungi anda untuk proses pemberian barang</p>
            </div>
            <div class="w-100 d-flex align-items-center justify-content-center flex-shrink-0"> {{-- Removed fixed height --}}
                <img src="{{ Storage::url($transaction->product->thumbnail) }}" alt="Thumbnail" class="img-fluid object-fit-contain" style="max-height: 300px;" /> {{-- Added max-height for better control --}}
            </div>
            <div class="card p-4 d-flex flex-column gap-2 w-100">
                <p class="fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-card-heading"></i> Your Booking ID</p>
                <div class="d-flex align-items-center gap-3 justify-content-center"> {{-- Centered content --}}
                    <p id="bookingId" class="fw-semibold fs-5 mb-0">{{ $transaction->trx_id }}</p> {{-- Added ID for copying --}}
                    <button class="btn btn-sm btn-outline-secondary" onclick="copyBookingId()">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                </div>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Protect your booking ID</p>
            </div>
            <div class="w-100 d-flex flex-column gap-3 align-items-center" style="max-width: 220px;">
                <a href="{{ route('front.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-cart-plus"></i> Rent More</a>
                <a href="{{ route('front.transactions.details', ['trx_id' => $transaction->trx_id, 'phone_number' => $transaction->phone_number]) }}"
                    class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-receipt"></i> Booking Details</a>
            </div>
        </section>
    </main>
@endsection

@push('after-scripts')
    <script>
        function copyBookingId() {
            const bookingId = document.getElementById('bookingId').innerText;
            navigator.clipboard.writeText(bookingId).then(() => {
                alert('Booking ID copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy Booking ID: ', err);
            });
        }
    </script>
@endpush
