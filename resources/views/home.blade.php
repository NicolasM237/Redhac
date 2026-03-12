@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="form-head mb-sm-5 mb-3 d-flex flex-wrap align-items-center">
            <h2 class="font-w600 title mb-2 mr-auto">Tableau de bord</h2>
        </div>

        <div class="row">

            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-users" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-2 font-w600">Utilisateurs</h2>
                        <p class="mb-0 fs-14">
                            <span class="text-primary mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_users }}
                            </span>
                            utilisateurs inscrits
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-file-text" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-0 font-w600">Nature de cas</h2>
                        <p class="mb-0 fs-13">
                            <span class="text-success mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_natures }}
                            </span>
                            natures enregistrées
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-database" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-2 font-w600">Mode de collecte</h2>
                        <p class="mb-0 fs-14">
                            <span class="text-info mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_collectes }}
                            </span>
                            modes enregistrés
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-exclamation-triangle" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-2 font-w600">Déclaration cas</h2>
                        <p class="mb-0 fs-14">
                            <span class="text-danger mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_violences }}
                            </span>
                            cas de violences
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
                        <h4 class="fs-20 text-black">Graphes Violences par pays</h4>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="violenceChart"></canvas>
                        </div>
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('violenceChart').getContext('2d');

            // Récupération des données injectées par HomeController
            const labels = {!! json_encode($labels) !!};
            const dataValues = {!! json_encode($values) !!};

            new Chart(ctx, {
                type: 'bar', // Type d'histogramme
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de cas',
                        data: dataValues,
                        backgroundColor: 'rgba(41, 0, 138, 0.7)', // Ton violet #29008a
                        borderColor: '#29008a',
                        borderWidth: 1,
                        borderRadius: 5 // Optionnel : arrondit un peu les barres
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Force l'affichage de nombres entiers (1, 2, 3...)
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // On cache la légende car le titre de la carte suffit
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
