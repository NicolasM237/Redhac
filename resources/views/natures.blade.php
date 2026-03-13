@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4 style="color: blue;">Hello,Bon retour!</h4>
                    <p class="mb-0">Votre session de travail est prete</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <button type="button" class="btn btn-rounded btn-info" data-toggle="modal"
                        data-target=".bd-example-modal-lg"><span class="btn-icon-left text-info"><i
                                class="fa fa-plus color-info"></i>
                        </span>Ajouter</button>
                </ol>
            </div>
        </div>
        <div class="col-md-12">
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste des natures des cas</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('view.natures') }}" method="GET">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Rechercher une nature..."
                                    name="search" value="{{ $search }}">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="submit">
                                        <i class="fa fa-search"></i> Rechercher
                                    </button>
                                    @if ($search)
                                        <a href="{{ route('view.natures') }}" class="btn btn-danger">Effacer</a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th style="width:80px;">#</th>
                                        <th>NOM</th>
                                        <th>Date d'enregistrement</th>
                                        <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($natures as $index => $nature)
                                        <tr>
                                            <td><strong>{{ $loop->iteration }}</strong></td>
                                            <td>{{ $nature->nom }}</td>
                                            <td>{{ $nature->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-info light btn-xs dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item btnEditNature" href="javascript:void(0)"
                                                            data-toggle="modal" data-target=".bd-example-modal-lgMN"
                                                            data-nature='{{ json_encode($nature) }}'>
                                                            Modifier
                                                        </a>

                                                        <button type="button" class="dropdown-item text-danger"
                                                            onclick="confirmDeleteNature({{ $nature->id }})">
                                                            Supprimer
                                                        </button>

                                                        <form id="delete-form-nature-{{ $nature->id }}" method="POST"
                                                            action="{{ route('delete.natures', $nature->id) }}"
                                                            style="display:none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-danger">
                                                Aucune nature trouvée.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $natures->appends(['search' => $search])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <small class="copyright" style="text-align:center; width: 100%;">
                <p>Copyright © Designed &amp; Developed by <a href="/login" target="_blank">Univers Solutions</a> 2026</p>
            </small>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Gestion des notifications de session
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
                        title: 'Oups...',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#3085d6'
                    });
                @endif
            });

            // 2. Fonction de confirmation de suppression
            function confirmDeleteNature(id) {
                Swal.fire({
                    title: 'Supprimer cette nature ?',
                    text: "Cette action peut impacter les cas liés à cette nature.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-nature-' + id).submit();
                    }
                });
            }
        </script>

        <!--formulaire d'enregistrement-->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Formulaire d'enregistrement</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('create.natures') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="col-md-4 col-form-label text-md-end">Nature de cas</label>
                                    <input id="nom" type="text"
                                        class="form-control @error('nom') is-invalid @enderror" name="nom"
                                        value="{{ old('nom') }}" required autofocus>

                                    @error('nom')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--formulaire de modification-->
        <div class="modal fade bd-example-modal-lgMN" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Modification d'une Nature </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('update.nature') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class=" row gutters">
                                <div class="col-xl-12 col-lglg-4 col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="inputName">Nature de cas</label>
                                        <input type="text" class="form-control" id="inputName" name="nom"
                                            required>
                                        <input type="hidden" class="form-control" id="inputName" name="id"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <script>
                    // Écouteur d'événement pour le clic sur le bouton "Modifier"
                    document.querySelectorAll('.btnEditNature').forEach(button => {
                        button.addEventListener('click', function() {
                            let natureData = JSON.parse(this.getAttribute(
                                'data-nature')); // Récupération des données de l'utilisateur

                            console.log(natureData); // Debugging pour voir les données
                            console.log(natureData.id); // Debugging pour voir l'ID

                            // Vérification si l'input est accessible
                            let idInput = document.querySelector('.bd-example-modal-lgMN input[name="id"]');
                            if (idInput) {
                                idInput.value = natureData.id; // ID
                            } else {
                                console.error("L'input ID n'a pas été trouvé.");
                            }

                            let naturepermisInput = document.querySelector('.bd-example-modal-lgMN input[name="nom"]');
                            if (naturepermisInput) {
                                naturepermisInput.value = natureData.nom; // ID
                            } else {
                                console.error("L'input Nature n'a pas été trouvé.");
                            }

                        });
                    });
                </script>
            @endsection
