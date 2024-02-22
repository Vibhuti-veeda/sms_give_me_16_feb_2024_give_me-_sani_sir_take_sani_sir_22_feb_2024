@extends('layouts.admin')
@section('title','Add Location Master')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add Location Master</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.locationMasterList') }}">Location Master List</a></li>
                            <li class="breadcrumb-item active">Add Location Master</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.saveLocationMaster') }}" method="post" id="addLocationMaster" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Location Name<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="location_name" placeholder="Location Name" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Location Type<span class="mandatory">*</span></label>
                                <select id="location_type" name="location_type" class="form-select" required="">
                                    <option value="">Select Location Type</option>
                                    <option value="CORPORATEOFFICE">CORPORATEOFFICE</option>
                                    <option value="CRSITE">CRSITE</option>
                                    <option value="BRSITE">BRSITE</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Location Address</label>
                                <input type="text" class="form-control" name="location_address" placeholder="Location Address" autocomplete="off">
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Remark</label>
                                <input type="text" class="form-control" name="remarks" placeholder="Remark" autocomplete="off"/>
                            </div>

                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Save
                                    </button>
                                    <button type="submit" class="btn btn-secondary waves-effect waves-light mr-1" name="btn_submit" value="save_and_update">
                                        Save & Add New
                                    </button>
                                    <a href="{{ route('admin.locationMasterList') }}" class="btn btn-danger waves-effect">
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