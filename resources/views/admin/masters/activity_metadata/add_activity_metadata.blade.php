@extends('layouts.admin')
@section('title','Add Activity Metadata')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add Activity Metadata</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.activityMetadataList') }}">
                                    Activity Metadata List
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                Add Activity Metadata
                            </li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>

        <form class="custom-validation" action="{{ route('admin.saveActivityMetadata') }}" method="post" id="addActivityMetadata" name="addActivityMetadata" enctype='multipart/form-data'>
            @csrf

            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Activity<span class="mandatory">*</span></label>
                                <select class="form-control select2" name="activity_id" id="activity_id" required>
                                    <option value="">Select activity</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak => $av)
                                            <option value="{{ $av->id }}">
                                                {{ $av->activity_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="activityError"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Control<span class="mandatory">*</span></label>
                                <select class="form-control select2" name="control_id" id="control_id" required>
                                    <option value="">Select control</option>
                                    @if(!is_null($controls))
                                        @foreach($controls as $ck => $cv)
                                            <option value="{{ $cv->id }}" data-control-name="{{ $cv->control_name }}" data-control-type="{{ $cv->control_type }}">
                                                {{ $cv->control_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="controlError"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Source Question<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="source_question" id="source_question" placeholder="Enter source question" autocomplete="off" maxlength="250" required/>
                            </div>

                            <div class="form-group mb-3 row">
                                <div class="col-md-12">
                                    <label class="col-md-2">Is Required<span class="mandatory">*</span></label>
                                    <div class="form-check form-check-inline col-md-1">
                                        <input class="form-check-input mandatory" type="radio" name="is_mandatory" id="is_mandatory" value="yes">
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline col-md-1">
                                        <input class="form-check-input mandatory" type="radio" name="is_mandatory" id="is_mandatory" value="no">
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>    
                                </div>
                                <span id="isMandatoryError"></span>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Activity For<span class="mandatory">*</span></label>
                                <select class="form-control select2" name="is_activity" id="is_activity" required>
                                    <option value="">Select activity for</option>
                                    <option value="S">Actual start</option>
                                    <option value="E">Actual end</option>
                                </select>
                                <span id="activityForError"></span>
                            </div>

                            <!-- <div class="form-group mb-3">
                                <label>Data Validation<span class="mandatory">*</span></label>
                                <select class="form-control select2" name="input_validation" id="input_validation" required>
                                    <option value="">Select data validation</option>
                                    <option value="NONE">None</option>
                                    <option value="NUMERIC">Number</option>
                                    <option value="DECIMAL">Decimal</option>
                                    <option value="EMAIL">Email</option>
                                    <option value="DATE">Date</option>
                                    <option value="ALPHANUMERIC">Alpha Numeric</option>
                                </select>
                                <span id="dataValidationError"></span>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="col-6 hideColumn" style="display: none;">
                    <div class="card">
                        <div class="card-body newInputs">
                            <div class="form-group mb-3">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" id="btn_submit" value="save">
                                        Save
                                    </button>
                                    <button type="submit" class="btn btn-secondary waves-effect waves-light mr-1" name="btn_submit" id="btn_submit" value="save_and_new">
                                        Save & Add New
                                    </button>
                                    <a href="{{ route('admin.activityMetadataList') }}" class="btn btn-danger waves-effect">
                                        Cancel
                                    </a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection