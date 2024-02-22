@extends('layouts.admin')
@section('title','Edit Para Code Master')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Edit Para Code Master</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.paraCodeMasterList', base64_encode($paraCode->id)) }}">
                                    Para Code Master List
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Edit Para Code Master</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.updateParaCodeMaster') }}" method="post" id="addParaCodeMaster" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <input type="hidden" name="id" value="{{ $paraCode->id }}">
                            <input type="hidden" name="para_master_id" value="{{ base64_encode($paraCode->para_master_id) }}">

                            <div class="form-group mb-3">
                                <label>Para Value<span class="mandatory">*</span></label>
                                <input type="text" class="form-control" name="para_value" placeholder="Para Value" autocomplete="off" required value="{{ $paraCode->para_value }}" />
                            </div>

                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Update
                                    </button>
                                    
                                    <a href="{{ route('admin.paraCodeMasterList', base64_encode($paraCode->id)) }}" class="btn btn-danger waves-effect">
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
