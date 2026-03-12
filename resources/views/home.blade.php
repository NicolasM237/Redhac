@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="form-head mb-sm-5 mb-3 d-flex flex-wrap align-items-center">
        <h2 class="font-w600 title mb-2 mr-auto">Tableau de bord</h2>
    </div>

    <div class="row">

        <!-- Utilisateurs -->
        <div class="col-xl-3 col-sm-6 m-t35">
            <div class="card card-coin">
                <div class="card-body text-center">
                    <i class="fa fa-users" style="font-size:48px;color:#29008a;"></i>

                    <h2 class="text-black mb-2 font-w600">Utilisateurs</h2>

                    <p class="mb-0 fs-14">
                        <span class="text-success mr-1" style="font-size:18px;">
                          
                        </span>
                        utilisateurs enregistrés
                    </p>

                </div>
            </div>
        </div>

        <!-- Nature de cas -->
        <div class="col-xl-3 col-sm-6 m-t35">
            <div class="card card-coin">
                <div class="card-body text-center">
                    <i class="fa fa-file-text" style="font-size:48px;color:#29008a;"></i>

                    <h2 class="text-black mb-0 font-w600">Nature de cas</h2>

                    <p class="mb-0 fs-13">
                        <span class="text-success mr-1" style="font-size:18px;">
                     
                        </span>
                        natures enregistrées
                    </p>

                </div>
            </div>
        </div>

        <!-- Mode collecte -->
        <div class="col-xl-3 col-sm-6 m-t35">
            <div class="card card-coin">
                <div class="card-body text-center">
                    <i class="fa fa-database" style="font-size:48px;color:#29008a;"></i>

                    <h2 class="text-black mb-2 font-w600">Mode de collecte</h2>

                    <p class="mb-0 fs-14">
                        <span class="text-success mr-1" style="font-size:18px;">
                          
                        </span>
                        modes de collecte enregistrés
                    </p>

                </div>
            </div>
        </div>

        <!-- Violences -->
        <div class="col-xl-3 col-sm-6 m-t35">
            <div class="card card-coin">
                <div class="card-body text-center">
                    <i class="fa fa-exclamation-triangle" style="font-size:48px;color:#29008a;"></i>

                    <h2 class="text-black mb-2 font-w600">Declaration de cas</h2>

                    <p class="mb-0 fs-14">
                        <span class="text-success mr-1" style="font-size:18px;">
                         
                        </span>
                        cas de violences enregistrés
                    </p>

                </div>
            </div>
        </div>

    </div>


    <!-- GRAPH -->
    <div class="row">

        <div class="col-xl-12">

            <div class="card">

                <div class="card-header">
                    <h4 class="fs-20 text-black">Violences par pays</h4>
                </div>

                <div class="card-body">

                    <canvas id="violenceChart"></canvas>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-xl-12">

            <div class="card">

                <div class="card-header">
                    <h4 class="card-title">Graphique sur genre</h4>
                </div>

                <div class="card-body">

                    graphique sur le nombre d'utilisateurs par genre

                </div>

            </div>

        </div>

    </div>


    <small class="copyright" style="text-align:center;">

        <p>
            Copyright © Designed & Developed by
            <a href="/login">Univers Solutions</a> 2026
        </p>

    </small>

</div>

@endsection