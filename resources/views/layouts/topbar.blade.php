<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="col-8 col-md-9">
                <h3 class="text-white m-0">@yield('title_page')</h3>
            </div>
            <div class="col-4 col-md-3 p-0">
                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center float-right ml-md-auto">
                    {{-- <li class="nav-item d-sm-none">
                            <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                                <i class="ni ni-zoom-split-in"></i>
                            </a>
                        </li> --}}
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item dropdown">
                            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <div class="media align-items-center">
                                    <div class="media-body mr-3 d-none d-sm-block">
                                        @guest
                                            <a href="{{ route('login') }}" class="text-white">
                                                <span>เข้าสู่ระบบ</span>
                                            </a>
                                        @else
                                            <span class="mb-0 text-sm  font-weight-bold">{{ Auth::user()->first_name }}
                                                {{ Auth::user()->last_name }}</span>
                                        @endguest

                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu  dropdown-menu-right">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">ยินดีต้อนรับ</h6>
                                </div>
                                <a href="{{ route('user.detail') }}" class="dropdown-item">
                                    <i class="ni ni-single-02"></i>
                                    <span>โปรไฟล์</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('user.logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="ni ni-user-run"></i>
                                    <span>ออกจากระบบ</span>
                                </a>
                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                    <li class="nav-item d-xl-none">
                        <!-- Sidenav toggler -->
                        <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-target="#sidenav-main">
                            <div class="sidenav-toggler-inner">
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                                <i class="sidenav-toggler-line"></i>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>
