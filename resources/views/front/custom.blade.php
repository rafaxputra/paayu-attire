@extends('front.layouts.app')
@section('title', 'Custom Kebaya Order')

@section('content')
<main class="main-content-container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <a href="{{ route('front.index') }}">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <p class="h5 mb-0 fw-semibold">Custom Kebaya Order</p>
        <button id="theme-toggle" class="btn p-0">
            <i class="bi bi-moon fs-4"></i>
        </button>
    </div>

    <section class="d-flex flex-column gap-4 mt-4 px-3">
        <div class="text-center">
            <h1 class="h4 fw-bold">Order Your Custom Kebaya</h1>
            <p class="text-muted mb-0">Fill out the form below to order a custom kebaya.</p>
        </div>

        <form action="{{ route('front.custom.order.store') }}" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-4">
            @csrf

            <div class="card p-4" data-section="1">
                <h2 class="h6 fw-semibold d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-person-badge"></i>
                    <span>Contact Information</span>
                    <i class="bi bi-check-circle-fill text-success ms-auto d-none" id="status-icon-1"></i>
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-column gap-2">
                        <label for="full_name" class="form-label mb-0">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control required-for-section-1" placeholder="Enter your full name" value="{{ old('full_name', Auth::user()->name ?? '') }}" required>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label for="phone_number" class="form-label mb-0">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" class="form-control required-for-section-1" placeholder="Enter your phone number" value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}" required>
                    </div>
                </div>
            </div>

            <div class="card p-4" data-section="2">
                 <h2 class="h6 fw-semibold d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-palette-fill"></i>
                    <span>Kebaya Design</span>
                    <i class="bi bi-check-circle-fill text-success ms-auto d-none" id="status-icon-2"></i>
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-column gap-2">
                        <label class="form-label mb-0">Kebaya Image References (required 1)</label>
                        <input type="file" name="image_reference_1" class="form-control required-for-section-2" accept=".jpg,.png" required>
                        <input type="file" name="image_reference_2" class="form-control" accept=".jpg,.png">
                        <input type="file" name="image_reference_3" class="form-control" accept=".jpg,.png">
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label for="material" class="form-label mb-0">Material</label>
                        <input type="text" name="material" id="material" class="form-control required-for-section-2" placeholder="e.g., Silk, Batik, Brocade" required>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label for="color" class="form-label mb-0">Color</label>
                        <input type="text" name="color" id="color" class="form-control required-for-section-2" placeholder="e.g., Pastel Blue, Maroon, Gold" required>
                    </div>
                </div>
            </div>

            <div class="card p-4" data-section="3">
                <h2 class="h6 fw-semibold d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-rulers"></i>
                    <span>Sizing</span>
                    <i class="bi bi-check-circle-fill text-success ms-auto d-none" id="status-icon-3"></i>
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-column gap-2">
                        <label for="size_chart_option" class="form-label mb-0">Choose Size</label>
                        <select name="size_chart_option" id="size_chart_option" class="form-select required-for-section-3" required>
                            <option value="">Select Size</option>
                            <option value="S">S</option> <option value="M">M</option> <option value="L">L</option> <option value="XL">XL</option> <option value="custom">Custom Size</option>
                        </select>
                    </div>
                    <div class="d-block" id="sizeChartTable">
                        <p class="fw-semibold mb-2">Standard Size Chart</p>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th class="align-middle">Size</th>
                                        <th class="align-middle">Lebar Bahu Belakang</th>
                                        <th class="align-middle">Lingkar Panggul</th>
                                        <th class="align-middle">Lingkar Pinggul</th>
                                        <th class="align-middle">Lingkar Dada</th>
                                        <th class="align-middle">Kerung Lengan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td class="align-middle">S</td><td>36 cm</td><td>88 cm</td><td>66 cm</td><td>86 cm</td><td>42 cm</td></tr>
                                    <tr><td class="align-middle">M</td><td>38 cm</td><td>96 cm</td><td>72 cm</td><td>92 cm</td><td>44 cm</td></tr>
                                    <tr><td class="align-middle">L</td><td>39 cm</td><td>108 cm</td><td>78 cm</td><td>98 cm</td><td>48 cm</td></tr>
                                    <tr><td class="align-middle">XL</td><td>40 cm</td><td>112 cm</td><td>84 cm</td><td>104 cm</td><td>50 cm</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 d-none" id="customSizeInputs">
                        <p class="fw-semibold mb-0">Enter Custom Measurements (cm)</p>
                        <input type="number" name="lebar_bahu_belakang" class="form-control" placeholder="Lebar Bahu Belakang" min="0" step="any">
                        <input type="number" name="lingkar_panggul" class="form-control" placeholder="Lingkar Panggul" min="0" step="any">
                        <input type="number" name="lingkar_pinggul" class="form-control" placeholder="Lingkar Pinggul" min="0" step="any">
                        <input type="number" name="lingkar_dada" class="form-control" placeholder="Lingkar Dada" min="0" step="any">
                        <input type="number" name="kerung_lengan" class="form-control" placeholder="Kerung Lengan" min="0" step="any">
                    </div>
                </div>
            </div>

            <div class="card p-4" data-section="4">
                <h2 class="h6 fw-semibold d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-file-text-fill"></i>
                    <span>Final Details</span>
                    <i class="bi bi-check-circle-fill text-success ms-auto d-none" id="status-icon-4"></i>
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex flex-column gap-2">
                        <label for="kebaya_preference" class="form-label mb-0">Describe Your Kebaya Preference</label>
                        <textarea name="kebaya_preference" id="kebaya_preference" class="form-control required-for-section-4" rows="4" placeholder="Describe your desired kebaya..." required>{{ old('kebaya_preference') }}</textarea>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label for="amount_to_buy" class="form-label mb-0">Quantity (max 15)</label>
                        <input type="number" name="amount_to_buy" id="amount_to_buy" class="form-control required-for-section-4" placeholder="Enter quantity" min="1" max="15" required>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label for="date_needed" class="form-label mb-0">Date Kebaya Needed</label>
                        <input type="date" name="date_needed" id="date_needed" class="form-control required-for-section-4" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-2"><i class="bi bi-check-circle"></i> Submit Custom Order</button>
        </form>
    </section>

    <div class="mb-5 pb-5"></div>
    <div id="Bottom-nav" class="fixed-bottom">
        <div class="container main-content-container">
            <ul class="nav justify-content-around py-1">
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.index') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-house-door bottom-nav-icon"></i><span class="small">Browse</span></div></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.transactions') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-receipt bottom-nav-icon"></i><span class="small">Orders</span></div></a>
                </li>
                <li class="nav-item">
                     <a class="nav-link text-center active" href="{{ route('front.custom') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-pencil-square bottom-nav-icon"></i><span class="small">Custom</span></div></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-center" href="{{ route('front.contact') }}"><div class="d-flex flex-column align-items-center"><i class="bi bi-person bottom-nav-icon"></i><span class="small">Contact</span></div></a>
                </li>
            </ul>
        </div>
    </div>
</main>
@endsection

@push('after-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sizeOption = document.getElementById('size_chart_option');
    if (sizeOption) {
        const sizeChartTable = document.getElementById('sizeChartTable');
        const customSizeInputs = document.getElementById('customSizeInputs');

        const toggleSizing = () => {
            if (sizeOption.value === 'custom') {
                sizeChartTable.classList.add('d-none');
                customSizeInputs.classList.remove('d-none');
                customSizeInputs.querySelectorAll('input').forEach(input => input.required = true);
            } else {
                sizeChartTable.classList.remove('d-none');
                customSizeInputs.classList.add('d-none');
                customSizeInputs.querySelectorAll('input').forEach(input => {
                    input.required = false;
                    input.value = '';
                });
            }
        };
        sizeOption.addEventListener('change', toggleSizing);
        toggleSizing();
    }

    const sections = document.querySelectorAll('[data-section]');
    sections.forEach(section => {
        const sectionId = section.dataset.section;
        const inputs = section.querySelectorAll(`.required-for-section-${sectionId}`);
        const statusIcon = document.getElementById(`status-icon-${sectionId}`);

        if(statusIcon) {
            const checkSection = () => {
                let allFilled = true;
                inputs.forEach(input => {
                    if (!input.value) {
                        allFilled = false;
                    }
                });
                if (allFilled) {
                    statusIcon.classList.remove('d-none');
                } else {
                    statusIcon.classList.add('d-none');
                }
            };
            inputs.forEach(input => {
                input.addEventListener('keyup', checkSection);
                input.addEventListener('change', checkSection);
            });
            checkSection();
        }
    });
});
</script>
@endpush