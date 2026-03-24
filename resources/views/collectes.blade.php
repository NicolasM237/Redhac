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
                    <button type="button" class="btn btn-rounded btn-info" data-toggle="modal"
                        data-target=".bd-example-modal-lg"><span class="btn-icon-left text-info"><i
                                class="fa fa-plus color-info"></i>
                        </span>{{ __('messages.add_button') }}</button>
                </ol>
            </div>
        </div>

        <div class="col-md-12">
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.collecte_list_title') }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('view.collectes') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('messages.search_collecte_placeholder') }}" name="search"
                                    value="{{ $search ?? '' }}">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="submit">
                                        <i class="fa fa-search"></i> {{ __('messages.search_button') }}
                                    </button>
                                    @if (isset($search) && $search)
                                        <a href="{{ route('view.collectes') }}" class="btn btn-danger">{{ __('messages.clear_button') }}</a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">

                            <table id="collectes-datatable" class="table table-responsive-md table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:80px;">#</th>
                                        <th>{{ __('messages.nature_name') }}</th>
                                        <th>{{ __('messages.collecte_mode') }}</th>
                                        <th>{{ __('messages.quantity') }}</th>
                                        <th>{{ __('messages.created_at') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
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
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @forelse ($collectes as $collecte)
                                        <tr>
                                            <td><strong>{{ $loop->iteration + ($collectes->currentPage() - 1) * $collectes->perPage() }}</strong>
                                            </td>
                                            <td>{{ $collecte->nature->nom ?? 'N/A' }}</td>
                                            <td>{{ $collecte->nom }}</td>
                                            <td><span class="text-nowrap">{{ $collecte->quantite }}</span></td>
                                            <td>{{ \Carbon\Carbon::parse($collecte->date_collecte)->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-info light btn-xs dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        {{ __('messages.actions') }}
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item btnEditCollecte" href="javascript:void(0)"
                                                            data-toggle="modal" data-target=".bd-example-modal-lgMC"
                                                            data-collecte='{{ json_encode($collecte) }}'>
                                                            {{ __('messages.edit') }}
                                                        </a>

                                                        <div class="dropdown-divider"></div>

                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDeleteCollecte({{ $collecte->id }})">
                                                            {{ __('messages.delete') }}
                                                        </button>

                                                        <form id="delete-form-collecte-{{ $collecte->id }}"
                                                            action="{{ route('delete.collecte', $collecte->id) }}"
                                                            method="POST" style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                {{ __('messages.no_collecte_found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $collectes->appends(['search' => $search])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Configuration du Toast
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                // Notifications de session
                @if (session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#3085d6'
                    });
                @endif
            });

            // Fonction de confirmation de suppression
            function confirmDeleteCollecte(id) {
                Swal.fire({
                    title: '{{ __('messages.delete_collecte_title') }}',
                    text: '{{ __('messages.delete_collecte_text') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ __('messages.delete_collecte_confirm') }}',
                    cancelButtonText: '{{ __('messages.delete_collecte_cancel') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-collecte-' + id).submit();
                    }
                });
            }
        </script>

    </div>

    <small class="copyright" style="text-align:center;">
        <p>Copyright © Designed &amp; Developed by <a href="/login" target="_blank">Univers Solutions</a> 2026</p>
    </small>
    </div>

    <!--formulaire d'enregistrement-->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.form_collecte_title_create') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('collectes.store') }}" method="POST" #naturescasForm="ngForm">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>{{ __('messages.natures_of_case') }}</label>
                                <select class="form-control" name="nature_id" required>
                                    <option disabled selected>{{ __('messages.choose_nature') }}</option>
                                    @foreach ($natures as $nature)
                                        <option value="{{ $nature->id }}">{{ $nature->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('messages.collecte_mode') }}</label>
                                <input type="text" name="nom" class="form-control" placeholder="{{ __('messages.enter_name') }}"
                                    required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('messages.quantity_collecte') }}</label>
                                <input type="number" name="quantite" class="form-control"
                                    placeholder="{{ __('messages.enter_quantity') }}" required min="0">
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('messages.collecte_date') }}</label>
                                <input type="date" name="date_collecte" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-dismiss="modal">{{ __('messages.button_close') }}</button>
                            <button type="submit" class="btn btn-info">{{ __('messages.button_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--formulaire de modification-->
    <div class="modal fade bd-example-modal-lgMC" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">{{ __('messages.form_collecte_title_edit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('update.collecte') }}" enctype="multipart/form-data">
                        @csrf
                        <div class=" row gutters">
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">{{ __('messages.natures_of_case') }}</label>
                                    <select class="form-control" name="nature_id" required>
                                        @foreach ($natures as $nature)
                                            <option value="{{ $nature->id }}">{{ $nature->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">{{ __('messages.collecte_mode') }}</label>
                                    <input type="text" class="form-control" id="inputName" name="nom" required>
                                    <input type="hidden" class="form-control" id="inputName" name="id" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">{{ __('messages.quantity') }}</label>
                                    <input type="text" class="form-control" id="inputName" name="quantite" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">{{ __('messages.collecte_date') }}</label>
                                    <input type="date" class="form-control" id="inputName" name="date_collecte"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.button_close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('messages.button_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                // Écouteur d'événement pour le clic sur le bouton "Modifier"
                document.querySelectorAll('.btnEditCollecte').forEach(button => {
                    button.addEventListener('click', function() {
                        let collecteData = JSON.parse(this.getAttribute('data-collecte')); // CORRECT

                        // Remplir le champ hidden ID
                        let idInput = document.querySelector('.bd-example-modal-lgMC input[name="id"]');
                        if (idInput) idInput.value = collecteData.id;

                        // Remplir le champ nom
                        let nomInput = document.querySelector('.bd-example-modal-lgMC input[name="nom"]');
                        if (nomInput) nomInput.value = collecteData.nom;

                        let quantiteInput = document.querySelector('.bd-example-modal-lgMC input[name="quantite"]');
                        if (quantiteInput) quantiteInput.value = collecteData.quantite;

                        let dateCollecteInput = document.querySelector(
                            '.bd-example-modal-lgMC input[name="date_collecte"]');
                        if (dateCollecteInput) dateCollecteInput.value = collecteData.date_collecte;
                    });
                });
            </script>
        @endsection
