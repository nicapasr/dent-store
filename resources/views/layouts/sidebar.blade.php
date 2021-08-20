<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white d-print-none"
    id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <img src="{{ asset('assets/img/Logo.png') }}" class="navbar-brand-img" alt="...">
                <h3>DENT STORE</h3>
            </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                @guest
                @include('layouts.sidebar-level.guest_sidebar')
                @else
                @if (auth()->user()->permission == 1)
                @include('layouts.sidebar-level.admin_sidebar')
                @elseif (auth()->user()->permission == 2)
                @include('layouts.sidebar-level.board_sidebar')
                @else
                @include('layouts.sidebar-level.user_sidebar')
                @endif
                @endguest
            </div>
        </div>
    </div>
</nav>
