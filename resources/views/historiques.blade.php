@extends('layouts.app')

@section('content')
    <app-dashboard>
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4 style="color: blue;">{{ __('messages.welcome_back') }}</h4>
                        <p class="mb-0">{{ __('messages.session_ready') }}</p>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('messages.history_list_title') }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('historique') }}" method="GET" class="form-group">
                                <div class="input-group mb-3 input-primary">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="{{ __('messages.search_user_placeholder') }}"
                                        value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-info">
                                            <i class="fa fa-search mr-2"></i> {{ __('messages.search_button') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                            @if (request('search'))
                                <div class="mb-3">
                                    <a href="{{ route('historique') }}" class="btn btn-light btn-xs text-danger">
                                        <i class="fa fa-times"></i> {{ __('messages.cancel_search') }}
                                    </a>
                                </div>
                            @endif

                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-striped table-hover align-middle">
                                    <thead class="thead-light" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th scope="col"><b>{{ __('messages.full_name') }}</b></th>
                                            <th scope="col"><b>{{ __('messages.email') }}</b></th>
                                            <th scope="col"><b>{{ __('messages.date_and_time') }}</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($historiques as $item)
                                            <tr>
                                                <td class="text-nowrap">
                                                    <strong>
                                                        {{ $item->user->nom ?? 'N/A' }}
                                                        {{ $item->user->prenom ?? '' }}
                                                    </strong>
                                                </td>
                                                <td>{{ $item->user->email ?? __('messages.unknown_email') }}</td>
                                                <td class="text-nowrap">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-calendar-o mr-2 text-primary"></i>
                                                        <span>{{ $item->created_at->format('d/m/Y') }}</span>
                                                        <span class="badge badge-xs light badge-primary ml-2">
                                                            {{ $item->created_at->format('H:i') }}
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4">
                                                    {{ __('messages.no_history_found') }}
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
    </app-dashboard>
@endsection
