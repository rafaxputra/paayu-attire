@extends('front.layouts.app')
@section('title', 'Customer Dashboard')

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.index') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Customer Dashboard</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0">
                <i class="bi bi-moon fs-4"></i>
            </button>
        </div>
        <section class="d-flex flex-column gap-4 mt-4 px-3">
            <div class="d-flex flex-column gap-2 align-items-center text-center mb-4">
                <h1 class="h4 fw-bold mb-2">Hi, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-muted mb-0">Welcome to your dashboard.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card p-4 d-flex flex-column gap-4">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-person-circle"></i> Profile Management</h2>
                <a href="{{ route('front.customer.editProfile') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-pencil-square"></i> Edit Profile
                </a>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-bold d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    <i class="bi bi-trash"></i> Delete Account
                </button>
            </div>

            <div class="card p-4 d-flex flex-column gap-4">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-receipt"></i> Rental Transaction History</h2>
                @forelse ($rentalTransactions as $transaction)
                    <div class="border p-3 rounded-3 d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0">{{ $transaction->trx_id }}</p>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Product: {{ $transaction->product->name }}</p>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Dates: {{ $transaction->started_at->format('d M Y') }} - {{ $transaction->ended_at->format('d M Y') }}</p>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Total: Rp {{ Number::format($transaction->total_amount, locale: 'id') }}</p>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Status: {{ $transaction->is_paid ? 'Paid' : 'Pending Payment' }}</p>
                        <a href="{{ route('front.transactions.details', ['trx_id' => $transaction->trx_id, 'phone_number' => $transaction->phone_number]) }}" class="btn btn-sm btn-outline-secondary mt-2">View Details</a>
                    </div>
                @empty
                    <p class="text-muted">No rental transactions found.</p>
                @endforelse
            </div>

            <div class="card p-4 d-flex flex-column gap-4">
                <h2 class="h6 mb-0 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-pencil-square"></i> Custom Order History</h2>
                 @forelse ($customTransactions as $transaction)
                    <div class="border p-3 rounded-3 d-flex flex-column gap-2">
                        <p class="fw-semibold mb-0">{{ $transaction->trx_id }}</p>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Preference: {{ Str::limit($transaction->kebaya_preference, 50) }}</p>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Date Needed: {{ $transaction->date_needed->format('d M Y') }}</p>
                         @if($transaction->admin_price)
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">Price: Rp {{ Number::format($transaction->admin_price, locale: 'id') }}</p>
                        @endif
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">Status: {{ $transaction->status->getLabel() }}</p>
                        <a href="{{ route('front.custom.details', $transaction->trx_id) }}" class="btn btn-sm btn-outline-secondary mt-2">View Details</a>
                    </div>
                @empty
                    <p class="text-muted">No custom orders found.</p>
                @endforelse
            </div>
        </section>

        <!-- Delete Account Modal -->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete your account? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('front.customer.deleteAccount') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5"></div> {{-- Added margin to push content above fixed navbar --}}

        {{-- Bottom Navigation --}}
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
        </div>
    </main>
@endsection
