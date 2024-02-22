@extends('layouts.admin')
@section('title','Add Sponsor Master')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add Sponsor Master</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.sponsorMasterList') }}">Sponsor Master List</a></li>
                            <li class="breadcrumb-item active">Add Sponsor Master</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.saveSponsorMaster') }}" method="post" id="addSponsorMaster">
            @csrf
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sponsor Name<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="sponsor_name" placeholder="Sponsor Name" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sponsor Address</label>
                                <textarea type="text" name="sponsor_address" id="sponsor_address" class="form-control" placeholder="Sponsor Address" autocomplete="off"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sponsor Type</label>
                                <select class="form-control select2 sponsorType" name="sponsor_type" data-placeholder="Select Sponsor Type">
                                    <option value="">Select Sponsor Type</option>
                                    <option value="DOMESTIC">Domestic</option>
                                    <option value="GLOBAL">Global</option>
                                </select>
                                <!-- <span id="sponsorType"></span> -->
                            </div>

                            <div class="form-group mb-3">
                                <label>Contact Person 1</label>
                                <input type="text" class="form-control" name="contact_person_1" placeholder="Contact Person 1" autocomplete="off"/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Contact Mobile 1</label>
                                <input type="text" class="form-control numeric" name="contact_mobile_1" placeholder="Contact Mobile 1" autocomplete="off" maxlength="10" minlength="10" />
                            </div>

                            <div class="form-group mb-3">
                                <label>Contact Email 1</label>
                                <input type="text" class="form-control" name="contact_email_1" placeholder="Contact Email 1" autocomplete="off"/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Contact Person 2</label>
                                <input type="text" class="form-control" name="contact_person_2" placeholder="Contact Person 2" autocomplete="off"/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Contact Mobile 2</label>
                                <input type="text" class="form-control numeric" name="contact_mobile_2" placeholder="Contact Mobile 2" autocomplete="off" maxlength="10" minlength="10" />
                            </div>

                            <div class="form-group mb-3">
                                <label>Contact Email 2</label>
                                <input type="text" class="form-control" name="contact_email_2" placeholder="Contact Email 2" autocomplete="off" />
                            </div>

                            <div class="form-group mb-3">
                                <label>Landline No</label>
                                <input type="text" class="form-control" name="landline_no" placeholder="Landline No" autocomplete="off" />
                            </div>

                            <div class="form-group mb-3">
                                <label>Remarks</label>
                                <textarea type="text" name="remarks" id="remarks" class="form-control" placeholder="Remarks" autocomplete="off"></textarea>
                            </div>

                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Save
                                    </button>
                                    <button type="submit" class="btn btn-secondary waves-effect waves-light mr-1" name="btn_submit" value="save_and_update">
                                        Save & Add New
                                    </button>
                                    <a href="{{ route('admin.sponsorMasterList') }}" class="btn btn-danger waves-effect">
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
