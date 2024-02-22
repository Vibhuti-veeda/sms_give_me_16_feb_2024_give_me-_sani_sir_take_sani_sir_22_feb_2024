@extends('layouts.login')
@section('title','Login')
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
                                    <h5 class="text-primary">Welcome</h5>
                                    <p>Sign in to continue to <br> Veeda Clinical Research Limited</p>
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

                            <center>
                                <h5 style="color: #556ee6;">Study Management System</h5>
                            </center>
                        </div>

                        <div class="p-2">
                            <form class="form-horizontal" action="{{ route('admin.postlogin') }}" id="loginForm" method="post">
                                @csrf
                                
                                <div class="form-group">
                                    <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">User Name<span class="mandatory red">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Enter user name" autofocus="autofocus" required>
                                    <!-- <input type="hidden" class="form-control" name="email" id="username" placeholder="Enter email" autocomplete="off" value="admin@admin.com"> -->
                                </div>
        
                                <div class="mb-3">
                                    <label class="form-label">Password<span class="mandatory red">*</span></label>
                                    <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password" autocomplete="off" required>
                                    <!-- <input type="hidden" class="form-control" name="password" id="userpassword" placeholder="Enter password" autocomplete="off" value="admin123"> -->
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                </div>
                                <!-- <div class="mt-4 text-center">
                                    <a href="{{ route('admin.auth.password.reset') }}" class="text-muted">
                                        <i class="mdi mdi-lock me-1"></i> 
                                        Forgot your password?
                                    </a>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection