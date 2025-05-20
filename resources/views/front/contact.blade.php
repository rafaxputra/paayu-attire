@extends('front.layouts.app')
@section('title', 'Contact Us')

@section('content')
    <main class="main-content-container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 px-3">
            <a href="{{ route('front.index') }}">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <p class="h5 mb-0 fw-semibold">Contact Us</p>
            {{-- Dark/Light Mode Toggle Button --}}
            <button id="theme-toggle" class="btn p-0"> {{-- Added ID --}}
                <i class="bi bi-moon fs-4"></i> {{-- Initial icon (moon for light mode) --}}
            </button>
        </div>
        <section class="d-flex flex-column gap-4 mt-4 px-3">
            <div class="d-flex flex-column gap-2 align-items-center text-center">
                <h1 class="h4 fw-bold">Get in Touch</h1>
                <p class="text-muted">We'd love to hear from you!</p>
            </div>

            <div class="card p-4 d-flex flex-column gap-3"> {{-- Added flex and gap to card body --}}
                <h2 class="h6 mb-2 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-info-circle"></i> Our Information</h2> {{-- Adjusted margin-bottom --}}
                <div class="d-flex flex-column gap-1"> {{-- Adjusted gap --}}
                    <p class="fw-semibold mb-1 d-flex align-items-center gap-2"><i class="bi bi-geo-alt"></i> Address:</p> {{-- Adjusted margin-bottom --}}
                    <p class="mb-0 text-muted">Jl. Puspowarno Ds. Tales Dsn. Cakruk Kec. Ngadiluwih Kab. Kediri</p> {{-- Added text-muted --}}
                </div>
                <div class="d-flex flex-column gap-1"> {{-- Adjusted gap --}}
                    <p class="fw-semibold mb-1 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone:</p> {{-- Adjusted margin-bottom --}}
                    <p class="mb-0 text-muted">+62851 8300 4324‬</p> {{-- Added text-muted --}}
                </div>
                <div class="d-flex flex-column gap-1"> {{-- Adjusted gap --}}
                    <p class="fw-semibold mb-1 d-flex align-items-center gap-2"><i class="bi bi-instagram"></i> Instagram:</p> {{-- Adjusted margin-bottom --}}
                    <p class="mb-0 text-muted">@paayuattire</p> {{-- Added text-muted --}}
                </div>
                <div class="d-flex flex-column gap-1"> {{-- Adjusted gap --}}
                    <p class="fw-semibold mb-1 d-flex align-items-center gap-2"><i class="bi bi-envelope"></i> Email:</p> {{-- Adjusted margin-bottom --}}
                    <p class="mb-0 text-muted">paayuattire@gmail.com</p> {{-- Added text-muted --}}
                </div>
                <div class="d-flex flex-column gap-1"> {{-- Adjusted gap --}}
                    <p class="fw-semibold mb-1 d-flex align-items-center gap-2"><i class="bi bi-tiktok"></i> TikTok:</p> {{-- Adjusted margin-bottom --}}
                    <p class="mb-0 text-muted">@paayuattire</p> {{-- Added text-muted --}}
                </div>
            </div>

            <div class="card p-4">
                <h2 class="h6 mb-3 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-geo-alt-fill"></i> Our Location</h2>
                <div class="w-100 ratio ratio-16x9"> {{-- Removed bg-light, d-flex, align-items-center, justify-content-center, text-muted --}}
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d246.98515492578906!2d111.9803414012407!3d-7.919860659310218!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78f9001c955deb%3A0xeeb357c589d4cdbf!2sPaayu%20Attire!5e0!3m2!1sen!2sid!4v1747732804837!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </section>

        <div class="p-3">
            <a href="https://wa.me/6285183004324?text={{ urlencode('Hello, I would like to inquire about Paayu Attire services.') }}" class="btn btn-success rounded-pill px-4 py-2 fw-bold w-100 text-center d-flex align-items-center justify-content-center gap-2"><i class="bi bi-whatsapp"></i> WhatsApp Consultation</a>
        </div>

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
                    <a class="nav-link text-center text-muted" href="{{ route('front.custom') }}">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-pencil-square bottom-nav-icon"></i>
                            <p class="mb-0" style="font-size: 0.8rem;">Custom</p>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center text-dark" href="{{ route('front.contact') }}">
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
