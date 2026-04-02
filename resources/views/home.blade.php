@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="form-head mb-sm-5 mb-3 d-flex flex-wrap align-items-center">
            <h2 class="font-w600 title mb-2 mr-auto">{{ __('messages.dashboard') }}</h2>
        </div>

        <div class="row">

           @if (auth()->user()->profil !== "Administrateur")
            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-users" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-2 font-w600">{{ __('messages.users') }}</h2>
                        <p class="mb-0 fs-14">
                            <span class="text-primary mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_users }}
                            </span>
                            {{ __('messages.registered_users') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-file-text" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-0 font-w600">{{ __('messages.case_types') }}</h2>
                        <p class="mb-0 fs-13">
                            <span class="text-success mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_natures }}
                            </span>
                            {{ __('messages.registered_types') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-database" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-2 font-w600">{{ __('messages.collection_methods') }}</h2>
                        <p class="mb-0 fs-14">
                            <span class="text-info mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_collectes }}
                            </span>
                            {{ __('messages.registered_methods') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 m-t35">
                <div class="card card-coin">
                    <div class="card-body text-center">
                        <i class="fa fa-exclamation-triangle" style="font-size:48px;color:#29008a;"></i>
                        <h2 class="text-black mb-2 font-w600">{{ __('messages.violence_reports') }}</h2>
                        <p class="mb-0 fs-14">
                            <span class="text-danger mr-1" style="font-size:24px; font-weight:bold;">
                                {{ $nb_violences }}
                            </span>
                            {{ __('messages.reported_violence_cases') }}
                        </p>
                    </div>
                </div>
            </div>
           @endif
        </div>

        <!-- GRAPHIQUE -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fs-20 text-black">{{ __('messages.violence_chart_title') }}</h4>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="violenceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fs-20 text-black">{{ __('messages.violence_chart_title') }} {{ __('messages.percentage') ?? '%' }}</h4>
                    </div>
                    <div class="card-body">
                        <div style="height: 400px;">
                            <canvas id="violencePieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = {!! json_encode($labels) !!};
            const dataValues = {!! json_encode($values) !!};

            // Graphique en bâtons
            const ctx = document.getElementById('violenceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __('messages.case_count_label') }}',
                        data: dataValues,
                        backgroundColor: 'rgba(41, 0, 138, 0.7)',
                        borderColor: '#29008a',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                }
            });

            // Graphique en camembert (Pie chart)
            const ctxPie = document.getElementById('violencePieChart').getContext('2d');
            const colors = [
                'rgba(41, 0, 138, 0.7)',
                'rgba(220, 20, 60, 0.7)',
                'rgba(255, 165, 0, 0.7)',
                'rgba(34, 139, 34, 0.7)',
                'rgba(65, 105, 225, 0.7)',
                'rgba(220, 20, 60, 0.5)',
                'rgba(255, 215, 0, 0.7)',
                'rgba(128, 0, 128, 0.7)',
                'rgba(0, 128, 128, 0.7)',
                'rgba(255, 69, 0, 0.7)'
            ];

            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: colors.slice(0, labels.length),
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 15 }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection