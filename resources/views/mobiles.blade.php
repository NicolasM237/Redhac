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
                        <form class="form-group" method="GET" action="{{ url()->current() }}">
                            <div class="input-group mb-3 input-primary">
                                <input type="text" class="form-control"
                                    placeholder="Rechercher par nom, prénom ou téléphone..." name="search"
                                    value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="input-group-text" type="submit">Rechercher</button>
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
                                        <th><b>ACTIONS</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mobiles as $key => $mobile)
                                        <tr>
                                            <td><strong>{{ $key + 1 }}</strong></td>
                                            <td>{{ $mobile->nom }}</td>
                                            <td>{{ $mobile->prenom }}</td>
                                            <td>{{ $mobile->telephone }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-info dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <form action="{{ route('users.destroy', $mobile->id) }}"
                                                            method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="dropdown-item text-danger btn-delete">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun utilisateur trouvé pour
                                                "{{ request('search') }}".</td>
                                        </tr>
                                    @endforelse
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
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-form');

                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Cette action est irréversible !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
