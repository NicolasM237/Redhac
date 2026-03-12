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
                        <h4 class="card-title">Liste des modes de collecte</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-group">
                            <div class="input-group mb-3 input-primary">
                                <input type="text" class="form-control" placeholder="Entrer la recherche" name="search">
                                <div class="input-group-append">
                                    <span class="input-group-text">Rechercher</span>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">

                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th style="width:80px;"><b>#</b></th>
                                        <th><b>NATURE</b></th>
                                        <th><b>MODE DE COLLECTE</b></th>
                                        <th><b>QUANTITE</b></th>
                                        <th><b>Date d'enregistrement</b></th>
                                        <th><b>ACTIONS</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($collectes as $index => $collecte)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $collecte->nature->nom ?? '-' }}</td>
                                            <td>{{ $collecte->nom }}</td>
                                            <td>{{ $collecte->quantite }}</td>
                                            <td>{{ $collecte->date_collecte }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        {{-- Modifier --}}
                                                        <a class="dropdown-item btnEditCollecte" href="javascript:void(0)"
                                                            data-toggle="modal" data-target=".bd-example-modal-lgMC"
                                                            data-collecte='{{ json_encode($collecte) }}'>
                                                            Modifier
                                                        </a>

                                                        {{-- Supprimer --}}
                                                        <form action="{{ route('delete.collecte', $collecte->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Voulez-vous vraiment supprimer cette collecte ?')">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-danger">Aucune donnée trouvée
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                    <form action="{{ route('collectes.store') }}" method="POST" #naturescasForm="ngForm">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Natures de cas</label>
                                <select class="form-control" name="nature_id" required>
                                    <option disabled selected>-- Choisir une nature --</option>
                                    @foreach ($natures as $nature)
                                        <option value="{{ $nature->id }}">{{ $nature->nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Mode de collecte</label>
                                <input type="text" name="nom" class="form-control" placeholder="Entrer votre nom"
                                    required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Quantite de la collecte</label>
                                <input type="number" name="quantite" class="form-control"
                                    placeholder="Entrer votre quantite" required min="0">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Date de collecte</label>
                                <input type="date" name="date_collecte" class="form-control" required>
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
    <div class="modal fade bd-example-modal-lgMC" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
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
                    <form method="post" action="{{ route('update.collecte') }}" enctype="multipart/form-data">
                        @csrf
                        <div class=" row gutters">
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">Nature de cas</label>
                                    <select class="form-control" name="nature_id" required>
                                        @foreach ($natures as $nature)
                                            <option value="{{ $nature->id }}">{{ $nature->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">Mode de collecte</label>
                                    <input type="text" class="form-control" id="inputName" name="nom" required>
                                    <input type="hidden" class="form-control" id="inputName" name="id" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">Quantite</label>
                                    <input type="text" class="form-control" id="inputName" name="quantite" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">Date de collecte</label>
                                    <input type="date" class="form-control" id="inputName" name="date_collecte"
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
