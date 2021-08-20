<!-- Nav items -->
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="/home">
        <i class="ni ni-tv-2 text-primary"></i>
        <span class="nav-link-text">หน้าหลัก</span>
        </a>
    </li>

    {{-- QRCode --}}
    <li class="nav-item">
        {{-- <a class="nav-link {{ Request::is('qrcode') ? 'active' : '' }}" href="{{route('qr.code')}}">
            <i class="fas fa-qrcode text-primary"></i>
            <span class="nav-link-text">QRCode</span>
        </a> --}}
    </li>
    {{-- -------------------------------- --}}

    {{-- contact --}}
    <li class="nav-item">
        {{-- <a class="nav-link {{ Request::is('contact') ? 'active' : '' }}" href="{{route('qr.code')}}">
            <i class="fas fa-phone-volume text-orange"></i>
            <span class="nav-link-text">ติดต่อเรา</span>
        </a> --}}
    </li>
    {{-- -------------------------------- --}}

    {{-- about --}}
    <li class="nav-item">
        {{-- <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{route('qr.code')}}">
            <i class="fas fa-id-card"></i>
            <span class="nav-link-text">เกี่ยวกับเรา</span>
        </a> --}}
    </li>
    {{-- -------------------------------- --}}
</ul>
