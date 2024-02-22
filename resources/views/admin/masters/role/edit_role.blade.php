@extends('layouts.admin')
@section('title','Edit Role')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Edit Role</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.roleList') }}">Role List</a></li>
                            <li class="breadcrumb-item active">Edit Role</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     
        
        <form class="custom-validation" action="{{ route('admin.updateRole') }}" method="post" id="addRole" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="role_id" id="role_id" value="{{$data->id}}">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>
                            <div class="form-group mb-3">
                                <label>Role<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="role_name" placeholder="Role" autocomplete="off" required/ value="{{$data->name}}">
                            </div>

                            <div class="form-group mb-3">
                                <label class="control-label">Module Access<span class="mandatory">*</span></label>
                                <select class="select2 form-control select2-multiple role_module" multiple="multiple" name="role_modules[]" id="role_modules" data-placeholder="Select Module(s)" required >
                                    @forelse ($role_modules as $rk => $rv)
                                        @php $module_name = ''; $module_name =$rv->name; @endphp
                                        <option value="{{$rv->id}}" @if(in_array($rv->id,$module_id)) selected @endif>
                                            {{ ucfirst($module_name) }}
                                        </option>
                                    @empty
                                        <option>No Data Found</option>
                                    @endforelse
                                </select>
                                <span id="role_modules_error"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label class="control-label">Role Dashboard Elements</label>
                                <select class="select2 form-control select2-multiple" multiple="multiple" name="role_dashboard_elements[]" data-placeholder="Select Dashboard Access" >
                                    @forelse ($role_dashboard_elements as $rk => $rv)
                                        <option value="{{$rv->id}}" @if(in_array($rv->id,$elements_id)) selected @endif>{{$rv->element}}</option>
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

            @if($module_id != '')
                <div class="row module_func" wfd-id="74">
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
                                            @php $i=1; $j=1; @endphp
                                            @foreach($module_access as $mk => $mv)
                                                @php $module_name = ''; $module_name =$mv->module_name; @endphp
                                                <tr>
                                                    <th scope="row">{{$j}}</th>
                                                    <td>{{ ucfirst($module_name) }}</td>
                                                    <td>
                                                        <div class="form-check mb-3" wfd-id="141"><input class="form-check-input add_func add_value" type="checkbox" data-id="{{$mv->module_id->id}}" value="{{$mv->module_id->id}}" @if($mv->add == 1) checked @endif name="add[{{$mv->module_id->id}}]" id="add_{{$mv->module_id->id}}" wfd-id="365" @if($mv->module_name == 'policy') checked disabled @endif></div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check mb-3" wfd-id="141"><input class="form-check-input add_func edit_value" type="checkbox" data-id="{{$mv->module_id->id}}" value="{{$mv->module_id->id}}" @if($mv->edit == 1) checked @endif name="edit[{{$mv->module_id->id}}]" id="edit_{{$mv->module_id->id}}" wfd-id="365" @if($mv->module_name == 'policy') checked disabled @endif></div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check mb-3" wfd-id="141"><input class="form-check-input add_func delete_value" type="checkbox" data-id="{{$mv->module_id->id}}" value="{{$mv->module_id->id}}" @if($mv->delete == 1) checked @endif name="delete[{{$mv->module_id->id}}]" id="delete_{{$mv->module_id->id}}" wfd-id="365" @if($mv->module_name == 'policy') checked disabled @endif></div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check mb-3" wfd-id="141"><input disabled class="form-check-input view_value" data-id="{{$mv->module_id->id}}" value="{{$mv->module_id->id}}" type="checkbox" checked name="view[{{$mv->module_id->id}}]" id="view_{{$mv->module_id->id}}" wfd-id="365"></div>
                                                    </td>
                                                </tr>
                                                @php $i++; $j++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <div>
                                    <center>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                            Update
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
            </div>

        </form>
    </div>
</div>

@endsection