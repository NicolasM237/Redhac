<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>REDHAC-SOMONE</title>
    <!-- CSS Fournisseurs -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/font-awesome-old/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/font-awesome-old/fonts/fontawesome-webfont3e6e.svg') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/icomoon/icomoon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/icomoon/fonts/icomoon.svg') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div id="main-wrapper">

        <!-- EN-TÊTE LOGO -->
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

        <!-- EN-TÊTE PRINCIPAL -->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">

                        <div class="header-left">
                            <div class="input-group search-area right d-lg-inline-flex d-none">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('messages.search1') }}...">
                            </div>
                        </div>

                        <!-- CHANGEMENT DE LANGUE -->
                        <li class="nav-item dropdown">
                            <a class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                                🌐 {{ __('messages.language') }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('lang.switch', 'fr') }}">🇫🇷
                                    {{ __('messages.french') }}</a>
                                <a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇬🇧
                                    {{ __('messages.english') }}</a>
                            </div>
                        </li>

                        <!-- DÉCONNEXION -->
                        <ul class="navbar-nav header-right">
                            @auth
                                <li class="nav-item d-flex align-items-center">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                                            <i class="fa fa-sign-out me-1"></i>
                                            {{ __('messages.logout') }}
                                        </button>
                                    </form>
                                </li>
                            @endauth
                        </ul>

                    </div>
                </nav>
            </div>
        </div>

        <script>
            function confirmLogout() {
                Swal.fire({
                    title: '{{ __('messages.confirm_logout_title') }}',
                    text: '{{ __('messages.confirm_logout_message') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __('messages.confirm_logout_yes') }}',
                    cancelButtonText: '{{ __('messages.confirm_logout_cancel') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            }
        </script>

        <!-- BARRE LATÉRALE -->
        <div class="deznav">
            <div class="deznav-scroll">
                @auth
                    <div class="main-profile">
                        <div class="image-bx">
                            <a href="{{ url('/home') }}">
                                <img src="{{ asset('assets/images/user.png') }}">
                            </a>
                        </div>
                        <h5 class="name">
                            <small>{{ __('messages.acceuil') }}</small> {{ Auth::user()->nom }}
                        </h5>
                        <p class="email">
                            <small>{{ Auth::user()->email }}</small>
                        </p>
                    </div>
                @endauth

                <ul class="metismenu" id="menu">
                    <li class="nav-label">{{ __('messages.navigation') }}</li>
                    <li>
                        <a href="{{ url('/home') }}">
                            <i class="flaticon-144-layout"></i>
                            <span>{{ __('messages.dashboard') }}</span>
                        </a>
                    </li>

                    @if (auth()->user()->profil !== 'Utilisateur')
                        <li class="nav-label">{{ __('messages.administration') }}</li>
                        <li>
                            <a href="{{ url('/utilisateurs') }}">
                                <i class="fa fa-user-plus"></i>
                                <span>{{ __('messages.users') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/mobiles') }}">
                                <i class="fa fa-mobile"></i>
                                <span>{{ __('messages.mobile_users') }}</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ url('/natures') }}">
                            <i class="fa fa-location-arrow"></i>
                            <span>{{ __('messages.case_types') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/collectes') }}">
                            <i class="fa fa-pencil"></i>
                            <span>{{ __('messages.collection_methods') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/violences') }}">
                            <i class="fa fa-sun-o"></i>
                            <span>{{ __('messages.violence_reports') }}</span>
                        </a>
                    </li>

                    @if (auth()->user()->profil !== 'Utilisateur')
                        <li>
                            <a href="{{ url('/activites') }}">
                                <i class="fa fa-tasks"></i>
                                <span>{{ __('messages.activites') }}</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/historiques') }}">
                                <i class="fa fa-cog"></i>
                                <span>{{ __('messages.history_logs') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- CONTENU DE LA PAGE -->
        <div class="content-body">
            @yield('content')
            <small class="copyright" style="text-align:center;">
                <p>
                    {{ __('messages.copyright') }}
                    <a href="/login">{{ __('messages.developed_by') }}</a> 2026
                </p>
            </small>
        </div>
    </div>

    <!-- JS Fournisseurs -->
    <script data-cfasync="false"
        src="{{ asset('assets/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexchart/apexchart.js') }}"></script>
    <script src="{{ asset('assets/vendor/owl-carousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/deznav-init.js') }}"></script>
    <script src="{{ asset('assets/js/demo.js') }}"></script>
    <script src="{{ asset('assets/js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('assets/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins-init/morris-init.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
