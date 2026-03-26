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
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('messages.mobile_users_list') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form class="form-group" method="GET" action="{{ url()->current() }}">
                                    <div class="input-group mb-3 input-primary">
                                        <input type="text" class="form-control"
                                            placeholder="{{ __('messages.search_placeholder') }}" name="search"
                                            value="{{ request('search') }}">
                                        {{-- On garde le statut actuel si on recherche par texte --}}
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                        <div class="input-group-append">
                                            <button class="input-group-text" type="submit">{{ __('messages.search_button') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <form action="{{ url()->current() }}" method="GET" class="form-group">
                                    <div class="input-group mb-3 input-primary">
                                        {{-- On garde la recherche actuelle si on filtre par statut --}}
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <select name="status" class="form-control">
                                            <option value="" selected>{{ __('messages.all_status') }}</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                                {{ __('messages.active_only') }}</option>
                                            <option value="desactive"
                                                {{ request('status') == 'desactive' ? 'selected' : '' }}>{{ __('messages.inactive_only') }}
                                            </option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="input-group-text" type="submit">{{ __('messages.filter_button') ?? 'Filtrer' }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th style="width:80px;"><b>#</b></th>
                                        <th><b>{{ __('messages.last_name') ?? 'NOM' }}</b></th>
                                        <th><b>{{ __('messages.first_name') ?? 'PRENOM' }}</b></th>
                                        <th><b>{{ __('messages.phone') ?? 'TELEPHONE' }}</b></th>
                                        <th><b>{{ __('messages.status') ?? 'STATUS' }}</b></th>
                                        <th><b>{{ __('messages.actions') }}</b></th>
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
                                                @if ($mobile->active)
                                                    <span class="badge badge-success">{{ __('messages.active') }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ __('messages.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        {{ __('messages.actions') }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        @if (!$mobile->active)
                                                            <form action="{{ url('/users/' . $mobile->id . '/activate') }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="button"
                                                                    class="dropdown-item btn-confirm-action"
                                                                    data-title="{{ __('messages.confirm_activate_user_title') }}">
                                                                    <i class="fa fa-check text-success mr-2"></i> {{ __('messages.active') }}
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form
                                                                action="{{ url('/users/' . $mobile->id . '/deactivate') }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="button"
                                                                    class="dropdown-item btn-confirm-action"
                                                                    data-title="{{ __('messages.confirm_deactivate_user_title') }}">
                                                                    <i class="fa fa-times text-danger mr-2"></i> {{ __('messages.inactive') }}
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">{{ __('messages.no_user_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <small class="copyright" style="text-align:center; display: block; margin-top: 20px;">
            <p>{{ __('messages.copyright') }} <a href="/login" target="_blank">{{ __('messages.developed_by') }}</a> 2026</p>
        </small>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Configuration des notifications Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            // Affichage du message de succès venant de la session Laravel
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            // Affichage du message d'erreur venant de la session Laravel
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('messages.oops') }}',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#3085d6'
                });
            @endif

            // 2. Gestion universelle de la confirmation (Activation/Désactivation)
            document.querySelectorAll('.btn-confirm-action').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');
                    const title = this.getAttribute('data-title');

                    Swal.fire({
                        title: title,
                        text: '{{ __('messages.confirm_status_update_text') }}',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __('messages.confirm_yes') }}',
                        cancelButtonText: '{{ __('messages.confirm_cancel') }}',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
