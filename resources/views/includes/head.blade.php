<meta charset="utf-8" />
<title>Login | Skote - Admin & Dashboard Template</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
<meta content="Themesbrand" name="author" />
<!-- App favicon -->
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

<!-- DataTables -->
<link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

<!-- Responsive datatable examples -->
<link href="{{asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

<!-- Bootstrap Css -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}" id="app-style" type="text/css">

<style>
    .boldclass {
        font-weight: bold;
    }

    p {
        -webkit-user-select: all;
        /* Safari */
        -ms-user-select: all;
        user-select: all;
        margin: 0;
        white-space: pre-wrap;
    }

    .read-more-show {
        cursor: pointer;
        color: #ed8323;
    }

    .read-more-hide {
        cursor: pointer;
        color: #ed8323;
    }

    .hide_content {
        display: none;
    }

    .error {
        color: red;
    }
</style>
@yield('style')

<script src="{{ asset('assets/js/plugin.js') }}"></script>