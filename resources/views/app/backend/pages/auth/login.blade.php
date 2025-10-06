 @extends('layouts.auth.main')
 @push('css')
     <link rel="stylesheet" href="{{ url('/template') }}/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
 @endpush
 @section('left')
     <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
         <img src="{{ url('/template') }}/img/illustrations/auth-login-illustration-light.png" alt="auth-login-cover"
             class="img-fluid my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png"
             data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

         <img src="{{ url('/template') }}/img/illustrations/bg-shape-image-light.png" alt="auth-login-cover"
             class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png"
             data-app-dark-img="illustrations/bg-shape-image-dark.png" />
     </div>
 @endsection
 @section('content')
     <div class="w-px-400 mx-auto">
         <!-- Logo -->
         <div class="app-brand mb-4">
             <a href="index.html" class="app-brand-link gap-2">
                 <span class="app-brand-logo demo">
                     <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                         <path fill-rule="evenodd" clip-rule="evenodd"
                             d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                             fill="#7367F0" />
                         <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                             d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                         <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                             d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                         <path fill-rule="evenodd" clip-rule="evenodd"
                             d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                             fill="#7367F0" />
                     </svg>
                 </span>
             </a>
         </div>
         <!-- /Logo -->
         <h3 class="mb-1 fw-bold">Welcome to Vuexy! 👋</h3>
         <p class="mb-4">Please sign-in to your account and start the adventure</p>

         <form id="loginForm" class="mb-3" action="{{ route('login') }}" method="POST">
             @csrf
             <div class="mb-3">
                 <label for="email" class="form-label">Email</label>
                 <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                     autofocus />
             </div>
             <div class="mb-3 form-password-toggle">
                 <div class="d-flex justify-content-between">
                     <label class="form-label" for="password">Password</label>
                     <a href="auth-forgot-password-cover.html">
                         <small>Forgot Password?</small>
                     </a>
                 </div>
                 <div class="input-group input-group-merge">
                     <input type="password" id="password" class="form-control" name="password"
                         placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                         aria-describedby="password" />
                     <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                 </div>
             </div>
             <button type="submit" id="btnLogin" class="btn btn-primary d-grid w-100">
                 <span class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                 <span class="btn-text">Sign in</span>
             </button>
         </form>

         <p class="text-center">
             <span>New on our platform?</span>
             <a href="{{ route('register') }}">
                 <span>Create an account</span>
             </a>
         </p>
     </div>
 @endsection
 @push('js')
     <link rel="stylesheet" href="{{ url('/template') }}/vendor/css/pages/page-auth.css" />
     <script>
         $('#loginForm').on('submit', function(e) {
             e.preventDefault();

             let $btn = $('#btnLogin');
             let $spinner = $btn.find('.spinner-border');
             let $text = $btn.find('.btn-text');

             $btn.prop('disabled', true);
             $spinner.removeClass('d-none');
             $text.text('Processing...');

             $.ajax({
                 url: $(this).attr('action'),
                 method: 'POST',
                 data: $(this).serialize(),
                 success: function(response) {
                     if (response.status === 'success') {
                         Swal.fire({
                             icon: 'success',
                             title: 'Berhasil',
                             text: response.message[0],
                             timer: 1500,
                             showConfirmButton: false
                         }).then(() => {
                             window.location.href = response.redirect ?? '/~admin';
                         });
                     } else {
                         Swal.fire({
                             icon: 'error',
                             title: 'Error',
                             text: response.message[0],
                         });
                     }
                 },
                 error: function(xhr) {
                     Swal.fire({
                         icon: 'error',
                         title: 'Error',
                         text: xhr.responseJSON?.message?.[0] ?? "Terjadi kesalahan",
                     });
                 },
                 complete: function() {
                     // Reset button setelah selesai
                     $btn.prop('disabled', false);
                     $spinner.addClass('d-none');
                     $text.text('Sign in');
                 }
             });
         });
     </script>
 @endpush
