 <!-- build:js backend/vendor/js/core.js -->
 <script src="{{ url('/template') }}/vendor/libs/jquery/jquery.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/popper/popper.js"></script>
 <script src="{{ url('/template') }}/vendor/js/bootstrap.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/node-waves/node-waves.js"></script>

 <script src="{{ url('/template') }}/vendor/libs/hammer/hammer.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/i18n/i18n.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/typeahead-js/typeahead.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/i18n/i18n.js"></script>

 <script src="{{ url('/template') }}/vendor/js/menu.js"></script>
 <!-- endbuild -->

 <!-- Vendors JS -->
 <script src="{{ url('/template') }}/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/apex-charts/apexcharts.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/swiper/swiper.js"></script>
 <script src="{{ url('/template') }}/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
 <script src="{{ url('/template') }}/js/tables-datatables-basic.js"></script>

 <!-- Main JS -->
 <script src="{{ url('/template') }}/js/main.js"></script>

 <!-- Page JS -->
 <script src="{{ url('/template') }}/js/pages-auth.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="{{ url('/template') }}/assets/js/backend.js"></script>

 <script>
     @if (session('success'))
         Swal.fire({
             icon: 'success',
             title: 'Berhasil',
             text: {!! json_encode(session('success')) !!}
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
