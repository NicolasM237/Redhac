@extends('layouts.app')

@section('content')
    <div class="col-xl-12 col-xxl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Modifier le signalement : {{ $violence->code }}</h4>
            </div>
            <div class="card-body">
                <div id="smartwizard" class="form-wizard order-create">
                    <ul class="nav nav-wizard">
                        <li><a class="nav-link" href="#wizard_Service"><span>1</span></a></li>
                        <li><a class="nav-link" href="#wizard_Time"><span>2</span></a></li>
                        <li><a class="nav-link" href="#wizard_Details"><span>3</span></a></li>
                        <li><a class="nav-link" href="#wizard_Payment"><span>4</span></a></li>
                    </ul>

                    <form action="{{ route('update.violences', $violence->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $violence->id }}">

                        <div class="tab-content">
                            <div id="wizard_Service" class="tab-pane" role="tabpanel">
                                <h5 class="mb-4">1. Informations personnelles de la victime</h5>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            @foreach ($statuses as $st)
                                                <option value="{{ $st }}"
                                                    {{ $violence->status == $st ? 'selected' : '' }}>{{ $st }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Contact <span class="text-danger">*</span></label>
                                        <input type="text" name="contact" class="form-control"
                                            value="{{ $violence->contact }}" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Profession <span class="text-danger">*</span></label>
                                        <input type="text" name="occupation" class="form-control"
                                            value="{{ $violence->occupation }}" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Âge <span class="text-danger">*</span></label>
                                        <input type="number" name="age" class="form-control"
                                            value="{{ $violence->age }}" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                        <select name="sexe" class="form-control" required>
                                            <option value="M" {{ $violence->sexe == 'M' ? 'selected' : '' }}>Masculin
                                            </option>
                                            <option value="F" {{ $violence->sexe == 'F' ? 'selected' : '' }}>Féminin
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                        <select name="nationalite" class="form-control" required>
                                            @foreach ($nationalites as $pays)
                                                <option value="{{ $pays }}"
                                                    {{ $violence->nationalite == $pays ? 'selected' : '' }}>
                                                    {{ $pays }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="wizard_Time" class="tab-pane" role="tabpanel">
                                <h5 class="mb-4">2. Lieu et dates</h5>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Résidence <span class="text-danger">*</span></label>
                                        <input type="text" name="residence" class="form-control"
                                            value="{{ $violence->residence }}" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Date de survenance <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="datesurvenue" class="form-control"
                                            value="{{ $violence->datesurvenue }}" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Lieu de survenance <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="lieusurvenue" class="form-control"
                                            value="{{ $violence->lieusurvenue }}" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Situation du cas <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="situation" class="form-control"
                                            value="{{ $violence->situation }}" required>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Auteurs présumés <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="auteurs" class="form-control"
                                            value="{{ $violence->auteurs }}" required>
                                    </div>
                                </div>
                            </div>

                            <div id="wizard_Details" class="tab-pane" role="tabpanel">
                                <h5 class="mb-4">3. Détails du cas</h5>
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Nature de l'événement <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="nature_id" required>
                                            @foreach ($natures as $nature)
                                                <option value="{{ $nature->id }}"
                                                    {{ $violence->nature_id == $nature->id ? 'selected' : '' }}>
                                                    {{ $nature->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Mode de collecte <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" name="collecte_id" required>
                                            @foreach ($collectes as $collecte)
                                                <option value="{{ $collecte->id }}"
                                                    {{ $violence->collecte_id == $collecte->id ? 'selected' : '' }}>
                                                    {{ $collecte->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Description du cas <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description_cas" rows="3" required>{{ $violence->description_cas }}</textarea>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Mesure prise par l'OSC <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="mesure_obc" rows="3" required>{{ $violence->mesure_obc }}</textarea>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Risque pour la victime <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="risque_victime" rows="3" required>{{ $violence->risque_victime }}</textarea>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Attente de la victime <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="attente_victime" rows="3" required>{{ $violence->attente_victime }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div id="wizard_Payment" class="tab-pane" role="tabpanel">
                                <h5 class="mb-4">4. Pièces jointes</h5>
                                <div class="row">
                                    @foreach (['fichier1', 'fichier2', 'fichier3'] as $f)
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">Fichier {{ substr($f, -1) }}</label>
                                            <input type="file" name="{{ $f }}" class="form-control">
                                            @if ($violence->$f)
                                                <small class="text-info">Actuel : {{ $violence->$f }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-info btn-lg px-5">Mettre à jour</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
