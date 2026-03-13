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

    <body class="vh-100">
        <div class="authincation h-100">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-md-6">
                        <div class="authentication-content"
                            style="background-color: #00346b; height: 540px; display: flex; align-items: center; justify-content: center; color: white; padding: 20px; border-radius: 8px;">
                            <div class="text-center">
                                <img src="{{ asset('assets/images/redhac.png') }}" alt="Logo Redhac" width="150"
                                    height="120" style="border-radius: 10%;">
                                <h1 class="mb-3 text-white">PLATEFORME REDHAC</h1>
                                <p class="mb-0">Panel d'administration de l'application de restriction des cas Civique</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="authincation-content">
                            <div class="row no-gutters">
                                <div class="col-xl-12">
                                    <div class="auth-form">
                                        <h2 class="text-center mb-4">CONNEXION</h2>
                                        <marquee class="mb-4">Entrez vos informations pour vous connecter</marquee>

                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <div class="mb-3">
                                                <label class="mb-1"><strong>Email</strong></label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}" required
                                                    autocomplete="email" autofocus placeholder="votre@email.com">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="mb-1"><strong>Mot de passe</strong></label>
                                                <input id="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    name="password" required autocomplete="current-password"
                                                    placeholder="********">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="row d-flex justify-content-between mt-4 mb-2">
                                                <div class="mb-3">
                                                    <div class="form-check custom-checkbox ms-1">
                                                        <input type="checkbox" class="form-check-input" name="remember"
                                                            id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="remember">Se souvenir de
                                                            moi</label>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    @if (Route::has('password.request'))
                                                        <a href="{{ route('password.request') }}">Mot de passe oublié
                                                            ?</a>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-block"
                                                    style="background-color: #00346b; border: none; width: 100%; padding: 10px;">
                                                    SE CONNECTER
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <small class="copyright" style="display: block; text-align:center; margin-top: 20px;">
                                <p>Copyright © Designed &amp; Developed by <a href="/login">Univers Solutions</a> 2026
                                </p>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
