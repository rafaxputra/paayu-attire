<div id="Bottom-nav" class="fixed-bottom">
    <div class="container main-content-container">
        <ul class="nav justify-content-around py-1">
            <li class="nav-item">
                <a class="nav-link text-center {{ request()->routeIs('front.index') ? 'active' : '' }}" href="{{ route('front.index') }}">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-house-door-fill bottom-nav-icon"></i>
                        <span class="small">Browse</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-center {{ request()->routeIs('front.transactions') ? 'active' : '' }}" href="{{ route('front.transactions') }}">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-receipt bottom-nav-icon"></i>
                        <span class="small">Orders</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-center {{ request()->routeIs('front.custom') ? 'active' : '' }}" href="{{ route('front.custom') }}">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-pencil-square bottom-nav-icon"></i>
                        <span class="small">Custom</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-center {{ request()->routeIs('front.contact') ? 'active' : '' }}" href="{{ route('front.contact') }}">
                    <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-person bottom-nav-icon"></i>
                        <span class="small">Contact</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
