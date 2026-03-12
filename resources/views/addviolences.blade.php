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
                    <h4 class="card-title">Form step</h4>
                </div>
                <div class="card-body">
                    <div id="smartwizard" class="form-wizard order-create">
                        <ul class="nav nav-wizard">
                            <li><a class="nav-link" href="#wizard_Service">
                                    <span>1</span>
                                </a></li>
                            <li><a class="nav-link" href="#wizard_Time">
                                    <span>2</span>
                                </a></li>
                            <li><a class="nav-link" href="#wizard_Details">
                                    <span>3</span>
                                </a></li>
                            <li><a class="nav-link" href="#wizard_Payment">
                                    <span>4</span>
                                </a></li>
                        </ul>
                        <form action="">
                            <div class="tab-content">
                                <div id="wizard_Service" class="tab-pane" role="tabpanel">
                                    <h5>1. Informations personnelles de la victime</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required>
                                                <option disabled selected>-- Choisir --</option>
                                                <option value="Victime">Victime</option>
                                                <option value="Temoin">Temoin</option>
                                                <option value="DDH">DDH</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Contact (telephone ou email)</label>
                                            <input type="text" name="contact" class="form-control" required
                                                min="0">
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Profession*</label>
                                            <input type="text" name="occupation" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Age*</label>
                                            <input type="number" min="0" max="100" name="age"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Sexe*</label>
                                            <select name="sexe" class="form-control" required>
                                                <option disabled selected>-- Choisir --</option>
                                                <option value="M">Masculin</option>
                                                <option value="F">Féminin</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Nationalite*</label>
                                            <select name="nationalite" class="form-control" required>
                                                <option disabled selected>-- Choisir --</option>

                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="wizard_Time" class="tab-pane" role="tabpanel">
                                    <h5>2. Lieu et dates</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-2">
                                            <label>Residence (quartier,ville)*</label>
                                            <input type="text" name="residence" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Date survenance*</label>
                                            <input type="date" name="datesurvenue" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Lieu survenance*</label>
                                            <input type="text" name="lieusurvenue" class="form-control" required>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Situation du cas*</label>
                                            <input type="text" name="situation" class="form-control" required>
                                        </div>
                                        <div class="col-lg-12 mb-2">
                                            <label>Auteurs de la violation*</label>
                                            <input type="text" name="auteurs" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div id="wizard_Details" class="tab-pane" role="tabpanel">
                                    <h5>3. Détails du cas</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-2">
                                            <label>Nature de l'événement*</label>
                                            <select class="form-control" name="nature_id" required>
                                                <option disabled selected>-- Choisir --</option>

                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Mode de collecte*</label>
                                            <select class="form-control" name="collecte_id" required>
                                                <option disabled selected>-- Choisir --</option>

                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Description du cas*</label>
                                            <textarea class="form-control" name="description_cas" rows="3" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Mesure prise par l'OSC*</label>
                                            <textarea class="form-control" name="mesure_obc" rows="3" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Risque qu'en court la victime*</label>
                                            <textarea class="form-control" name="risque_victime" rows="3" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <label>Attente de la victime*</label>
                                            <textarea class="form-control" name="attente_victime" rows="3" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="wizard_Payment" class="tab-pane" role="tabpanel">
                                    <h5>4. Fichiers</h5>
                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <label>Fichier 1 (taille max 10 mo)</label>
                                            <input type="file" class="form-control"
                                                accept=".jpg,.png,.pdf,.doc,.docx">
                                        </div>
                                        <div class="col-lg-12 mb-2">
                                            <label>Fichier 2 (taille max 10 mo)</label>
                                            <input type="file" class="form-control"
                                                accept=".jpg,.png,.pdf,.doc,.docx">
                                        </div>
                                        <div class="col-lg-12 mb-2">
                                            <label>Fichier 3 (taille max 10 mo)</label>
                                            <input type="file" class="form-control"
                                                accept=".jpg,.png,.pdf,.doc,.docx">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <button type="submit" class="btn btn-success">Enregistrer</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
