<div class="navbar-header">
    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="{{ URL::to('/admin/dashboard') }}" class="logo logo-dark">
                <!-- <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
                </span> -->
                <h1 class="text-white">DHD</h1>
            </a>

            <a href="{{ URL::to('/admin/dashboard') }}" class="logo logo-light">
                <!-- <span class="logo-sm">
                    <img src="{{ asset('assets/images/logo-light.svg') }}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="19">
                </span> -->
                <h1 class="text-white">DHD</h1>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
            <i class="fa fa-fw fa-bars"></i>
        </button>
    </div>

    <div class="d-flex">

        <div class="dropdown d-none d-lg-inline-block ms-1">
            <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                <i class="bx bx-fullscreen"></i>
            </button>
        </div>

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="{{ asset('/images/user-icone.png') }}"
                    alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{Session::get('user')['name']}}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <a class="dropdown-item" href="/profile/{{Session::get('user')['id']}}"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="/logout"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
            </div>
        </div>

        <div class="dropdown d-inline-block">
            <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                <i class="bx bx-cog bx-spin"></i>
            </button>
        </div>

    </div>
</div>