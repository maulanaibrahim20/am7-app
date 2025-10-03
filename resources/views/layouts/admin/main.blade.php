<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ url('/template') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Vuexy - Bootstrap Admin Template</title>

    <meta name="description" content="" />

    <!-- Vendors CSS -->
    @include('layouts.components.style_css')
    @stack('css')
    <style>
        #img-loader {
            height: 40px;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.admin.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('layouts.admin.navbar')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        @hasSection('breadcrumb')
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                                <h4 class="fw-bold mb-0">
                                    <span class="text-muted fw-light">Dashboard / </span>
                                    @yield('breadcrumb')
                                </h4>

                                {{-- Right side button section --}}
                                @hasSection('page_nav_button')
                                    <div class="d-flex align-items-center">
                                        @yield('page_nav_button')
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Page Content --}}
                        @yield('content')
                    </div>

                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.admin.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <div class="modal fade" id="ajaxModal" tabindex="-1" atabindex="-1" role="dialog"
        aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@yield('modal_title')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modal-message"></div>
                    <div class="modal_content">
                        <center>
                            <img id="img-loader" src="{{ url('template/assets/svg/loading.svg') }}" height="40"
                                alt="Loading.." />
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="ajaxOffcanvas" aria-labelledby="ajaxOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 id="ajaxOffcanvasLabel" class="offcanvas-title">@yield('offcanvas_title')</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div id="offcanvas-message"></div>
            <div class="offcanvas_content">
                <center>
                    <img id="img-loader" src="{{ url('template/assets/svg/loading.svg') }}" height="10"
                        alt="Loading.." />
                </center>
            </div>
        </div>
    </div>


    {{-- Custome JS --}}
    <script src="{{ url('/template') }}/js/wizard-ex-checkout.js"></script>

    @include('layouts.components.style_js')
    @stack('js')
</body>

</html>
