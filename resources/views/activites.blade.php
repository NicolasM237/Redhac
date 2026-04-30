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
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __('messages.breadcrumb_home') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.activity_history_title') }}</li>
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
                            title: '{{ __('messages.error_title') }}',
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
                        <h4 class="card-title">{{ __('messages.activity_history_title') }}</h4>
                        <p class="text-muted">{{ __('messages.activity_history_subtitle') }}</p>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('viewactivites') }}">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('messages.activity_search_placeholder') }}" name="search"
                                    value="{{ $search ?? '' }}">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="submit">
                                        <i class="fa fa-search"></i> {{ __('messages.activity_search_button') }}
                                    </button>
                                    @if ($search)
                                        <a href="{{ route('viewactivites') }}"
                                            class="btn btn-danger">{{ __('messages.activity_reset_button') }}</a>
                                    @endif
                                </div>
                            </div>
                        </form>

                       <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-responsive-md">
        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
            <tr>
                <th style="width:80px;"><b>{{ __('messages.number') }}</b></th>
                <th>{{ __('messages.activity_table_user') }}</th>
                <th>{{ __('messages.activity_table_action') }}</th>
                <th>{{ __('messages.activity_table_table') }}</th>
                <th>{{ __('messages.activity_table_description') }}</th>
                <th>{{ __('messages.activity_table_date') }}</th>
                <th>{{ __('messages.activity_table_actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activites as $activite)
                <tr>
                    {{-- Calcul de l'index correct pour la pagination --}}
                    <td><strong>{{ $loop->iteration + ($activites->currentPage() - 1) * $activites->perPage() }}</strong></td>
                    <td>
                        <strong>{{ $activite->user->nom ?? __('messages.activity_unknown_user') }}</strong>
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
                            {{ $activite->description }}
                        </div>
                    </td>
                    <td>{{ $activite->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                                {{ __('messages.actions') }}
                            </button>
                            <div class="dropdown-menu">
                                <form class="delete-activite-form" action="{{ route('delete.activite', $activite->id) }}"
                                    method="POST" data-id="{{ $activite->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="dropdown-item text-danger btn-delete-activite">
                                        {{ __('messages.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        {{ __('messages.activity_no_records') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- AJOUT : Liens de pagination --}}
<div class="d-flex justify-content-center mt-3">
    {{ $activites->links() }}
</div>

                        <!-- Pagination -->
                        @if ($activites->hasPages())
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    @if ($activites->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ __('messages.pagination_previous') }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $activites->previousPageUrl() }}">{{ __('messages.pagination_previous') }}</a>
                                        </li>
                                    @endif

                                    @foreach ($activites->getUrlRange(1, $activites->lastPage()) as $page => $url)
                                        @if ($page == $activites->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    @if ($activites->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $activites->nextPageUrl() }}">{{ __('messages.pagination_next') }}</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ __('messages.pagination_next') }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete-activite').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const form = this.closest('.delete-activite-form');

                    Swal.fire({
                        title: '{{ __('messages.activity_confirm_delete_title') }}',
                        text: '{{ __('messages.activity_confirm_delete') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: '{{ __('messages.yes_delete') }}',
                        cancelButtonText: '{{ __('messages.cancel') }}'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .table-scrollable {
            max-height: 500px;
            overflow-y: auto;
            display: block;
        }
    </style>
@endsection
