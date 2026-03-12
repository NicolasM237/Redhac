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
                            <form method="GET" action="{{ route('view.violences') }}" class="form-group  ">
                                <div class="row">

                                    <!-- Filtre par nationalité -->
                                    <div class="col-md-6">
                                        <div class="input-group   input-primary">
                                            <select name="nationalite" class="form-control" onchange="this.form.submit()">
                                                <option value="">-- Tous les pays --</option>
                                                @foreach ($nationalites as $pays)
                                                    <option value="{{ $pays }}"
                                                        {{ request('nationalite') == $pays ? 'selected' : '' }}>
                                                        {{ $pays }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-text">Nationalité</span>
                                        </div>
                                    </div>
                                    <!-- Recherche par code -->
                                    <div class="col-md-6">
                                        <div class="input-group   input-primary">
                                            <input type="text" class="form-control" placeholder="Entrer le code"
                                                name="searchTerm" value="{{ request('searchTerm') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Rechercher</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                                    @foreach ($violences as $index => $violence)
                                        <tr>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item  view-details"
                                                            data-violences='{{ json_encode($violence) }}'
                                                            data-toggle="modal" data-target="#infosleModal"
                                                            title="voir les infos petite section"><i class="icon-eye"></i>
                                                            Voir</a>

                                                        <a href="{{ route('edit.violences', $violence->id) }}"
                                                            class="dropdown-item">
                                                            Modifier
                                                        </a>
                                                        <a class="dropdown-item" href="#">Supprimer</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $violence->code }}</td>
                                            <td>{{ $violence->nationalite }}</td>
                                            <td>{{ $violence->status }}</td>
                                            <td>{{ $violence->contact }}</td>
                                            <td>{{ $violence->occupation }}</td>
                                            <td>{{ $violence->age }}</td>
                                            <td>{{ $violence->sexe }}</td>
                                            <td>{{ $violence->residence }}</td>
                                            <td>{{ $violence->datesurvenue }}</td>
                                            <td>{{ $violence->lieusurvenue }}</td>
                                            <td>{{ $violence->situation }}</td>
                                            <td>{{ $violence->auteurs }}</td>
                                            <td>{{ $violence->nature->nom ?? '' }}</td>
                                            <td>{{ $violence->collecte->nom ?? '' }}</td>
                                            <td>{{ $violence->description_cas }}</td>
                                            <td>{{ $violence->mesure_obc }}</td>
                                            <td>{{ $violence->risque_victime }}</td>
                                            <td>{{ $violence->attente_victime }}</td>
                                            <td>
                                                @if ($violence->fichier1)
                                                    <a href="{{ asset('storage/' . $violence->fichier1) }}"
                                                        target="_blank">Voir</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($violence->fichier2)
                                                    <a href="{{ asset('storage/' . $violence->fichier2) }}"
                                                        target="_blank">Voir</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($violence->fichier3)
                                                    <a href="{{ asset('storage/' . $violence->fichier3) }}"
                                                        target="_blank">Voir</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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
    <div class="modal fade" id="infosleModal" tabindex="-1" role="dialog" aria-labelledby="violencesculeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="violencesculeModalLabel">Informations sur La declaration</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="printableArea">
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Code:</strong>
                            <p id="code"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Nationalité:</strong>
                            <p id="nationalite"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Status:</strong>
                            <p id="status"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Contact:</strong>
                            <p id="contact"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Occupation:</strong>
                            <p id="occupation"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Age:</strong>
                            <p id="age"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Sexe:</strong>
                            <p id="sexe"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Résidence:</strong>
                            <p id="residence"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Date survenance:</strong>
                            <p id="datesurvenue"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Lieu survenance:</strong>
                            <p id="lieusurvenue"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Situation:</strong>
                            <p id="situation"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Auteurs:</strong>
                            <p id="auteurs"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Nature:</strong>
                            <p id="nature"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Collecte:</strong>
                            <p id="collecte"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Description du cas:</strong>
                            <p id="description_cas"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Mesure OBC:</strong>
                            <p id="mesure_obc"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Risque victime:</strong>
                            <p id="risque_victime"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Attente victime:</strong>
                            <p id="attente_victime"></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4  ">
                            <strong>Fichier 1:</strong>
                            <p id="fichier1"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Fichier 2:</strong>
                            <p id="fichier2"></p>
                        </div>
                        <div class="col-md-4  ">
                            <strong>Fichier 3:</strong>
                            <p id="fichier3"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-dark" id="printModal"
                            onclick="printModalContent()">Imprimer</button>
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
                                elem.innerHTML =
                                    `<a href="/storage/${violences[fichier]}" target="_blank">Voir</a>`;
                            } else {
                                elem.textContent = '';
                            }
                        });

                        var modal = new bootstrap.Modal(document.getElementById('infosleModal'));
                        modal.show();
                    });
                });
            });
        </script>

    @endsection
