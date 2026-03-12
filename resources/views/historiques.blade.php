@extends('layouts.app')

@section('content')

<app-dashboard>
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                  <div class="welcome-text">
                    <h4 style="color: blue;">Hello,Bon retour!</h4>
                    <p class="mb-0">Votre session de travail est prete</p>
                </div>
            </div>
        </div>
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste des historiques de connexion</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-group">
                            <div class="input-group mb-3 input-primary">
                                <input type="text" class="form-control" placeholder="Entrer la recherche"
                                   >
                                <div class="input-group-append">
                                    <span class="input-group-text">Rechercher</span>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th><b>Nom et Prénom</b></th>
                                        <th><b>Email</b></th>
                                        <th><b>Date et Heure</b></th>
                                        <th><b>Actions</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr >
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                       >
                                                        Supprimer
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</app-dashboard>

@endsection