<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>REDHAC</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="{{ asset('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <!-- Font Awesome Local -->
    <link href="{{ asset('assets/icons/font-awesome-old/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/font-awesome-old/fonts/fontawesome-webfont3e6e.svg') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/icomoon/icomoon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/icomoon/fonts/icomoon.svg') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">


    <!-- Main CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    {{-- 
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div> --}}

    <div id="main-wrapper">

        <!-- HEADER LOGO -->
        <div class="nav-header">
            <a href="{{ url('/home') }}" class="brand-logo">
                <img src="{{ asset('assets/images/redhac.png') }}" width="74">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </div>
        </div>


        <!-- TOP HEADER -->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">

                    <div class="collapse navbar-collapse justify-content-between">

                        <div class="header-left">
                            <div class="input-group search-area right d-lg-inline-flex d-none">

                                <input type="text" class="form-control" placeholder="Search...">

                            </div>
                        </div>

                        <ul class="navbar-nav header-right">

                            <!-- USER PROFILE -->
                            @auth
                                <li class="nav-item d-flex align-items-center">
                                    <!-- Bouton Déconnexion visible -->
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf

                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">

                                            <i class="fa fa-sign-out me-1"></i>
                                            Déconnexion

                                        </button>

                                    </form>

                                </li>
                            @endauth

                        </ul>

                    </div>

                </nav>
            </div>
        </div>


        <!-- SIDEBAR -->
        <div class="deznav">
            <div class="deznav-scroll">

                @auth
                    <div class="main-profile">

                        <div class="image-bx">
                            <img src="{{ asset('assets/images/user.png') }}">
                        </div>

                        <h5 class="name">
                            Bienvenue {{ Auth::user()->nom }}
                        </h5>

                        <p class="email">
                            <small>{{ Auth::user()->email }}</small>
                        </p>

                    </div>
                @endauth


                <ul class="metismenu" id="menu">

                    <li class="nav-label">Navigation</li>

                    <li>
                        <a href="{{ url('/home') }}">
                            <i class="flaticon-144-layout"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/utilisateurs') }}">
                            <i class="fa fa-user-plus"></i>
                            <span>Utilisateurs </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/mobiles') }}">
                            <i class="fa fa-mobile"></i>
                            <span>User Mobile</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/natures') }}">
                            <i class="fa fa-location-arrow"></i>
                            <span>Nature de cas</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/collectes') }}">
                            <i class="fa fa-pencil"></i>
                            <span>Mode de collecte</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/violences') }}">
                            <i class="fa fa-sun-o"></i>
                            <span>Declaration de cas</span>
                        </a>
                    </li>

                    {{-- <li>
                        <a href="{{ url('/activites') }}">
                            <i class="fa fa-plus"></i>
                            <span>Activités</span>
                        </a>
                    </li> --}}

                    <li>
                        <a href="{{ url('/historiques') }}">
                            <i class="fa fa-cog"></i>
                            <span>Historiques de connexion</span>
                        </a>
                    </li>

                </ul>

                <div class="copyright">
                    <p>Developed by Univers Solutions</p>
                </div>

            </div>
        </div>


        <!-- PAGE CONTENT -->
        <div class="content-body">

            @yield('content')

        </div>

    </div>


    <!-- JS Vendor -->
    <script data-cfasync="false"
        src="{{ asset('assets/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexchart/apexchart.js') }}"></script>
    <script src="{{ asset('assets/vendor/owl-carousel/owl.carousel.js') }}  "></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/deznav-init.js') }}"></script>
    <script src="{{ asset('assets/js/demo.js') }}   "></script>
    <script src="{{ asset('assets/js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('assets/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins-init/morris-init.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>
