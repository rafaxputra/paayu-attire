@extends('front.layouts.app')
@section('title', 'Customer Dashboard')

@section('content')
<main class="main-content-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.index') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">My Dashboard</p>
        <button id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>
    
    <section class="d-flex flex-column gap-4 mt-4">
        <div class="text-center">
            <h1 class="h4 fw-bold mb-2">Hi, {{ Auth::user()->name }}! 👋</h1>
            <div class="mt-2">
                <span class="text-muted">If you want to log out, click </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">@csrf <button type="submit" class="btn btn-link p-0 m-0 align-baseline" style="vertical-align: baseline;">here</button></form>.
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card p-4">
            <h2 class="h6 mb-3 fw-semibold">Account Management</h2>
            <div class="d-grid gap-3">
                <a href="{{ route('front.index') }}" class="btn btn-primary"><i class="bi bi-house-door"></i> Home</a>
                <a href="{{ route('front.customer.editProfile') }}" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-pencil-square"></i> Edit Profile</a>
                
                @if (Auth::user()->google_id)
                    <button type="button" class="btn btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#unlinkGoogleModal">
                        <i class="bi bi-google"></i> Unlink Google Account
                    </button>
                @else
                    <a href="{{ route('front.auth.google') }}" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-google"></i> Link to Google Account</a>
                @endif

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="bi bi-trash"></i> Delete Account
                </button>
            </div>
        </div>

        <div class="btn-group w-100" role="group" aria-label="History Toggle">
            <button type="button" class="btn btn-primary active" id="showRentalHistory">Rental History</button>
            <button type="button" class="btn btn-outline-secondary" id="showCustomHistory">Custom Order History</button>
        </div>

        <div id="rentalHistory" class="d-flex flex-column gap-3">
            @forelse ($rentalTransactions as $transaction)
                <div class="card p-3 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="fw-bold">{{ $transaction->trx_id }}</span>
                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis">{{ $transaction->status->getLabel() }}</span>
                    </div>
                    <hr class="my-1">
                    <p class="mb-0 small text-muted">Product: {{ $transaction->product->name }}</p>
                    <a href="{{ route('front.transactions.details', ['trx_id' => $transaction->trx_id, 'phone_number' => $transaction->phone_number]) }}" class="btn btn-sm btn-outline-secondary mt-2 align-self-end">View Details</a>
                </div>
            @empty
                <p class="text-muted text-center card p-3">No rental transactions found.</p>
            @endforelse
        </div>

        <div id="customHistory" class="d-flex flex-column gap-3 d-none">
            @forelse ($customTransactions as $transaction)
                 <div class="card p-3 d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="fw-bold">{{ $transaction->trx_id }}</span>
                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis">{{ $transaction->status->getLabel() }}</span>
                    </div>
                    <hr class="my-1">
                    <p class="mb-0 small text-muted">Preference: {{ Str::limit($transaction->kebaya_preference, 50) }}</p>
                    <a href="{{ route('front.custom.details', $transaction->trx_id) }}" class="btn btn-sm btn-outline-secondary mt-2 align-self-end">View Details</a>
                </div>
            @empty
                <p class="text-muted text-center card p-3">No custom orders found.</p>
            @endforelse
        </div>

    </section>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Confirm Account Deletion</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p>Are you sure you want to delete your account? This action cannot be undone.</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><form action="{{ route('front.customer.deleteAccount') }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Delete Account</button></form></div></div></div></div>
    <div class="modal fade" id="unlinkGoogleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Google Unlink</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to unlink your Google account? You will need to set a password to log in afterwards.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('front.customer.unlinkGoogle') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes, Unlink</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection

@push('after-scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rentalBtn = document.getElementById('showRentalHistory');
    const customBtn = document.getElementById('showCustomHistory');
    const rentalHistory = document.getElementById('rentalHistory');
    const customHistory = document.getElementById('customHistory');

    rentalBtn.addEventListener('click', function() {
        rentalHistory.classList.remove('d-none');
        customHistory.classList.add('d-none');
        rentalBtn.classList.add('btn-primary');
        rentalBtn.classList.remove('btn-outline-secondary');
        customBtn.classList.add('btn-outline-secondary');
        customBtn.classList.remove('btn-primary');
    });

    customBtn.addEventListener('click', function() {
        customHistory.classList.remove('d-none');
        rentalHistory.classList.add('d-none');
        customBtn.classList.add('btn-primary');
        customBtn.classList.remove('btn-outline-secondary');
        rentalBtn.classList.add('btn-outline-secondary');
        rentalBtn.classList.remove('btn-primary');
    });
});
</script>
@endpush