@extends('layouts.app')

@section('content')
        <!-- row -->
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('messages.declare_case_title') }}</h4>
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
                                    <h5 class="mb-4">{{ __('messages.step1_personal_info') }}</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control default-select" required>
                                                <option value="" disabled selected>{{ __('messages.choose_status') }}</option>
                                                <option value="Victime">{{ __('messages.victim') }}</option>
                                                <option value="Temoin">{{ __('messages.witness') }}</option>
                                                <option value="DDH">{{ __('messages.human_rights_defender') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.contact') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="contact" class="form-control"
                                                pattern="^[0-9+@.\s-]+$" placeholder="{{ __('messages.phone_email_placeholder') }}" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.occupation') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="occupation" class="form-control" placeholder="{{ __('messages.profession_placeholder') }}" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.age') }} <span class="text-danger">*</span></label>
                                            <input type="number" min="0" max="120" name="age"
                                                class="form-control" placeholder="{{ __('messages.age_placeholder') }}" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.sex') }} <span class="text-danger">*</span></label>
                                            <select name="sexe" class="form-control default-select" required>
                                                <option  disabled selected>{{ __('messages.choose_gender') }}</option>
                                                <option value="M">{{ __('messages.masculine') }}</option>
                                                <option value="F">{{ __('messages.feminine') }}</option>
                                                <option value="A">{{ __('messages.other') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.nationality') }} <span class="text-danger">*</span></label>
                                            <select name="nationalite" class="form-control" required>
                                                <option value="" disabled selected>{{ __('messages.choose_nationality') }}</option>
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
                                    <h5 class="mb-4">{{ __('messages.step2_location_dates') }}</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.residence') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="residence" class="form-control" placeholder="{{ __('messages.residence_placeholder') }}" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.occurrence_date') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="datesurvenue" class="form-control" placeholder="{{ __('messages.occurrence_date_placeholder') }}" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.occurrence_location') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="lieusurvenue" class="form-control" placeholder="{{ __('messages.occurrence_location_placeholder') }}" required>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.case_situation') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="situation" class="form-control"
                                                placeholder="{{ __('messages.case_situation_placeholder') }}" required>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label class="form-label">{{ __('messages.presumed_perpetrators') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="auteurs" class="form-control" placeholder="{{ __('messages.perpetrators_placeholder') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div id="wizard_Details" class="tab-pane" role="tabpanel">
                                    <h5 class="mb-4">{{ __('messages.step3_case_details') }}</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.event_nature') }} <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" name="nature_id" required>
                                                <option disabled selected>{{ __('messages.choose_nature') }}</option>
                                                @foreach ($natures as $nature)
                                                    <option value="{{ $nature->id }}">{{ $nature->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.collection_method') }} <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" name="collecte_id" required>
                                                <option disabled selected>{{ __('messages.choose_collection_method') }}</option>
                                                @foreach ($collectes as $collecte)
                                                    <option value="{{ $collecte->id }}">{{ $collecte->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.case_description') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="description_cas" rows="4" placeholder="{{ __('messages.case_description_placeholder') }}" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.measures_taken') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="mesure_obc" rows="4" placeholder="{{ __('messages.measures_placeholder') }}" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.victim_risks') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="risque_victime" rows="4" placeholder="{{ __('messages.victim_risks_placeholder') }}" required></textarea>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">{{ __('messages.victim_expectations') }} <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" name="attente_victime" rows="4" placeholder="{{ __('messages.victim_expectations_placeholder') }}" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div id="wizard_Payment" class="tab-pane" role="tabpanel">
                                    <h5 class="mb-4">{{ __('messages.step4_attachments') }}</h5>
                                    <div class="row">
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">{{ __('messages.file1') }}</label>
                                            <input type="file" name="fichier1" class="form-control"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <small class="text-muted">{{ __('messages.max_size') }}</small>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">{{ __('messages.file2') }}</label>
                                            <input type="file" name="fichier2" class="form-control"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <small class="text-muted">{{ __('messages.max_size') }}</small>
                                        </div>
                                        <div class="col-lg-4 mb-3">
                                            <label class="form-label">{{ __('messages.file3') }}</label>
                                            <input type="file" name="fichier3" class="form-control"
                                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <small class="text-muted">{{ __('messages.max_size') }}</small>
                                        </div>
                                    </div>

                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-info btn-lg px-5">{{ __('messages.save_button') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
 @endsection
