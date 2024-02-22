@extends('layouts.admin')
@section('title','Add Role')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Add Role</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.roleList') }}">Role List</a></li>
                            <li class="breadcrumb-item active">Add Role</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     
        
        <form class="custom-validation" action="{{ route('admin.saveRole') }}" method="post" id="addRole" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Role<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="role_name" placeholder="Role" autocomplete="off" required/>
                            </div>

                            <div class="form-group mb-3">
                                <label>Module Access<span class="mandatory">*</span></label>
                                <select class="select2 form-control select2-multiple role_module" multiple="multiple" name="role_modules[]" id="role_modules" data-placeholder="Select Module(s)" required >
                                    @forelse ($role_modules as $rk => $rv)
                                        @php 
                                            $module_name = ''; 
                                            $module_name = $rv->name; 
                                        @endphp
                                        <option value="{{ $rv->id }}" data-id="{{ $rv->id }}">
                                            {{ ucfirst($module_name) }}
                                        </option>
                                    @empty
                                        <option>No Data Found</option>
                                    @endforelse
                                </select>
                                <span id="role_modules_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Dashboard Access</label>
                                <select class="select2 form-control select2-multiple" multiple="multiple" name="role_dashboard_elements[]" data-placeholder="Select Dashboard Access" >
                                    @forelse ($role_dashboard_elements as $rk => $rv)
                                        <option value="{{ $rv->id }}">{{ $rv->element }}</option>
                                    @empty
                                        <option>No Data Found</option>
                                    @endforelse
                                </select>
                                <span id="role_dashboard_elements" class="role_dashboard_elements"></span>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="row module_func" wfd-id="74" style="display: none;">
                <div class="col-lg-8 offset-lg-2" wfd-id="79">
                    <div class="card" wfd-id="80">
                        <div class="card-body" wfd-id="81">
                            <h4 class="card-title">Selected Modules Access</h4> 
                            
                            <div class="table-responsive" wfd-id="82">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Module Name</th>
                                            <th>Add</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody class="module_func_body">
                                        
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Save
                                    </button>
                                    <button type="submit" class="btn btn-secondary waves-effect waves-light mr-1" name="btn_submit" value="save_and_update">
                                        Save & Add New
                                    </button>
                                    <a href="{{ route('admin.roleList') }}" class="btn btn-danger waves-effect">
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