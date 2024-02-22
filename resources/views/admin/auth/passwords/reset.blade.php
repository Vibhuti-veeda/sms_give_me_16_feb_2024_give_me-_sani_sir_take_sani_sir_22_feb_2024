@extends('layouts.login')
@section('title','Admin Reset Password')
@section('content')
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-8">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p>Sign in to continue to <br> Veeda Clinical Research Limited.</p>
                                </div>
                            </div>
                            <div class="col-4 align-self-end">
                                <img src="{{ asset('images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0"> 
                        <div class="auth-logo">
                            <a href="{{ route('admin.login') }}" class="auth-logo-light">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{{ asset('images/logo/veeda-favicon.png') }}" alt="" class="rounded-circle" height="34">
                                    </span>
                                </div>
                            </a>

                            <a href="{{ route('admin.login') }}" class="auth-logo-dark">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{{ asset('images/logo/veeda-favicon.png') }}" alt="" class="rounded-circle" height="50">
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="p-2">
                            <form class="form-horizontal" action="{{ route('admin.resetpassword') }}" id="resetPassword" autocomplete="off" method="post">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">
                                
                                <div class="form-group">
                                    <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                                </div>

                                <div class="form-group hide" style="display: none;">
                                    <label for="username">Email<span class="mandatory">*</span></label>
                                    <input type="text" name="email" class="form-control" id="username" placeholder="Enter email" value="{{ $email }}">
                                </div>

                                <div class="mb-3">
                                    <label for="userpassword">New Password<span class="mandatory red">*</span></label>
                                    <input type="password" name="password" class="form-control pr-password" id="password" placeholder="Enter password" autocomplete="off" required>
                                </div>
        
                                <div class="mb-3">
                                     <label for="userpassword">Comfirm Password<span class="mandatory red">*</span></label>
                                    <input type="password" class="form-control" id="userpassword" placeholder="Enter comfirm password" name="password_confirmation" autocomplete="off" required>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Reset Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
