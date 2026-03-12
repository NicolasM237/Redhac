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
                                        <th>Utilisateur</th>
                                        <th>Action</th>
                                        <th>Table</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activites as $activite)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $activite->user->nom ?? 'Utilisateur inconnu' }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $badgeClass = match ($activite->action_type) {
                                                        'Création' => 'badge-success',
                                                        'Modification' => 'badge-warning',
                                                        'Suppression' => 'badge-danger',
                                                        default => 'badge-info',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ $activite->action_type }}
                                                </span>
                                            </td>
                                            <td><span class="text-muted">{{ $activite->table_name }}</span></td>
                                            <td style="max-width: 300px;">
                                                <div class="text-wrap">
                                                    {{ $act->description }}
                                                </div>
                                            </td>
                                            <td>{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-dark dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <form action="{{ route('delete.activite', $activite->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Supprimer cet historique ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item text-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Aucune activité enregistrée pour le
                                                moment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center">
                                {{ $activites->links() }}
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
@endsection
