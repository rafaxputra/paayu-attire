@extends('front.layouts.app')
@section('title', 'Product Details')

@push('after-styles')
@endpush

@section('content')
<main class="main-content-container pt-2 pb-4">
    <div id="Top-navbar" class="d-flex justify-content-between align-items-center mb-3 px-3">
        <a href="{{ route('front.index') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Details</p>
        <button type="button" id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>

    {{-- Gallery: main image full-width on top, thumbnails in a row below
         it. Previously the thumbnails overlapped the bottom of a fixed
         400px frame while the narrow portrait image sat in the middle
         with wide empty space on both sides. --}}
    <section id="Thumbnail" class="card mx-3 p-3">
        <div class="gallery-main">
            <img id="mainThumbnail" src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}"
                 class="transition-opacity duration-500 ease-in-out"
                 role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#imageLightbox"
                 aria-label="Enlarge image" style="cursor: zoom-in;"
                 fetchpriority="high" decoding="async" />
        </div>
        <div class="d-flex gap-3 justify-content-center mt-3">
            <button type="button" class="thumbnail-button btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0 thumbnail-active-ring" style="width: 70px; height: 70px;" aria-label="View main image">
                <img src="{{ Storage::url($product->thumbnail) }}" alt="Thumbnail" class="rounded-circle object-fit-cover" style="width: 100%; height: 100%;" loading="lazy" decoding="async" />
            </button>
            @forelse ($product->photos as $photo)
            <button type="button" class="thumbnail-button btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 70px; height: 70px;" aria-label="View image {{ $loop->iteration + 1 }}">
                <img src="{{ Storage::url($photo->photo) }}" alt="Thumbnail" class="img-fluid rounded-circle object-fit-cover" style="width: 100%; height: 100%;" loading="lazy" decoding="async" />
            </button>
            @empty
            @endforelse
        </div>
    </section>

    {{-- Lightbox premium: tap the main image to see it enlarged, browse
         with arrows / thumbnails, and zoom in. The previous version used
         a white modal with a white close button (btn-close-white), so the
         close button was invisible. --}}
    <div class="modal fade image-lightbox" id="imageLightbox" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="lightbox-header">
                    <div>
                        <p class="lightbox-title">{{ $product->name }}</p>
                        <p class="lightbox-counter"><span id="lightboxCounter">1</span> / {{ 1 + $product->photos->count() }}</p>
                    </div>
                    <button type="button" class="lightbox-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="lightbox-stage" id="lightboxStage">
                    <img id="lightboxImage" src="{{ Storage::url($product->thumbnail) }}" alt="{{ $product->name }}">
                    <button type="button" class="lightbox-nav lightbox-prev" id="lightboxPrev" aria-label="Previous image">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button type="button" class="lightbox-nav lightbox-next" id="lightboxNext" aria-label="Next image">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <div class="lightbox-footer">
                    <div class="lightbox-thumbs">
                        <img src="{{ Storage::url($product->thumbnail) }}" class="lightbox-thumb active" data-index="0" alt="Gambar utama">
                        @foreach ($product->photos as $photo)
                        <img src="{{ Storage::url($photo->photo) }}" class="lightbox-thumb" data-index="{{ $loop->iteration }}" alt="Gambar {{ $loop->iteration + 1 }}">
                        @endforeach
                    </div>
                    <div class="lightbox-zoom">
                        <button type="button" id="lightboxZoomOut" aria-label="Zoom out"><i class="bi bi-dash-lg"></i></button>
                        <button type="button" id="lightboxZoomIn" aria-label="Zoom in"><i class="bi bi-plus-lg"></i></button>
                        <button type="button" id="lightboxZoomReset" aria-label="Reset zoom"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="Details" class="d-flex flex-column mt-4 px-3 w-100 gap-3">
        <div id="Heading" class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column gap-1">
                <h1 class="h4 fw-bold mb-0">{{ $product->name }}</h1>
            </div>
        </div>
        <div id="About" class="d-flex flex-column gap-2">
            <h2 class="h6 fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-book"></i> About</h2>
            <div class="card p-3">
                <p class="mb-0">{!! $product->about !!}</p>
            </div>
        </div>
    </section>

    <section id="Product-Info" class="d-flex flex-column mt-4 px-3 w-100 gap-3">
        @if($product->material)
        <div id="Material" class="d-flex flex-column gap-2">
            <h2 class="h6 fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-gem"></i> Material</h2>
            <div class="card p-3">
                <p class="mb-0">{!! nl2br(e($product->material)) !!}</p>
            </div>
        </div>
        @endif

        @if($product->color)
            <div id="Color" class="d-flex flex-column gap-2">
                <h2 class="h6 fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-palette"></i> Color</h2>
                <div class="card p-3">
                    <p class="mb-0">{{ $product->color }}</p>
                </div>
            </div>
        @endif

        @if(true)
            <div id="Size-Chart" class="d-flex flex-column gap-2">
                <h2 class="h6 fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-list-ol"></i> Size Chart</h2>
                <div class="card p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th class="align-middle">Size</th>
                                    <th class="align-middle">Back Shoulder Width</th>
                                    <th class="align-middle">Hip Circumference</th>
                                    <th class="align-middle">Waist Circumference</th>
                                    <th class="align-middle">Bust Circumference</th>
                                    <th class="align-middle">Armhole Circumference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>S</td><td>36 cm</td><td>88 cm</td><td>66 cm</td><td>86 cm</td><td>42 cm</td></tr>
                                <tr><td>M</td><td>38 cm</td><td>96 cm</td><td>72 cm</td><td>92 cm</td><td>44 cm</td></tr>
                                <tr><td>L</td><td>39 cm</td><td>108 cm</td><td>78 cm</td><td>98 cm</td><td>48 cm</td></tr>
                                <tr><td>XL</td><td>40 cm</td><td>112 cm</td><td>84 cm</td><td>104 cm</td><td>50 cm</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div id="Sizes" class="d-flex flex-column gap-2">
            <h2 class="h6 fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-arrows-fullscreen"></i> Available Sizes</h2>
            <div class="card p-3">
                <div class="d-flex flex-wrap gap-3">
                    @forelse ($product->productSizes as $size)
                        <div class="d-flex align-items-center gap-2 p-2 border rounded-3">
                            <p class="mb-0 fw-semibold">{{ $size->size }}</p>
                            <p class="mb-0 text-muted" style="font-size: 0.9rem;">Stock: {{ $size->stock }}</p>
                        </div>
                    @empty
                        <p class="text-muted" style="font-size: 0.9rem;">No size information available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <div class="d-flex align-items-center justify-content-between p-3">
                <div class="d-flex flex-column gap-1">
                    <p class="fw-bold fs-5 mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="mb-0" style="font-size: 0.9rem;">/day</p>
                </div>
                @php
                    // A product with no sizes at all must not show Rent
                    // Now either: the booking page would have no valid
                    // size option and every submit would fail validation.
                    $noSizes = $product->productSizes->count() === 0;
                    $allOutOfStock = $noSizes || $product->productSizes->every(fn($size) => $size->stock == 0);
                @endphp
                @if($allOutOfStock)
                    <button class="btn btn-secondary d-flex align-items-center gap-2" disabled><i class="bi bi-cart-x"></i> {{ $noSizes ? 'Not available yet' : 'All stock is currently unavailable' }}</button>
                @else
                    <a href="{{ route('front.booking', $product->slug) }}" class="btn btn-primary d-flex align-items-center gap-2"><i class="bi bi-cart-plus"></i> Rent Now</a>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

@push('after-scripts')
<script src="/customjs/details.js"></script>
@endpush