@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4 class="text-primary">{{ __('messages.welcome_back') }}</h4>
                    <p class="mb-0">{{ __('messages.session_ready') }}</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <a class="btn btn-rounded btn-info" href="{{ url('/addviolences') }}">
                        <span class="btn-icon-left text-info"><i class="fa fa-plus"></i></span>
                        {{ __('messages.add_button') }}
                    </a>
                </ol>
            </div>
        </div>

        @if (session('success') || session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    @if (session('success'))
                        Toast.fire({
                            icon: 'success',
                            title: "{{ session('success') }}"
                        });
                    @endif

                    @if (session('error'))
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur...',
                            text: "{{ session('error') }}",
                            confirmButtonColor: '#3085d6'
                        });
                    @endif
                });
            </script>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.violences_list_title') }}</h4>
                    </div>

                    <div class="card-body">
                        <!-- Export Buttons -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('export.violences.excel', request()->query()) }}"
                                    class="btn btn-rounded btn-info btn-block export-link" data-format="excel">
                                    <span class="btn-icon-left text-info"><i class="fa fa-download"></i></span> <b>
                                        {{ __('messages.Export_Excel') }}
                                    </b>
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('export.violences.csv', request()->query()) }}"
                                    class="btn btn-rounded btn-primary btn-block export-link" data-format="csv">
                                    <span class="btn-icon-left text-primary"><i class="fa fa-download"></i></span>
                                    <b>{{ __('messages.export_csv') }}</b>
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('export.violences.pdf', request()->query()) }}"
                                    class="btn btn-rounded btn-secondary btn-block export-link" data-format="pdf">
                                    <span class="btn-icon-left text-secondary"><i class="fa fa-file-pdf-o"></i></span>
                                    <b>{{ __('messages.export_pdf') }}</b>
                                </a>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary mb-3" id="btnToggleColumns">
                            <i class="fa fa-columns"></i> {{ __('messages.select_columns') }}
                        </button>

                        <!-- Column Selector -->
                        <div class="row mb-4" id="columnSelectorSection" style="display: none;">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-body">
                                        <label
                                            class="mb-3 text-dark font-weight-bold">{{ __('messages.select_columns') }}</label>
                                        <div class="row">
                                            @php
                                                $cols = [
                                                    ['table' => 0, 'export' => null, 'label' => __('messages.actions')],
                                                    [
                                                        'table' => 1,
                                                        'export' => null,
                                                        'label' => __('messages.permitted'),
                                                    ],
                                                    ['table' => 2, 'export' => null, 'label' => '#'],
                                                    ['table' => 3, 'export' => 'code', 'label' => __('messages.code')],
                                                    [
                                                        'table' => 4,
                                                        'export' => 'nationalite',
                                                        'label' => __('messages.nationality'),
                                                    ],
                                                    [
                                                        'table' => 5,
                                                        'export' => 'status',
                                                        'label' => __('messages.status'),
                                                    ],
                                                    ['table' => 6, 'export' => 'sexe', 'label' => __('messages.sex')],
                                                    [
                                                        'table' => 7,
                                                        'export' => 'nature',
                                                        'label' => __('messages.nature_name'),
                                                    ],
                                                    [
                                                        'table' => 8,
                                                        'export' => 'collecte',
                                                        'label' => __('messages.collection_mode'),
                                                    ],
                                                    [
                                                        'table' => 9,
                                                        'export' => 'datesurvenue',
                                                        'label' => __('messages.occurrence_date'),
                                                    ],
                                                    [
                                                        'table' => 10,
                                                        'export' => 'fichiers',
                                                        'label' => __('messages.files'),
                                                    ],
                                                ];
                                            @endphp
                                            @foreach ($cols as $col)
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input column-toggle"
                                                            id="col_{{ $col['table'] }}" data-column="{{ $col['table'] }}"
                                                            data-export-column="{{ $col['export'] }}" checked>
                                                        <label class="custom-control-label"
                                                            for="col_{{ $col['table'] }}">{{ $col['label'] }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('view.violences') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-5 mb-2">
                                    <div class="input-group">
                                        <select name="nationalite" class="form-control" onchange="this.form.submit()">
                                            <option value="">-- {{ __('messages.nat') }} --</option>
                                            @foreach ($nationalites as $pays)
                                                <option value="{{ $pays }}"
                                                    {{ request('nationalite') == $pays ? 'selected' : '' }}>
                                                    {{ $pays }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <span class="btn btn-secondary">{{ __('messages.nationality') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                            placeholder="{{ __('messages.enter_code') }}" name="searchTerm"
                                            value="{{ request('searchTerm') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-info"
                                                type="submit">{{ __('messages.search_button') }}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    @if (request('nationalite') || request('searchTerm'))
                                        <a href="{{ route('view.violences') }}"
                                            class="btn btn-danger btn-block">{{ __('messages.clear_filters') }}</a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <!-- Table -->
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table id="violences-datatable" class="table table-responsive-md table-striped table-bordered"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th><b>{{ __('messages.actions') }}</b></th>
                                        <th>{{ __('messages.permitted') }}</th>
                                        <th>#</th>
                                        <th>{{ __('messages.code') }}</th>
                                        <th>{{ __('messages.nationality') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.sex') }}</th>
                                        <th>{{ __('messages.nature_name') }}</th>
                                        <th>{{ __('messages.collection_mode') }}</th>
                                        <th class="text-nowrap">{{ __('messages.occurrence_date') }}</th>
                                        <th>{{ __('messages.files') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($violences as $violence)
                                        <tr>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                        data-toggle="dropdown">{{ __('messages.actions') }}</button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item view-details" href="javascript:void(0)"
                                                            data-violences='{{ json_encode($violence) }}'>
                                                            <i class="fa fa-eye mr-2"></i> {{ __('messages.see') }}
                                                        </a>
                                                        <a href="{{ route('edit.violences', $violence->id) }}"
                                                            class="dropdown-item">
                                                            <i class="fa fa-edit mr-2"></i> {{ __('messages.edit') }}
                                                        </a>
                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDeleteViolence({{ $violence->id }})">
                                                            <i class="fa fa-trash mr-2"></i> {{ __('messages.delete') }}
                                                        </button>
                                                        <form id="delete-form-violence-{{ $violence->id }}"
                                                            action="{{ route('delete.violences', $violence->id) }}"
                                                            method="POST" style="display:none;">
                                                            @csrf @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-xs btn-primary toggle-permis"
                                                    data-id="{{ $violence->id }}">
                                                    {{ $violence->permis ? __('messages.yes') : __('messages.no') }}
                                                </button>
                                            </td>
                                            <td><strong>{{ $loop->iteration + ($violences->currentPage() - 1) * $violences->perPage() }}</strong>
                                            </td>
                                            <td class="text-nowrap">{{ $violence->code }}</td>
                                            <td>{{ $violence->nationalite }}</td>
                                            <td><span class="badge badge-light">{{ $violence->status }}</span></td>
                                            <td>{{ $violence->sexe }}</td>
                                            <td>{{ $violence->nature->nom ?? 'N/A' }}</td>
                                            <td>{{ $violence->collecte->nom ?? 'N/A' }}</td>
                                            <td class="text-nowrap">
                                                {{ \Carbon\Carbon::parse($violence->datesurvenue)->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @for ($i = 1; $i <= 3; $i++)
                                                        @php $f = "fichier".$i; @endphp
                                                        @if ($violence->$f)
                                                            <a href="{{ asset('storage/' . $violence->$f) }}"
                                                                target="_blank" class="btn btn-xs btn-info mr-1"
                                                                title="Fichier {{ $i }}">
                                                                <i class="fa fa-file"></i>
                                                            </a>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted">
                                                {{ __('messages.no_record_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $violences->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Détails -->
    <div class="modal fade" id="infosleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.violence_details_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="modal-content-area">
                        @php
                            $fields = [
                                'code' => 'Code',
                                'nationalite' => 'Nationalité',
                                'status' => 'Status',
                                'contact' => 'Contact',
                                'occupation' => 'Occupation',
                                'age' => 'Age',
                                'sexe' => 'Sexe',
                                'residence' => 'Résidence',
                                'datesurvenue' => 'Date survenance',
                                'lieusurvenue' => 'Lieu survenance',
                                'situation' => 'Situation',
                                'auteurs' => 'Auteurs',
                                'nature' => 'Nature',
                                'collecte' => 'Collecte',
                                'description_cas' => 'Description du cas',
                                'mesure_obc' => 'Mesure OBC',
                                'risque_victime' => 'Risque victime',
                                'attente_victime' => 'Attente victime',
                            ];
                        @endphp
                        @foreach ($fields as $id => $label)
                            <div class="col-md-4 mb-3">
                                <strong>{{ $label }}:</strong><br>
                                <span class="text-danger" id="mdl-{{ $id }}"></span>
                            </div>
                        @endforeach
                        <div class="col-md-4 mb-3"><strong>Fichier 1:</strong> <span id="mdl-fichier1"></span></div>
                        <div class="col-md-4 mb-3"><strong>Fichier 2:</strong> <span id="mdl-fichier2"></span></div>
                        <div class="col-md-4 mb-3"><strong>Fichier 3:</strong> <span id="mdl-fichier3"></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-dismiss="modal">{{ __('messages.close_button') }}</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Column Selector Section
            $('#btnToggleColumns').on('click', function() {
                $('#columnSelectorSection').slideToggle();
            });

            // Initialisation DataTable
            var table = $('#violences-datatable').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                order: [
                    [3, 'asc']
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                }
            });

            // Gestion des toggles de colonnes
            function getSelectedExportColumns() {
                const cols = [];
                $('.column-toggle:checked').each(function() {
                    const exportKey = $(this).data('export-column');
                    if (exportKey) cols.push(exportKey);
                });
                return cols;
            }

            function updateExportLinks() {
                const selected = getSelectedExportColumns();
                $('.export-link').each(function() {
                    const currentUrl = new URL($(this).attr('href'), window.location.origin);
                    currentUrl.searchParams.delete('columns');

                    if (selected.length) {
                        currentUrl.searchParams.set('columns', selected.join(','));
                    }

                    $(this).attr('href', currentUrl.toString());
                });
            }

            $('.column-toggle').on('change', function() {
                const columnIndex = $(this).data('column');
                table.column(columnIndex).visible(this.checked);
                updateExportLinks();
            });

            // Initialisation des colonnes depuis query string
            const initialCols = new URLSearchParams(window.location.search).get('columns');
            if (initialCols) {
                const active = initialCols.split(',');
                $('.column-toggle').each(function() {
                    const exportKey = $(this).data('export-column');
                    if (exportKey) {
                        $(this).prop('checked', active.includes(exportKey));
                        table.column($(this).data('column')).visible(active.includes(exportKey));
                    }
                });
            }

            updateExportLinks();

            // Modal de détails
            $('.view-details').on('click', function() {
                const data = $(this).data('violences');

                // Remplissage des champs texte
                $('#mdl-code').text(data.code || '');
                $('#mdl-nationalite').text(data.nationalite || '');
                $('#mdl-status').text(data.status || '');
                $('#mdl-contact').text(data.contact || '');
                $('#mdl-occupation').text(data.occupation || '');
                $('#mdl-age').text(data.age || '');
                $('#mdl-sexe').text(data.sexe || '');
                $('#mdl-residence').text(data.residence || '');
                $('#mdl-datesurvenue').text(data.datesurvenue || '');
                $('#mdl-lieusurvenue').text(data.lieusurvenue || '');
                $('#mdl-situation').text(data.situation || '');
                $('#mdl-auteurs').text(data.auteurs || '');
                $('#mdl-nature').text(data.nature?.nom || 'N/A');
                $('#mdl-collecte').text(data.collecte?.nom || 'N/A');
                $('#mdl-description_cas').text(data.description_cas || '');
                $('#mdl-mesure_obc').text(data.mesure_obc || '');
                $('#mdl-risque_victime').text(data.risque_victime || '');
                $('#mdl-attente_victime').text(data.attente_victime || '');

                // Gestion des fichiers dans le modal
                for (let i = 1; i <= 3; i++) {
                    let field = 'fichier' + i;
                    if (data[field]) {
                        let filePath = data[field];

                        // Nettoyage et dédoublonnage du chemin
                        if (!/^https?:\/\//i.test(filePath)) {
                            // enlever leading slashs
                            filePath = filePath.replace(/^\/*/, '');

                            // enlever duplication de segment
                            filePath = filePath.replace(/^storage\//i, '');
                            filePath = filePath.replace(/^violences\//i, '');

                            // chemin final vers le disque public
                            filePath = '/storage/violences/violences/' + filePath;
                        }

                        $('#mdl-' + field).html(
                            `<a href="${filePath}" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-download"></i> Voir</a>`
                        );
                    } else {
                        $('#mdl-' + field).text('Aucun');
                    }
                }

                $('#infosleModal').modal('show');
            });

            // Toggle Permis Ajax
            $('.toggle-permis').on('click', function() {
                let id = $(this).data('id');
                let btn = $(this);
                fetch(`/violences/${id}/toggle-permis`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            btn.text(data.permis ? "{{ __('messages.yes') }}" :
                                "{{ __('messages.no') }}");
                        }
                    });
            });
        });

        function confirmDeleteViolence(id) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-violence-' + id).submit();
                }
            })
        }
    </script>

    <style>
        .table-scrollable {
            max-height: 500px;
            overflow-y: auto;
            display: block;
        }
    </style>
@endsection
