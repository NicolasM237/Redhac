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
        </div>

        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste Des Utilisateurs Mobiles</h4>
                    </div>
                    <div class="card-body">
                        <form class="form-group">
                            <div class="input-group mb-3 input-primary">
                                <input type="text" class="form-control" placeholder="Entrer la recherche"
                                     name="search">
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
                                        <th><b>NOM</b></th>
                                        <th><b>PRENOM</b></th>
                                        <th><b>TELEPHONE</b></th>
                                        <th><b>SEXE</b></th>
                                        <th><b>ACTIONS</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr >
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-info dropdown-toggle"
                                                    data-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu">
                                      
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

        <small class="copyright" style="text-align:center;">
            <p>Copyright © Designed &amp; Developed by <a href="/login" target="_blank">Univers Solutions</a> 2026</p>
        </small>
    </div>

@endsection