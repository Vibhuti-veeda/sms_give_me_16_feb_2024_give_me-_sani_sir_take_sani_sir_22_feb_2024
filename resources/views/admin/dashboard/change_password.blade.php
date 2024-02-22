@extends('layouts.admin')
@section('title','Change Password')
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Change Password</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Change Password</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-body">
                    	
                        <div class="form-group">
                            <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                        </div>
                        
                        <form class="custom-validation" action="{{ route('admin.updateAdminPassword') }}" method="post" id="changePassword">
                        	@csrf
                            <div class="form-group mb-3">
                                <label>Current Password<span class="mandatory">*</span></label>
                                <input type="password" class="form-control" name="old_password" required placeholder="Current Password"/>
                            </div>

                            <div class="form-group mb-3">
                                <label>New Password<span class="mandatory">*</span></label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="New Password" id="inputNewPassword" />
                            </div>

                            <div class="form-group mb-3">
                                <label>Confirm New Password<span class="mandatory">*</span></label>
                                <input type="password" class="form-control" name="confirm_password" required placeholder="Confirm New Password"/>
                            </div>

                            <div class="form-group mb-0">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger waves-effect">
                                        Cancel
                                    </a>
                                </center>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection