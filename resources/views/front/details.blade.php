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
        <button id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>

    <section id="Thumbnail" class="d-flex flex-column align-items-center justify-content-center position-relative card mx-3" style="height: 400px; padding-top: 0; padding-bottom: 66px;">
        <div class="w-100 h-100 d-flex align-items-center justify-content-center flex-shrink-0">
            <img id="mainThumbnail" src="{{ Storage::url($product->thumbnail) }}" alt="Thumbnail"
                 class="img-fluid object-fit-cover transition-opacity duration-500 ease-in-out h-100 rounded-3" />
        </div>
        <div class="d-flex gap-3 position-absolute" style="bottom: 30px; width: 100%; justify-content: center;">
            <button class="thumbnail-button btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0 thumbnail-active-ring" style="width: 70px; height: 70px;">
                <img src="{{ Storage::url($product->thumbnail) }}" alt="Thumbnail" class="rounded-circle object-fit-cover" style="width: 100%; height: 100%;" />
            </button>
            @forelse ($product->photos as $photo)
            <button class="thumbnail-button btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 70px; height: 70px;">
                <img src="{{ Storage::url($photo->photo) }}" alt="Thumbnail" class="img-fluid rounded-circle object-fit-cover" style="width: 100%; height: 100%;" />
            </button>
            @empty
            @endforelse
        </div>
    </section>

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

        @if($product->size_chart)
            <div id="Size-Chart" class="d-flex flex-column gap-2">
                <h2 class="h6 fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-list-ol"></i> Size Chart</h2>
                <div class="card p-3">
                    @php
                        $lines = explode("\n", trim($product->size_chart));
                        $header = explode('|', trim(array_shift($lines)));
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th class="align-middle">Size</th>
                                    @foreach($header as $h)
                                        <th scope="col" class="align-middle">{{ trim($h) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lines as $line)
                                    @php
                                        $parts = explode(':', trim($line), 2);
                                        $sizeLabel = trim($parts[0] ?? '');
                                        $measurements = explode('|', trim($parts[1] ?? ''));
                                    @endphp
                                    <tr>
                                        <td class="align-middle">{{ $sizeLabel }}</td>
                                        @foreach($measurements as $cell)
                                            <td class="align-middle">{{ trim($cell) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
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
                <a href="{{ route('front.booking', $product->slug) }}" class="btn btn-primary d-flex align-items-center gap-2"><i class="bi bi-cart-plus"></i> Rent Now</a>
            </div>
        </div>
    </div>
</main>
@endsection

@push('after-scripts')
<script src="{{ asset('customjs/details.js') }}"></script>
@endpush