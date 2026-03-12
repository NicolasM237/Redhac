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
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Signalement d'un cas</h4>
                </div>
                <div class="card-body">
                    <div id="smartwizard" class="form-wizard order-create">
                        <ul class="nav nav-wizard">
                            <li><a class="nav-link" href="#wizard_Service"><span>1</span></a></li>
                            <li><a class="nav-link" href="#wizard_Time"><span>2</span></a></li>
                            <li><a class="nav-link" href="#wizard_Details"><span>3</span></a></li>
                            <li><a class="nav-link" href="#wizard_Payment"><span>4</span></a></li>
                        </ul>
                        <form action="{{ route('create.violences') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content">

                                <div id="wizard_Service" class="tab-pane" role="tabpanel">
                                    <h5 class="mb-4">1. Informations personnelles de la victime</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control default-select" required>
                                                <option value="" disabled selected>-- Choisir --</option>
                                                <option value="Victime">Victime</option>
                                                <option value="Temoin">Témoin</option>
                                                <option value="DDH">DDH</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Contact (Téléphone ou Email) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="contact" class="form-control"
                                                pattern="^[0-9+@.\s-]+$" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Profession <span class="text-danger">*</span></label>
                                            <input type="text" name="occupation" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Âge <span class="text-danger">*</span></label>
                                            <input type="number" min="0" max="120" name="age"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                            <select name="sexe" class="form-control default-select" required>
                                                <option value="" disabled selected>-- Choisir --</option>
                                                <option value="M">Masculin</option>
                                                <option value="F">Féminin</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                            <select name="nationalite" class="form-control" required>
                                                <option value="" disabled selected>-- Choisir --</option>
                                                @foreach ($nationalites as $pays)
                                                    <option value="{{ $pays }}"
                                                        {{ old('nationalite') == $pays ? 'selected' : '' }}>
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
                                            <label class="form-label">Résidence (Quartier, Ville) <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="residence" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Date de survenance <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="datesurvenue" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Lieu de survenance <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="lieusurvenue" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Situation du cas <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="situation" class="form-control"
                                                placeholder="Ex: En cours, Résolu..." required>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label">Auteurs présumés de la violation <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="auteurs" class="form-control" required>
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
                                                <option disabled selected>-- Choisir une nature --</option>
                                                @foreach ($natures as $nature)
                                                    <option value="{{ $nature->id }}">{{ $nature->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Mode de collecte <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" name="collecte_id" required>
                                                <option disabled selected>-- Choisir une mode de collecte --</option>
                                                @foreach ($collectes as $collecte)
                                                    <option value="{{ $collecte->id }}">{{ $collecte->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Description du cas <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="description_cas" rows="4" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Mesure prise par l'OSC <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="mesure_obc" rows="4" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Risques encourus par la victime <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="risque_victime" rows="4" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Attentes de la victime <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="attente_victime" rows="4" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="wizard_Payment" class="tab-pane" role="tabpanel">
                                    <h5 class="mb-4">4. Pièces jointes</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">Fichier 1</label>
                                            <input type="file" name="fichier1" class="form-control"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <small class="text-muted">Max 10 Mo</small>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">Fichier 2</label>
                                            <input type="file" name="fichier2" class="form-control"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <small class="text-muted">Max 10 Mo</small>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">Fichier 3</label>
                                            <input type="file" name="fichier3" class="form-control"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <small class="text-muted">Max 10 Mo</small>
                                        </div>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-info btn-lg px-5">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
