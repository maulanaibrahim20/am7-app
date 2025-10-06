<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>CarServ - Car Repair HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ url('/landing') }}/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ url('/landing') }}/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ url('/landing') }}/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="{{ url('/landing') }}/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ url('/landing') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('/template') }}/assets/css/datatable-lokal.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="{{ url('/landing') }}/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    @include('layouts.frontend.topbar')
    <!-- Topbar End -->


    <!-- Navbar Start -->
    @include('layouts.frontend.navbar')
    <!-- Navbar End -->


    @yield('content')


    <!-- Footer Start -->
    @include('layouts.frontend.footer')
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


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

    <!-- JavaScript Libraries -->
    {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
    <script src="{{ url('/template') }}/assets/js/jquery_3.6.0.min.js"></script>
    <script src="{{ url('/template') }}/assets/js/jquery.form.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('/landing') }}/lib/wow/wow.min.js"></script>
    <script src="{{ url('/landing') }}/lib/easing/easing.min.js"></script>
    <script src="{{ url('/landing') }}/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ url('/landing') }}/lib/counterup/counterup.min.js"></script>
    <script src="{{ url('/landing') }}/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="{{ url('/landing') }}/lib/tempusdominus/js/moment.min.js"></script>
    <script src="{{ url('/landing') }}/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="{{ url('/landing') }}/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="{{ url('/template') }}/assets/js/backend.js"></script>
    <script src="{{ url('/landing') }}/js/main.js"></script>
    <script src="{{ url('/template') }}/assets/js/datatable-lokal.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: {!! json_encode(session('success')) !!}
            });
        @endif

        @if (session('msg'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: {!! json_encode(session('msg')) !!}
            });
        @endif

        // Error (single message)
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: {!! json_encode(session('error')) !!}
            });
        @endif

        // Warning / Info (optional)
        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: {!! json_encode(session('warning')) !!}
            });
        @endif

        // Validation errors (Laravel $errors)
        @if ($errors->any())
            // gabungkan semua pesan menjadi HTML dengan <br>
            const validationHtml = {!! json_encode(implode('<br>', $errors->all())) !!};
            Swal.fire({
                icon: 'error',
                title: 'Validasi gagal',
                html: validationHtml,
                // optionally: allow html tags
            });
        @endif
    </script>
</body>

</html>
