@extends('front.layouts.app')
@section('title', 'Contact Us')

@section('content')
<main class="main-content-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.index') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Contact Us</p>
        <button id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>

    <section class="d-flex flex-column gap-5 mt-4 px-3">
        <div class="text-center">
            <h1 class="h4 fw-bold">Get in Touch</h1>
            <p class="text-muted">We'd love to hear from you!</p>
        </div>

        <div class="contact-info-grid">
            <a href="https://wa.me/6285183004324" class="contact-card">
                <i class="bi bi-whatsapp"></i>
                <p class="fw-semibold mb-1">WhatsApp</p>
                <p class="mb-0 text-muted small">+62851 8300 4324</p>
            </a>
            <a href="https://instagram.com/paayuattire" class="contact-card">
                <i class="bi bi-instagram"></i>
                <p class="fw-semibold mb-1">Instagram</p>
                <p class="mb-0 text-muted small">@paayuattire</p>
            </a>
             <a href="mailto:paayuattire@gmail.com" class="contact-card">
                <i class="bi bi-envelope"></i>
                <p class="fw-semibold mb-1">Email</p>
                <p class="mb-0 text-muted small">paayuattire@gmail.com</p>
            </a>
             <a href="https://maps.app.goo.gl/bbh5otF1BKEnrJr56" class="contact-card">
                <i class="bi bi-geo-alt-fill"></i>
                <p class="fw-semibold mb-1">Address</p>
                <p class="mb-0 text-muted small">Jl. Puspowarno, Ngadiluwih, Kediri</p>
            </a>
        </div>

        <div class="card p-4">
            <h2 class="h6 mb-3 fw-semibold d-flex align-items-center gap-2"><i class="bi bi-geo-alt-fill"></i> Our Location</h2>
            <div class="w-100 ratio ratio-16x9" style="border-radius: 12px; overflow: hidden;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d246.98515492578906!2d111.9803414012407!3d-7.919860659310218!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78f9001c955deb%3A0xeeb357c589d4cdbf!2sPaayu%20Attire!5e0!3m2!1sen!2sid!4v1747732804837!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="card p-4">
            <h2 class="h6 mb-3 fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-chat-dots"></i> User Comments
            </h2>

            @if(Auth::check())
                <form action="{{ route('front.contact.comment') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3">
                    @csrf
                    <textarea name="comment" class="form-control" placeholder="Write your comment..." required rows="3"></textarea>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <button type="submit" class="btn btn-primary align-self-end">
                        <i class="bi bi-send"></i> Submit
                    </button>
                </form>
            @else
                <p class="text-muted">You must <a href="{{ route('login') }}">login</a> to post a comment.</p>
            @endif

            <hr class="my-4">

            <div class="d-flex flex-column gap-3">
                @forelse ($comments as $comment)
                    <div class="card p-3">
                        <p class="fw-bold mb-1">{{ $comment->name }}</p>
                        <p class="mb-2 text-muted">{{ $comment->comment }}</p>
                        @if ($comment->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $comment->image) }}" alt="Comment Image" class="img-fluid rounded" style="max-width: 150px;">
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    @include('front.components.bottom_navbar')
</main>
@endsection