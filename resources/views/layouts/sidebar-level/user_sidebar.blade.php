<!-- Nav items -->
<ul class="navbar-nav">
    {{-- <li class="nav-item">
        <a class="nav-link {{ Request::is('dashboard') ? 'active' : ''}}" href="/dashboard">
            <i class="ni ni-tv-2 text-primary"></i>
            <span class="nav-link-text">หน้าหลัก</span>
        </a>
    </li> --}}

    {{-- material --}}
    <h3 class="ml-2 mb-0">วัสดุ</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('material') || Request::is('material/stock/out/*') ? 'active' : '' }}"
            href="/material">
            <i class="fas fa-tools"></i>
            <span class="nav-link-text">เบิกวัสดุ</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('status/wait') ? 'active' : '' }}" href="/status/wait">
            <i class="fas fa-spinner text-primary"></i>
            <span class="nav-link-text">รอนุมัติ</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('status/accept') ? 'active' : '' }}" href="/status/accept">
            <i class="fas fa-check-square text-success"></i>
            <span class="nav-link-text">อนุมัติแล้ว</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('status/cancel') ? 'active' : '' }}" href="/status/cancel">
            <i class="fas fa-window-close text-danger"></i>
            <span class="nav-link-text">ไม่อนุมัติ</span>
        </a>
    </li>
    {{-- -------------------------------- --}}

    {{-- QRCode --}}
    <h3 class="ml-2 mb-0">QRCode</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('qrcode/scan/out') ? 'active' : '' }}" href="/qrcode/scan/out">
            <i class="fas fa-sign-out-alt text-danger"></i>
            <span class="nav-link-text">เบิกวัสดุด้วย QRCode</span>
        </a>
    </li>
    <li class="nav-item">
        {{-- <a class="nav-link {{ Request::is('qrcode') ? 'active' : '' }}" href="{{route('qr.code')}}">
            <i class="fas fa-qrcode text-primary"></i>
            <span class="nav-link-text">QRCode</span>
        </a> --}}
    </li>
    {{-- -------------------------------- --}}

    {{-- history --}}
    {{-- <h3 class="ml-2 mb-0">ประวัติ</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('history/out') ? 'active' : '' }}" href="/history/out">
            <i class="fas fa-history text-orange"></i>
            <span class="nav-link-text">ประวัติการเบิกวัสดุ</span>
        </a>
    </li> --}}
    {{-- -------------------------------- --}}

    {{-- setting --}}
    <h3 class="ml-2 mb-0">ตั้งค่า</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('user/detail') ? 'active' : '' }}" href="{{route('user.detail')}}">
            <i class="ni ni-single-02 text-yellow"></i>
            <span class="nav-link-text">โปรไฟล์</span>
        </a>
    </li>

    <li class="nav-item d-lg-none">
        <a id="a_logout" class="nav-link" href="{{ route('user.logout') }}">
            <i class="ni ni-single-02 text-yellow"></i>
            <span class="nav-link-text">ออกจากระบบ</span>
        </a>

        <form id="user-logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>
</ul>
<script type="application/javascript">
    $(document).ready(function () {
        $('#a_logout').on('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'คุณต้องการออกจากระบบ ใช่หรือไม่?',
                icon: 'info',
                showConfirmButton: true,
                showCancelButton: true,
                cancelButtonColor: '#f5365c'
            }).then(function (confirm) {
                if (confirm.value) $('#user-logout-form').submit();
            });
        });
    });
    </script>
