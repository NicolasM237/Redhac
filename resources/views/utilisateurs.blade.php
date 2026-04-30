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
                        </span>{{ __('messages.add_user') }}</button>
                </ol>
            </div>
        </div>
        @if (session('success') || session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
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
                            title: 'Erreur...',
                            text: "{{ session('error') }}",
                            confirmButtonColor: '#3085d6'
                        });
                    @endif
                });
            </script>
        @endif
        <!-- row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.users_list_title') }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('viewusers') }}">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('messages.search_user_profile_placeholder') }}" name="search"
                                    value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="submit">
                                        <i class="fa fa-search"></i> {{ __('messages.search_button') }}
                                    </button>
                                    @if (request('search'))
                                        <a href="{{ route('viewusers') }}"
                                            class="btn btn-danger">{{ __('messages.clear_search') }}</a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-responsive-md">
        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
            <tr>
                <th style="width:80px;">{{ __('messages.number') }}</th>
                <th>{{ __('messages.last_name') }} & {{ __('messages.first_name') }}</th>
                <th>{{ __('messages.phone') }}</th>
                <th>{{ __('messages.email') }}</th>
                <th>{{ __('messages.profile') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    {{-- Calcul pour garder une numérotation continue entre les pages --}}
                    <td><strong>{{ $users->firstItem() + $index }}</strong></td>
                    <td>{{ $user->nom }} {{ $user->prenom }}</td>
                    <td>{{ $user->telephone ?? 'Non renseigné' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $user->profil }}</span>
                    </td>
                    <td>

                                                <div class="dropdown">

                                                    <button type="button" class="btn btn-info light btn-xs dropdown-toggle"

                                                        data-toggle="dropdown">

                                                        {{ __('messages.actions') }}

                                                    </button>

                                                    <div class="dropdown-menu dropdown-menu-right">

                                                        <a class="dropdown-item btnEditUser" href="javascript:void(0)"

                                                            data-toggle="modal" data-target=".bd-example-modal-lgMP"

                                                            data-user='{{ json_encode($user->makeHidden(['password', 'email_verified_at'])) }}'>

                                                            {{ __('messages.edit') }}

                                                        </a>



                                                        <button type="button" class="dropdown-item text-danger"

                                                            onclick="confirmDelete({{ $user->id }})">

                                                            {{ __('messages.delete') }}

                                                        </button>



                                                        <form id="delete-form-{{ $user->id }}"

                                                            action="{{ route('delete.user', $user->id) }}" method="POST"

                                                            style="display: none;">

                                                            @csrf

                                                            @method('DELETE')

                                                        </form>

                                                    </div>

                                                </div>

                                            </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ __('messages.no_user_found') }} "{{ request('search') }}"</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- AFFICHAGE DES LIENS DE PAGINATION -->
<div class="d-flex justify-content-center mt-3">
    {{ $users->links() }}
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--formulaire d'enregistrement-->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.form_create_title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('create.user') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nom"
                                    class="col-md-4 col-form-label text-md-end">{{ __('messages.name') }}</label>
                                <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror"
                                    name="nom" value="{{ old('nom') }}" required autofocus>

                                @error('nom')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="prenom"
                                    class="col-md-4 col-form-label text-md-end">{{ __('messages.surname') }}</label>
                                <input id="prenom" type="text"
                                    class="form-control @error('prenom') is-invalid @enderror" name="prenom"
                                    value="{{ old('prenom') }}" required>

                                @error('prenom')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="telephone" class="col-md-4 col-form-label text-md-end">Téléphone</label>
                                <input id="telephone" type="text"
                                    class="form-control @error('telephone') is-invalid @enderror" name="telephone"
                                    value="{{ old('telephone') }}">

                                @error('telephone')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse" class="col-md-4 col-form-label text-md-end">Adresse</label>
                                <input id="adresse" type="text"
                                    class="form-control @error('adresse') is-invalid @enderror" name="adresse"
                                    value="{{ old('adresse') }}">

                                @error('adresse')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <label for="profil" class="col-md-4 col-form-label text-md-end">Profil</label>
                                <select id="profil" class="form-control @error('profil') is-invalid @enderror"
                                    name="profil" required>
                                    <option value="">Choisir un profil</option>
                                    <option value="Super_admin">Super_Administrateur</option>
                                    <option value="Administrateur">Administrateur</option>
                                </select>

                                @error('profil')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required>

                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Mot de passe</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>

                                @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="col-md-6">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">Confirmer</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>
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

    <!--formulaire de modification-->
    <div class="modal fade bd-example-modal-lgMP" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Modification d'un Utilisateur </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('updateuser') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class=" row gutters">
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputName">Nom</label>
                                    <input type="text" class="form-control" id="inputName" name="nom" required>
                                    <input type="hidden" class="form-control" id="inputName" name="id" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputPrenom">Prenom</label>
                                    <input type="text" class="form-control" id="inputPrenom" name="prenom" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputTelephone">Telephone</label>
                                    <input type="text" class="form-control" id="inputTelephone" name="telephone"
                                        required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputEmail">Email</label>
                                    <input type="text" class="form-control" id="inputEmail" name="email" required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputAdresse">Adresse</label>
                                    <input type="text" class="form-control" id="inputAdresse" name="adresse"
                                        required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputProfil">Profil</label>
                                    <select id="inputProfil" class="form-control" name="profil" required>
                                        <option selected disabled>choisir</option>
                                        <option value="Super_admin">Super_Administrateur</option>
                                        <option value="Administrateur">Administrateur</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputPassword">Password</label>
                                    <input type="text" class="form-control" id="inputPassword" name="password"
                                        required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lglg-4 col-md-4 col-sm-4 col-12">
                                <div class="form-group">
                                    <label for="inputConfirmPassword">Confirm Password</label>
                                    <input type="text" class="form-control" id="inputConfirmPassword"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light"
                                data-dismiss="modal">{{ __('messages.button_close') }}</button>
                            <button type="submit" class="btn btn-info">{{ __('messages.button_save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Écouteur d'événement pour le clic sur le bouton "Modifier"
        document.querySelectorAll('.btnEditUser').forEach(button => {
            button.addEventListener('click', function() {
                let userData = JSON.parse(this.getAttribute(
                    'data-user')); // Récupération des données de l'utilisateur

                console.log(userData); // Debugging pour voir les données
                console.log(userData.id); // Debugging pour voir l'ID

                // Vérification si l'input est accessible
                let idInput = document.querySelector('.bd-example-modal-lgMP input[name="id"]');
                if (idInput) {
                    idInput.value = userData.id; // ID
                } else {
                    console.error("L'input ID n'a pas été trouvé.");
                }

                let userpermisInput = document.querySelector('.bd-example-modal-lgMP input[name="nom"]');
                if (userpermisInput) {
                    userpermisInput.value = userData.nom; // ID
                } else {
                    console.error("L'input permis n'a pas été trouvé.");
                }
                let userprenomInput = document.querySelector('.bd-example-modal-lgMP input[name="prenom"]');
                if (userprenomInput) {
                    userprenomInput.value = userData.prenom; // ID
                } else {
                    console.error("L'input prenom n'a pas été trouvé.");
                }
                let usertelephoneInput = document.querySelector(
                    '.bd-example-modal-lgMP input[name="telephone"]');
                if (usertelephoneInput) {
                    usertelephoneInput.value = userData.telephone; // ID
                } else {
                    console.error("L'input telephone n'a pas été trouvé.");
                }
                let useremailInput = document.querySelector('.bd-example-modal-lgMP input[name="email"]');
                if (useremailInput) {
                    useremailInput.value = userData.email; // ID
                } else {
                    console.error("L'input email n'a pas été trouvé.");
                }
                let useradresseInput = document.querySelector(
                    '.bd-example-modal-lgMP input[name="adresse"]');
                if (useradresseInput) {
                    useradresseInput.value = userData.adresse; // ID
                } else {
                    console.error("L'input adresse n'a pas été trouvé.");
                }
                let userprofilInput = document.querySelector(
                    '.bd-example-modal-lgMP select[name="profil"]');
                if (userprofilInput) {
                    userprofilInput.value = userData.profil; // ID
                } else {
                    console.error("L'input profil n'a pas été trouvé.");
                }
                let userpasswordInput = document.querySelector(
                    '.bd-example-modal-lgMP input[name="password"]');
                let userconfirmPasswordInput = document.querySelector(
                    '.bd-example-modal-lgMP input[name="confirm_password"]');
                if (userpasswordInput && userconfirmPasswordInput) {
                    userpasswordInput.value = ''; // ID
                    userconfirmPasswordInput.value = ''; // ID
                } else {
                    console.error("Les inputs password et confirm_password n'ont pas été trouvés.");
                }

            });
        });
    </script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '{{ __('messages.activity_confirm_delete_title') }}',
                text: '{{ __('messages.activity_confirm_delete_title') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: '{{ __('messages.cancel') }}',
                confirmButtonText: '{{ __('messages.yes_delete') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Soumission du formulaire correspondant à l'ID
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>

    <style>
        .table-scrollable {
            max-height: 500px;
            overflow-y: auto;
            display: block;
        }
    </style>

    <style>
        .table-scrollable {
            max-height: 500px;
            overflow-y: auto;
            display: block;
        }
    </style>
@endsection
