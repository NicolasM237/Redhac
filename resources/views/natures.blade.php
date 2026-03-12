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
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste des natures des cas</h4>
                    </div>
                    <div class="card-body">
                        <!-- Champ de recherche -->
                        <form class="form-group" action="{{ route('view.natures') }}" method="GET">
                            <div class="input-group mb-3 input-primary">
                                <input type="text" class="form-control" placeholder="Entrer la recherche" name="search"
                                    value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="input-group-text btn btn-primary" type="submit">Rechercher</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            @if ($natures->isEmpty())
                                <div class="alert alert-warning">
                                    Aucun utilisateur trouvé pour votre recherche.
                                </div>
                            @endif

                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th style="width:80px;"><b>#</b></th>
                                        <th><b>NOM</b></th>
                                        <th><b>Date d'enregistrement</b></th>
                                        <th><b>ACTIONS</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($natures as $index => $nature)
                                        <tr>
                                            <td><strong>{{ $index + 1 }}</strong></td>
                                            <td>{{ $nature->nom }}</td>
                                            <td>{{ $nature->created_at }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-info dropdown-toggle"
                                                        data-toggle="dropdown">Actions</button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item btnEditNature" href="javascript:void(0)"
                                                            data-toggle="modal" data-target=".bd-example-modal-lgMN"
                                                            data-nature='{{ json_encode($nature) }}'>
                                                            Modifier
                                                        </a>
                                                        <form method="POST"
                                                            action="{{ route('delete.natures', $nature->id) }}"
                                                            class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-danger">
                                                Aucun utilisateur trouvé.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
