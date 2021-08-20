<!-- Nav items -->
<ul class="navbar-nav">
    <li class="nav-item">
        {{-- <a class="nav-link {{ Request::is('home') || Request::is('widthdraw') ? 'active' : '' }}" href="/home">
        <i class="ni ni-tv-2 text-primary"></i>
        <span class="nav-link-text">หน้าหลัก</span>
        </a> --}}

        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : ''}}" href="/admin/dashboard">
            <i class="ni ni-tv-2 text-primary"></i>
            <span class="nav-link-text">หน้าหลัก</span>
        </a>
    </li>

    {{-- material --}}
    <h3 class="ml-2 mb-0">วัสดุ</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/material/stock/in') ? 'active' : '' }}"
            href="{{route('material.stock.in.view')}}">
            <i class="fas fa-sign-in-alt text-success"></i>
            <span class="nav-link-text">นำเข้า</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/material/stock/out') ? 'active' : '' }}"
            href="{{route('admin.material.stock.out.view')}}">
            <i class="fas fa-sign-out-alt text-danger"></i>
            <span class="nav-link-text">เบิก</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/material/new') ? 'active' : '' }}" href="/admin/material/new">
            <i class="ni ni-curved-next text-primary"></i>
            <span class="nav-link-text">เพิ่มวัสดุใหม่</span>
        </a>
    </li>
    {{-- <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/material/stock/out/list') ? 'active' : '' }}" href="{{route('admin.material.stock.out.list.view')}}">
            <i class="fas fa-check-circle text-success"></i>
            <span class="nav-link-text">รายการเบิกวัสดุ</span>
        </a>
    </li> --}}
    {{-- -------------------------------- --}}

    {{-- QRCode --}}
    <h3 class="ml-2 mb-0">QRCode</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/qrcode/scan/in') ? 'active' : '' }}" href="{{route('admin.qrcode.scan.in.view')}}">
            <i class="fas fa-sign-in-alt text-success"></i>
            <span class="nav-link-text">นำเข้าวัสดุด้วย QRCode</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/qrcode/scan/out') ? 'active' : '' }}" href="{{route('admin.qrcode.scan.out.view')}}">
            <i class="fas fa-sign-out-alt text-danger"></i>
            <span class="nav-link-text">เบิกวัสดุด้วย QRCode</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/qrcode') ? 'active' : '' }}" href="{{route('admin.qr.code')}}">
            <i class="fas fa-qrcode text-primary"></i>
            <span class="nav-link-text">QRCode</span>
        </a>
    </li>
    {{-- -------------------------------- --}}

    {{-- history --}}
    <h3 class="ml-2 mb-0">ประวัติ</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/history/in') ? 'active' : '' }}" href="{{route('admin.history.in.view')}}">
            <i class="fas fa-history"></i>
            <span class="nav-link-text">ประวัติการนำเข้าวัสดุ</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/history/out') ? 'active' : '' }}" href="{{route('admin.history.out.view')}}">
            <i class="ni ni-bullet-list-67 text-orange"></i>
            <span class="nav-link-text">ประวัติการเบิกวัสดุ</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('reports/*') ? 'active' : '' }}" href="{{route('reports.home')}}">
            <i class="ni ni-chart-bar-32 text-default"></i>
            <span class="nav-link-text">สรุปรายงาน</span>
        </a>
    </li>
    {{-- -------------------------------- --}}

    {{-- setting --}}
    <h3 class="ml-2 mb-0">ตั้งค่า</h3>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('admin/informations') ? 'active' : '' }}" href="/admin/informations">
            <i class="ni ni-settings text-primary"></i>
            <span class="nav-link-text">จัดการข้อมูลพื้นฐาน</span>
        </a>
    </li>
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

        <form id="admin-logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
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
                if (confirm.value) $('#admin-logout-form').submit();
            });
        });
    });
</script>
