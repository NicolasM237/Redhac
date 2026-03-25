@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4 style="color: blue;">{{ __('messages.welcome_back') }}</h4>
                    <p class="mb-0">{{ __('messages.session_ready') }}</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <a type="button" class="btn btn-rounded btn-info" href="{{ url('/addviolences') }}"><span
                            class="btn-icon-left text-info"><i class="fa fa-plus color-info"></i>
                        </span>{{ __('messages.add_button') }}</a>
                </ol>
            </div>
        </div>

        <div class="col-md-12">
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.violences_list_title') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-md-4">
                                <a href="{{ route('export.violences.excel', request()->query()) }}"
                                    class="btn btn-rounded btn-info">
                                    <span class="btn-icon-left text-info"><i class="fa fa-download"></i></span>
                                    Excel
                                </a>
                            </div>
                            <div class="col-md-4">

                                <a href="{{ route('export.violences.csv', request()->query()) }}"
                                    class="btn btn-rounded btn-primary">
                                    <span class="btn-icon-left text-primary"><i class="fa fa-download"></i></span>
                                    CSV
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('export.violences.pdf', request()->query()) }}"
                                    class="btn btn-rounded btn-secondary">
                                    <span class="btn-icon-left text-secondary">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </span>
                                    Pdf
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('view.violences') }}">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="input-group input-primary">
                                                <select name="nationalite" class="form-control"
                                                    onchange="this.form.submit()">
                                                    <option value="">-- {{ __('messages.nat') }} --</option>
                                                    @foreach ($nationalites as $pays)
                                                        <option value="{{ $pays }}"
                                                            {{ request('nationalite') == $pays ? 'selected' : '' }}>
                                                            {{ $pays }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ __('messages.nationality') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="input-group input-primary">
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
                                                    class="btn btn-danger btn-block">Effacer</a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
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
                                    <th>{{ __('messages.nature') }}</th>
                                    <th>{{ __('messages.collection_mode') }}</th>
                                    <th class="text-nowrap">{{ __('messages.occurrence_date') }}</th>
                                    <th>{{ __('messages.files') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @forelse ($violences as $violence)
                                    <tr>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    {{ __('messages.actions') }}
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item view-details" href="javascript:void(0)"
                                                        data-violences='{{ json_encode($violence) }}' data-toggle="modal"
                                                        data-target="#infosleModal">
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
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary toggle-permis" data-id="{{ $violence->id }}">
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
                                                        <a href="{{ asset('storage/' . $violence->$f) }}" target="_blank"
                                                            class="btn btn-xs btn-info mr-1"
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
                                        <td colspan="10" class="text-center text-muted">
                                            {{ __('messages.no_record_found') }}
                                        </td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
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
                    title: '{{ __('messages.error') }}',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif
        });

        function confirmDeleteViolence(id) {
            Swal.fire({
                title: '{{ __('messages.delete_violence_title') }}',
                text: '{{ __('messages.delete_violence_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('messages.delete_violence_confirm') }}',
                cancelButtonText: '{{ __('messages.delete_violence_cancel') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-violence-' + id).submit();
                }
            });
        }

        $(document).ready(function() {
            var table = $('#violences-datatable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [
                    [3, 'asc']
                ],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        text: '{{ __('messages.copy') }}'
                    },
                    {
                        extend: 'csv',
                        text: '{{ __('messages.csv') }}'
                    },
                    {
                        extend: 'excel',
                        text: '{{ __('messages.excel') }}'
                    },
                    {
                        extend: 'pdf',
                        text: '{{ __('messages.pdf') }}'
                    },
                    {
                        extend: 'print',
                        text: '{{ __('messages.print') }}'
                    }
                ],
                initComplete: function() {
                    this.api().columns([2, 3, 4, 5, 6, 7, 8]).every(function() {
                        var column = this;
                        var input = $('<input type="text" placeholder="' + column.header()
                                .textContent.trim() + '..." />')
                            .appendTo($(column.footer()).empty())
                            .on('keyup change clear', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    });
                }
            });
        });
    </script>

    <small class="copyright" style="text-align:center;">
        <p>Copyright © Designed &amp; Developed by <a href="/login" target="_blank">Univers Solutions</a> 2026</p>
    </small>
    </div>


    <!-- Modal pour afficher les détails d'une violence -->
    <div class="modal fade" id="infosleModal" tabindex="-1" role="dialog" aria-labelledby="violencesculeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="violencesculeModalLabel">{{ __('messages.violence_details_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="printableArea">
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Code:</strong>
                            <span style="color: red" id="code"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Nationalité:</strong>
                            <span style="color: red" id="nationalite"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Status:</strong>
                            <soan style="color: red" id="status"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Contact:</strong>
                            <span style="color: red" id="contact"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Occupation:</strong>
                            <span style="color: red" id="occupation"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Age:</strong>
                            <span style="color: red" id="age"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Sexe:</strong>
                            <span style="color: red" id="sexe"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Résidence:</strong>
                            <span style="color: red" id="residence"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Date survenance:</strong>
                            <span style="color: red" id="datesurvenue"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Lieu survenance:</strong>
                            <span style="color: red" id="lieusurvenue"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Situation:</strong>
                            <span style="color: red" id="situation"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Auteurs:</strong>
                            <span style="color: red" id="auteurs"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Nature:</strong>
                            <span style="color: red" id="nature"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Collecte:</strong>
                            <span style="color: red" id="collecte"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Description du cas:</strong>
                            <span style="color: red" id="description_cas"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Mesure OBC:</strong>
                            <span style="color: red" id="mesure_obc"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Risque victime:</strong>
                            <span style="color: red" id="risque_victime"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Attente victime:</strong>
                            <span style="color: red" id="attente_victime"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Fichier 1:</strong>
                            <span style="color: red" id="fichier1"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Fichier 2:</strong>
                            <span style="color: red" id="fichier2"></span>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Fichier 3:</strong>
                            <span style="color: red" id="fichier3"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"
                            data-dismiss="modal">{{ __('messages.close_button') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewDetailsButtons = document.getElementsByClassName('view-details');
            Array.from(viewDetailsButtons).forEach(function(button) {
                button.addEventListener('click', function() {
                    var violences = JSON.parse(this.getAttribute('data-violences'));
                    // Remplir les champs du modal
                    document.getElementById('code').textContent = violences.code;
                    document.getElementById('nationalite').textContent = violences.nationalite;
                    document.getElementById('status').textContent = violences.status;
                    document.getElementById('contact').textContent = violences.contact;
                    document.getElementById('occupation').textContent = violences.occupation;
                    document.getElementById('age').textContent = violences.age;
                    document.getElementById('sexe').textContent = violences.sexe;
                    document.getElementById('residence').textContent = violences.residence;
                    document.getElementById('datesurvenue').textContent = violences.datesurvenue;
                    document.getElementById('lieusurvenue').textContent = violences.lieusurvenue;
                    document.getElementById('situation').textContent = violences.situation;
                    document.getElementById('auteurs').textContent = violences.auteurs;
                    document.getElementById('nature').textContent = violences.nature?.nom || '';
                    document.getElementById('collecte').textContent = violences.collecte?.nom || '';
                    document.getElementById('description_cas').textContent = violences
                        .description_cas;
                    document.getElementById('mesure_obc').textContent = violences.mesure_obc;
                    document.getElementById('risque_victime').textContent = violences
                        .risque_victime;
                    document.getElementById('attente_victime').textContent = violences
                        .attente_victime;

                    // Gérer les fichiers
                    ['fichier1', 'fichier2', 'fichier3'].forEach(fichier => {
                        let elem = document.getElementById(fichier);
                        if (violences[fichier]) {
                            // Ajout de "violences/" dans le chemin si la base de données ne contient que le nom du fichier
                            elem.innerHTML =
                                `<a href="/storage/violences/${violences[fichier]}" target="_blank">{{ __('messages.see') }}</a>`;
                        } else {
                            elem.textContent = '{{ __('messages.no_file') }}';
                        }
                    });

                    var modal = new bootstrap.Modal(document.getElementById('infosleModal'));
                    modal.show();
                });
            });
        });
    </script>


    <script>
        document.querySelectorAll('.toggle-permis').forEach(button => {
            button.addEventListener('click', function() {

                let id = this.dataset.id;

                fetch(`/violences/${id}/toggle-permis`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.textContent = data.permis ? 'oui' : 'non';
                        }
                    });

            });
        });
    </script>
@endsection
