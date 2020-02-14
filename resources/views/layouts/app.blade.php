<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Technical Test - Fabelio</title>
    <meta name="description" content="The Technical Test from Fabelio" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.png">
    <link rel="icon" href="favicon.png" type="image/x-icon">

    <!-- Toggles CSS -->
    <link href="{{ env('APP_ASSET')('vendors/jquery-toggles/css/toggles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ env('APP_ASSET')('vendors/jquery-toggles/css/themes/toggles-light.css') }}" rel="stylesheet" type="text/css">

    @stack('assetCSS')

    <!-- Custom CSS -->
    <link href="{{ env('APP_ASSET')('css/style.css') }}" rel="stylesheet" type="text/css">

</head>

<body>
    <!-- Preloader -->
    <div class="preloader-it">
        <div class="loader-pendulums"></div>
    </div>
    <!-- /Preloader -->

	<!-- HK Wrapper -->
    <div class="hk-wrapper hk-alt-nav">

        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-xl navbar-light fixed-top hk-navbar hk-navbar-alt">
            <a class="navbar-brand mx-auto" href="javascript:void(0);">
                <img class="brand-img d-inline-block align-top" src="{{ env('APP_ASSET')('img/fabelio-logo-2.svg') }}" alt="fabelio" />
            </a>
        </nav>
        <!-- /Top Navbar -->


        <!-- Main Content -->
        <div class="hk-pg-wrapper">

            <!-- Container -->
            <div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
                <!-- Title -->
                <div class="hk-pg-header">
                    <h4 class="hk-pg-title"><span class="pg-title-icon"><span class="feather-icon"><i data-feather="book"></i></span></span>{{ $title }}</h4>
                </div>
                <!-- /Title -->

                <!-- Row -->
                <div class="row">
                    <div class="col-xl-12">
                        @yield('content')
					</div>
                </div>
                <!-- /Row -->
            </div>
            <!-- /Container -->

            <!-- Footer -->
            <div class="hk-footer-wrap container-fluid">
                <footer class="footer">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <p>Copyright by <span class="text-dark">Khoiru Setyo Nugroho</span> © 2020</p>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <p class="d-inline-block">Follow me</p>
                            <a href="https://www.linkedin.com/in/khoirulsetyo/" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-linkedin"></i></span></a>
                            <a href="https://twitter.com/khoirulsetyo" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-twitter"></i></span></a>
                            <a href="https://www.instagram.com/irulsn/" class="d-inline-block btn btn-icon btn-icon-only btn-indigo btn-icon-style-4"><span class="btn-icon-wrap"><i class="fa fa-instagram"></i></span></a>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- /Footer -->

        </div>
        <!-- /Main Content -->

    </div>
   <!-- /HK Wrapper -->

    <!-- jQuery -->
    <script src="{{ env('APP_ASSET')('vendors/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ env('APP_ASSET')('vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ env('APP_ASSET')('vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll JavaScript -->
    <script src="{{ env('APP_ASSET')('js/jquery.slimscroll.js') }}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{ env('APP_ASSET')('js/dropdown-bootstrap-extended.js') }}"></script>

    <!-- FeatherIcons JavaScript -->
    <script src="{{ env('APP_ASSET')('js/feather.min.js') }}"></script>

    <!-- Tablesaw JavaScript -->
    <script src="{{ env('APP_ASSET')('vendors/tablesaw/dist/tablesaw.jquery.js') }}"></script>
    <script src="{{ env('APP_ASSET')('js/tablesaw-data.js') }}"></script>

    <!-- Toggles JavaScript -->
    <script src="{{ env('APP_ASSET')('vendors/jquery-toggles/toggles.min.js') }}"></script>
    <script src="{{ env('APP_ASSET')('js/toggle-data.js') }}"></script>

    @stack('assetJS')

    <!-- Init JavaScript -->
    <script src="{{ env('APP_ASSET')('js/init.js') }}"></script>

    @stack('script')
</body>

</html>


