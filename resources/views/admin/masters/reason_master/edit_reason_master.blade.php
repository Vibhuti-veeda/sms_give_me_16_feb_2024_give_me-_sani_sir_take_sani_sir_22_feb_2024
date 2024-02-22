@extends('layouts.admin')
@section('title','Edit Reason Master')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Edit Reason Master</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.reasonMasterList') }}">Reason Master List</a></li>
                            <li class="breadcrumb-item active">Edit Reason Master</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>     

        <form class="custom-validation" action="{{ route('admin.updateReasonMaster')}}" method="post" id="addReasonMaster" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <span style="color:red; float:right;" class="pull-right">* is mandatory</span>
                            </div>

                            <input type="hidden" name="id" id="id" value="{{ $reasonMaster->id }}">

                            <div class="form-group mb-3">
                                <label>Activity Type<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectActivityType" name="activity_type_id" id="activity_type_id" data-placeholder="Select Activity Type">
                                    <option value="">Select Activity Type</option>
                                    @if(!is_null($activityTypes))
                                        @foreach($activityTypes as $atp)
                                            <option @if($reasonMaster->activity_type_id == $atp->id) selected @endif value="{{ $atp->id }}">{{ $atp->para_value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectActivityType"></span>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Activity<span class="mandatory">*</span></label>
                                <select class="form-control select2 selectActivity" name="activity_id" id="activity_id" data-placeholder="Select Activity" required>
                                    <option value="">Select Activity</option>
                                    @if(!is_null($activities))
                                        @foreach($activities as $ak)
                                            <option @if($reasonMaster->activity_id == $ak->id) selected @endif value="{{ $ak->id }}">{{ $ak->activity_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span id="selectActivity"></span>
                            </div>

                            <div class="form-group mb-3">
                                <label>Start Delay Remark</label>
                                <textarea class="form-control" name="start_delay_remark" placeholder="Start Delay Remark" autocomplete="off" rows="1" cols="1">{{ $reasonMaster->start_delay_remark }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                               <label>End Delay Remark</label>
                                <textarea class="form-control" name="end_delay_remark" placeholder="End Delay Remark" autocomplete="off" rows="1" cols="1">{{ $reasonMaster->end_delay_remark }}
                                </textarea>
                            </div>
                            
                            <div class="button-items">
                                <center>
                                   <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.reasonMasterList') }}" class="btn btn-danger waves-effect">
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