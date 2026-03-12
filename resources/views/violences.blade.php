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
                         <a type="button" class="btn btn-rounded btn-info" href="{{ url('/addviolences') }}"><span
                            class="btn-icon-left text-info"><i class="fa fa-plus color-info"></i>
                        </span>Ajouter</a>
                    </ol>
                </div>
            </div>
       <div class="container mt-3">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Liste Des violences</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-rounded btn-primary">
                                        <span class="btn-icon-left text-primary">
                                            <i class="fa fa-download"></i>
                                        </span>
                                        CSV
                                    </button>
                                    <button type="button" class="btn btn-rounded btn-info">
                                        <span class="btn-icon-left text-info">
                                            <i class="fa fa-download"></i>
                                        </span>
                                        Excel
                                    </button>
                                    <button type="button" class="btn btn-rounded btn-secondary">
                                        <span class="btn-icon-left text-secondary">
                                            <i class="fa fa-share-alt color-secondary"></i>
                                        </span>
                                        Pdf
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <form class="form-group">
                                        <div class="input-group mb-3 input-primary">
                                            <select class="form-control" name="selectedNationalite">
                                                <option value="">-- Filtrer par nationalité --</option>
                                                <option>
                                                </option>
                                            </select>
                                            <span class="input-group-text">Nationalité</span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <form class="form-group">
                                        <div class="input-group mb-3 input-primary">
                                            <input type="text" class="form-control" placeholder="Entrer la recherche"
                                                name="searchTerm">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Rechercher</span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th><b>ACTIONS</b></th>
                                            <th>#</th>
                                            <th>Code</th>
                                            <th>Nationalité</th>
                                            <th>Status</th>
                                            <th>Contact</th>
                                            <th>Occupation</th>
                                            <th>Age</th>
                                            <th>Sexe</th>
                                            <th>Résidence</th>
                                            <th>Date survenance</th>
                                            <th>Lieu survenance</th>
                                            <th>Situation</th>
                                            <th>Auteurs</th>
                                            <th>Nature</th>
                                            <th>Collecte</th>
                                            <th>Description</th>
                                            <th>Mesure OBC</th>
                                            <th>Risque victime</th>
                                            <th>Attente victime</th>
                                            <th>Fichiers 1</th>
                                            <th>Fichiers 2</th>
                                            <th>Fichiers 3</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr *ngFor="let violence of filteredViolences(); let i = index">
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        Actions
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            data-toggle="modal" data-target=".bd-example-modal-lV">
                                                            Voir
                                                        </a>
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                        >Modifier</a>
                                                        <a class="dropdown-item" href="javascript:void(0)">Supprimer</a>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><strong></strong></td>

                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <small class="copyright" style="text-align:center;">
                <p>Copyright © Designed &amp; Developed by <a href="/login" target="_blank">Univers Solutions</a> 2026</p>
            </small>
        </div>

        <!-- Modal pour afficher les détails d'une violence -->
        <div class="modal fade bd-example-modal-lV" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails du cas</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body" *ngIf="selectedViolence">
                        <div class="row">
                            <!-- Colonne 1 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Status:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Contact:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Occupation:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Âge:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Sexe:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Date de survenue:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                            </div>

                            <!-- Colonne 2 -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Nationalité:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Résidence:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Mesure OBC:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Attente victime:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Description:</strong></label>
                                    <textarea class="form-control" rows="3" readonly></textarea>
                                </div>


                            </div>

                            <!-- Colonne 3 -->
                            <div class="col-md-4">

                                <div class="mb-3">
                                    <label class="form-label"><strong>Lieu de survenue:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Situation:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Auteurs:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Nature:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Collecte:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Risque victime:</strong></label>
                                    <input type="text" class="form-control" readonly>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger light" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

    @endsection
