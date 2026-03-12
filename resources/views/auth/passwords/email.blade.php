
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="keywords" content="">
  <meta name="author" content="">
  <meta name="robots" content="">
  <title>REDHAC</title>

  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/redhac.png">

  <!-- CSS Vendor -->
  <link rel="stylesheet" href="assets/vendor/chartist/css/chartist.min.css">
  <link href="assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
  <link href="assets/vendor/owl-carousel/owl.carousel.css" rel="stylesheet">

  <link href="assets/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
  <!-- Custom Stylesheet -->
  <link href="assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
  <!-- Font Awesome Local -->
  <link href="assets/icons/font-awesome-old/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/icons/font-awesome-old/fonts/fontawesome-webfont3e6e.svg" rel="stylesheet">
  <link href="assets/icons/icomoon/icomoon.css" rel="stylesheet">
  <link href="assets/icons/icomoon/fonts/icomoon.svg" rel="stylesheet">
    <link href="assets/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css" rel="stylesheet">


  <!-- Main CSS -->
  <link href="{{ asset('assets/css/style.css') }}   " rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
