@extends('layouts.admin')
@section('title','All Scheduled Study Monitoring')
@section('content')

<div class="page-content">
    <div class="container-fluid">

       <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">All Study Schedule Tracking</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">All Study Schedule Tracking</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionExample">
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#studyCollapseFilter" aria-expanded="true" aria-controls="studyCollapseFilter">
                        Filters
                    </button>
                </h2>
                <div id="studyCollapseFilter" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form method="post" action="{{ route('admin.studyScheduleMonitoringList') }}">
                            @csrf

                            <div class="row">
                                <div class="col-lg-2">
                                    <label class="control-label">Study Status</label>
                                    <select class="form-control select2" name="study_status" style="width: 100%;">
                                        <option value="">Select Study Status</option>
                                        @if(!is_null($studyStatus))
                                            @foreach($studyStatus as $sk => $sv)
                                                <option @if($studyStatusName == $sv->study_status) selected @endif value=" {{ $sv->study_status }}">
                                                    {{ $sv->study_status }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-1" style="margin-top: 4px;">
                                    <button type="submit" class="btn btn-primary vendors save_button mt-4">Submit</button>
                                </div>
                                @if(isset($filter) && ($filter == 1))
                                    <div class="col-md-1" style="margin-top: 4px;">
                                        <a href="{{ route('admin.studyScheduleMonitoringList') }}" class="btn btn-danger mt-4 cancel_button" id="filter" name="save_and_list" value="save_and_list" style="margin-left:-10px !important;">
                                            Reset
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

       <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="datatable-buttons" class="table table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Study No</th>
                                    <th>Sponsor</th>
                                    <th>Drug</th>
                                    <th>Study Result</th>
                                    <th>Global Priority</th>
                                    <th>Status</th>
                                    <!-- <th class='notexport'>Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @if(!is_null($studies))
                                    @php $srNo = 1; @endphp
                                    @foreach($studies as $sk => $sv)
                                        @if(count($sv->schedule)>0)
                                            <tr>
                                                <td>{{ $srNo++ }}</td>
                                                <td>
                                                    {{ $sv->study_no != '' ? $sv->study_no : '---' }}
                                                </td>
                                                <td>
                                                    {{ ($sv->sponsorName != '' && $sv->sponsorName->sponsor_name != '') ? $sv->sponsorName->sponsor_name : '---' }}
                                                </td>
                                                <td>
                                                    @if(!is_null($sv->drugDetails)) 
                                                        @php $drug = ''; @endphp
                                                        @foreach($sv->drugDetails as $dk => $dv)
                                                            @if((!is_null($dv->drugName)) && (!is_null($dv->drugDosageName)) && (!is_null($dv->dosage)) && (!is_null($dv->drugUom)) && (!is_null($dv->drugType)) && ($dv->drugType->type == 'TEST'))
                                                                @php 
                                                                    $drug = $dv->drugName->drug_name.' - '.$dv->drugDosageName->para_value .' - '.$dv->dosage .''.$dv->drugUom->para_value;
                                                                @endphp
                                                            @endif    
                                                            
                                                        @endforeach
                                                        <p>{{ $drug != '' ? $drug : '---' }}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $sv->study_result != '' ? $sv->study_result : '-' }}
                                                </td>
                                                <td>
                                                    {{ $sv->global_priority_no != '' ? $sv->global_priority_no : '-' }}
                                                </td>
                                                    @if((Auth::guard('admin')->user()->role_id == 5) || (Auth::guard('admin')->user()->role_id == 6) || (Auth::guard('admin')->user()->role_id == 10) || (((Auth::guard('admin')->user()->role_id == 7) || (Auth::guard('admin')->user()->role_id == 8) || (Auth::guard('admin')->user()->role_id == 11) || (Auth::guard('admin')->user()->role_id == 12) || (Auth::guard('admin')->user()->role_id == 15)) && (Auth::guard('admin')->user()->is_hod == 1)))
                                                    <td>                                                
                                                        <span>
                                                            {{ $sv->study_status != '' ? $sv->study_status : '---' }}
                                                        </span>
                                                    </td>
                                                @else
                                                    <td>                                                
                                                        <a class="btn btn-primary waves-effect waves-light" href="{{ route('admin.studyScheduleStatus', base64_encode($sv->id)) }}" role="button" title="View Study Schedule Detail">
                                                            {{ $sv->study_status != '' ? $sv->study_status : '---' }}
                                                        </a>
                                                    </td>
                                                @endif
                                           </tr>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection