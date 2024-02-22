@extends('layouts.utility')
@section('title','500 | Internal Server Error')
@section('content')
<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center mb-5">
                    <!-- <h1 class="display-2 font-weight-medium">5<i class="bx bx-buoy bx-spin text-primary display-3"></i>0</h1> -->
                    <h4 class="text-uppercase" style="color: red;">Site is Under Maintenance <br /> Please check back in sometime</h4>
                    <!-- <div class="mt-5 text-center">
                        <a class="btn btn-primary waves-effect waves-light" href="">Back to Dashboard</a>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 col-xl-6">
                <div>
                    <img src="{{ asset('images/maintenance.svg') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>  
@endsection