@extends('layouts.admin')
@section('title','Edit Profile')
@section('content')

<style type="text/css">
    #upload-demo{
        width: 250px;
        height: 250px;
        padding-bottom:25px;
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Edit Profile</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit Profile</li>
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

                        <form class="custom-validation" action="{{ route('admin.updateProfile') }}" method="post" id="adminProfile">
                            @csrf
                            
                            <div class="form-group mb-3">
                                <label for="name">Name<span style="color:red;">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{ $profile->name }}" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email<span style="color:red;">*</span></label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email" value="{{ $profile->email }}" required autocomplete="off" />
                            </div>
                            
                            <!-- <div class="form-group mb-3">
                                <label for="email">Mobile Number<span style="color:red;">*</span></label>
                                <input type="text" class="form-control numeric" maxlength="10" minlength="10" name="mobile_number" placeholder="Enter Mobile Number" value="{{ $profile->mobile }}" required autocomplete="off" />
                            </div> -->

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