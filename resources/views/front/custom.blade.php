@extends('front.layouts.app')
@section('title', 'Custom Kebaya Order')
@section('content')
<main class="main-content-container py-4">
<div class="d-flex justify-content-between align-items-center mb-4 px-3">
<a href="{{ route('front.index') }}">
<i class="bi bi-arrow-left fs-4"></i>
</a>
<p class="h5 mb-0 fw-semibold">Custom Kebaya Order</p>
{{-- Dark/Light Mode Toggle Button --}}
<button id="theme-toggle" class="btn p-0">
<i class="bi bi-moon fs-4"></i>
</button>
</div>
<section class="d-flex flex-column gap-4 mt-4 px-3">
<div class="d-flex flex-column gap-2 align-items-center text-center mb-4">
<h1 class="h4 fw-bold">Order Your Custom Kebaya</h1>
<p class="text-muted mb-0">Fill out the form below to order a custom kebaya.</p>
</div>
<form action="{{ route('front.custom.order.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 d-flex flex-column gap-4">
@csrf
<div class="d-flex flex-column gap-2">
<label for="full_name" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-person"></i> Full Name</label>
<input type="text" name="full_name" id="full_name" class="form-control" placeholder="Enter your full name" value="{{ old('full_name', Auth::user()->name ?? '') }}" required>
</div>
<div class="d-flex flex-column gap-2">
<label for="phone_number" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-telephone"></i> Phone Number</label>
<input type="tel" name="phone_number"1 id="phone_number" class="form-control" placeholder="Enter your phone number" value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" required>
</div>
<div class="d-flex flex-column gap-2">
<label class="form-label fw-semibold mb-0 d-flex align-items-center gap-2">
<i class="bi bi-image"></i> Kebaya Image Reference
</label>
<input type="file" name="image_reference_1" class="form-control" accept=".jpg,.png" required>
<input type="file" name="image_reference_2" class="form-control" accept=".jpg,.png">
<input type="file" name="image_reference_3" class="form-control" accept=".jpg,.png">
</div>
<div class="d-flex flex-column gap-2">
<label for="material" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-palette"></i> Material</label>
<input type="text" name="material" id="material" class="form-control" placeholder="e.g., Silk, Batik, Brocade" required>
</div>
<div class="d-flex flex-column gap-2">
<label for="color" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-droplet"></i> Color</label>
<input type="text" name="color" id="color" class="form-control" placeholder="e.g., Pastel Blue, Maroon, Gold" required>
</div>
<div class="d-flex flex-column gap-2">
<label for="size_chart_option" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-rulers"></i> Choose Size</label>
<select name="size_chart_option" id="size_chart_option" class="form-select" required>2
<option value="">Select Size</option>
<option value="S">S</option>
<option value="M">M</option>
<option value="L">L</option>
<option value="XL">XL</option>
<option value="custom">Custom Size</option>
</select>
</div>
<div class="card p-3 d-block" id="sizeChartTable">
<p class="fw-semibold mb-2">Standard Size Chart</p>
<div class="table-responsive">
<table class="table table-bordered text-center">
<thead>
<tr>
<th>Size</th>
<th>Lebar Bahu Belakang</th>
<th>Lingkar Panggul</th>
<th>Lingkar Pinggul</th>
<th>Lingkar Dada</th>
<th>Kerung Lengan</th>
</tr>
</thead>
<tbody>
<tr>
<th>S</th>
<td>36 cm</td>
<td>88 cm</td>
<td>66 cm</td>
<td>86 cm</td>
<td>42 cm</td>
</tr>
<tr>
<th>M</th>
<td>38 cm</td>
<td>96 cm</td>
<td>72 cm</td>
<td>92 cm</td>
<td>44 cm</td>
</tr>
<tr>
<th>L</th>
<td>39 cm</td>
<td>108 cm</td>
<td>78 cm</td>
<td>98 cm</td>
<td>48 cm</td>
</tr>
<tr>
<th>XL</th>
<td>40 cm</td>
<td>112 cm</td>
<td>84 cm</td>
<td>104 cm</td>
<td>50 cm</td>
</tr>
</tbody>
</table>
</div>
<small class="text-muted">Please describe your chosen size (e.g., "Size M") in the preference description field, or "I am using a custom size" if you select Custom Size.</small>
</div>
<div class="d-flex flex-column gap-2 d-none" id="customSizeInputs">
<p class="fw-semibold mb-2 d-flex align-items-center gap-2"><i class="bi bi-pencil-square"></i> Enter Custom Measurements</p>
<div class="d-flex flex-column gap-2">
<label for="lebar_bahu_belakang" class="form-label mb-0">Lebar Bahu Belakang (cm)</label>
<input type="number" name="lebar_bahu_belakang" id="lebar_bahu_belakang" class="form-control" placeholder="e.g., 38" min="0" step="any">
</div>
<div class="d-flex flex-column gap-2">
<label for="lingkar_panggul" class="form-label mb-0">Lingkar Panggul (cm)</label>
<input type="number" name="lingkar_panggul" id="lingkar_panggul" class="form-control" placeholder="e.g., 90" min="0" step="any">
</div>
<div class="d-flex flex-column gap-2">
<label for="lingkar_pinggul" class="form-label mb-0">Lingkar Pinggul (cm)</label>
<input type="number" name="lingkar_pinggul" id="lingkar_pinggul" class="form-control" placeholder="e.g., 70" min="0" step="any">
</div>
<div class="d-flex flex-column gap-2">
<label for="lingkar_dada" class="form-label mb-0">Lingkar Dada (cm)</label>
<input type="number" name="lingkar_dada" id="lingkar_dada" class="form-control" placeholder="e.g., 88" min="0" step="any">
</div>
<div class="d-flex flex-column gap-2">
<label for="kerung_lengan" class="form-label mb-0">Kerung Lengan (cm)</label>
<input type="number" name="kerung_lengan" id="kerung_lengan" class="form-control" placeholder="e.g., 45" min="0" step="any">
</div>
</div>
<div class="d-flex flex-column gap-2">
<label for="kebaya_preference" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-file-text"></i> Describe Your Kebaya Preference</label>
<textarea name="kebaya_preference" id="kebaya_preference" class="form-control" rows="4" placeholder="Describe your desired kebaya, including materials, specific details, design elements, and how your chosen size or custom measurements should be applied (e.g., 'Size M, long sleeve', or 'Custom size: Lebar Bahu Belakang 38cm, etc.')." required>{{ old('kebaya_preference') }}</textarea>
</div>
<div class="d-flex flex-column gap-2">
<label for="amount_to_buy" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-cart"></i> Quantity to Buy (max 15)</label>
<input type="number" name="amount_to_buy" id="amount_to_buy" class="form-control" placeholder="Enter quantity to buy" min="1" max="15" required>
</div>
<div class="d-flex flex-column gap-2">
<label for="date_needed" class="form-label fw-semibold mb-0 d-flex align-items-center gap-2"><i class="bi bi-calendar-event"></i> Date Kebaya Needed</label>
<input type="date" name="date_needed" id="date_needed" class="form-control" required>
</div>
<button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-check-circle"></i> Submit Custom Order</button>
</form>
</section>
@push('after-scripts')
<script src="{{ asset('customjs/custom.js') }}"></script>
@endpush
<div class="mb-5"></div>
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
<a class="nav-link text-center text-dark" href="{{ route('front.custom') }}">
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
