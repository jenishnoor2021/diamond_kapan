<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            @include('includes.topbar')
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            @include('includes.sidebar')
        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('includes.content_footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        @include('includes.right-sidebar')
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    @include('includes.footer')