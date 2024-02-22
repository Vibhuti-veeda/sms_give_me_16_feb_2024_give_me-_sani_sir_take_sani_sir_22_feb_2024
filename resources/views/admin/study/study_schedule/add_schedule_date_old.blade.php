@extends('layouts.admin')
@section('title','Add Study Schedule Date')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">
                        Add Study Schedule Date: 
                        <span style="color: blue;">
                            {{ $studyNo->study_no }} 

                            @if(!is_null($studyNo->drugDetails)) 
                                @php $drug = ''; @endphp
                                @foreach($studyNo->drugDetails as $dk => $dv)
                                    @if(!is_null($dv->drugName) && !is_null($dv->drugDosageName) && !is_null($dv->dosage) && !is_null($dv->drugUom) && !is_null($dv->drugType) && $dv->drugType->type == 'TEST')
                                        @php 
                                            $drug = $dv->drugName->drug_name;
                                        @endphp
                                    @endif    
                                    
                                @endforeach

                                ({{ $drug != '' ? $drug : '---' }} - {{ (!is_null($studyNo->sponsorName) && ($studyNo->sponsorName->sponsor_name != '')) ? $studyNo->sponsorName->sponsor_name : '' }} - {{ (!is_null($studyNo->projectManager) && ($studyNo->projectManager->name != '')) ? $studyNo->projectManager->name : '' }})
                                
                            @endif
                        </span>
                    </h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.studyScheduleList') }}">
                                    ALL SCHEDULED STUDY
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Add Study Schedule Date</li>
                        </ol>
                    </div>                    
                </div>
            </div>
        </div>

        <form class="custom-validation" action="{{ route('admin.saveStudyScheduleDate') }}" method="post" id="addStudySchedule" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- <div class="form-group">
                                <span style="color:red;float:right;" class="pull-right">* is mandatory</span>
                            </div> -->

                            <div class="form-group mb-3">
                                @php $crName = array(); @endphp
                                @if(!is_null($crActivitySchedule))
                                    @foreach($crActivitySchedule as $cak => $cav)
                                        @if((!is_null($cav->crActivity) && ($cav->crActivity->para_value != '') && ($cav->crActivity->para_value == 'CR')))
                                            @php $crName = $cav->crActivity->para_value; @endphp
                                        @endif
                                    @endforeach
                                @endif
                                
                                @if($crName == "CR")
                                    <center>
                                        <h5>
                                            <b>
                                                CR Activities List
                                            </b>
                                        </h5>
                                    </center><br>

                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>
                                                    Sr. No
                                                </th>
                                                <th>
                                                    Activity Name
                                                </th>
                                                <th>
                                                    Activity Sequence No
                                                </th>
                                                <th>
                                                    GN
                                                </th>
                                                <th>
                                                    PN
                                                </th>
                                                <th>
                                                    Required Days
                                                </th>
                                                <th>
                                                    Start Date
                                                </th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!is_null($crActivitySchedule))
                                                @foreach($crActivitySchedule as $sk => $sv)
                                                    @if(!is_null($sv->crActivity))
                                                        <tr>

                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $sv->activity_name }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="activity_sequence[{{ $sv->id }}][sequence]" value="{{ $sv->activity_sequence_no }}">
                                                            </td>
                                                            <td>
                                                                {{ $sv->group_no }}
                                                            </td>
                                                            <td>
                                                                {{ $sv->period_no }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="required_days[{{ $sv->id }}][days]" value="{{ $sv->require_days }}">
                                                            </td>
                                                            <!-- <td>
                                                                <input type="text" class="form-control" name="schedule_date[{{ $sv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $sv->id }}" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" value="{{ $sv->scheduled_start_date != '' ? date('d-m-Y', strtotime($sv->scheduled_start_date)) : '' }}" />
                                                            </td> -->
                                                            <td>
                                                                <input type="text" class="form-control @if($sv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $sv->id }}" name="schedule_date[{{ $sv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $sv->id }}" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-type="{{ $sv->activity_type }}" value="{{ $sv->scheduled_start_date != '' ? date('d-m-Y', strtotime($sv->scheduled_start_date)) : '' }}" />
                                                            </td>
                                                            @if($sv->scheduled_start_date != '')
                                                                <td>
                                                                    <a class="btn btn-primary waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-id="{{ $sv->id }}" data-type="{{ $sv->activity_type }}" data-toggle="modal" data-target="#openStudyDetailsModal" href="Javascript:void(0)">
                                                                        Update Schedule
                                                                    </a>
                                                                </td>
                                                            @else
                                                                <td>---</td>
                                                            @endif
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                @endif
                            </div>

                            
                            <div class="form-group mb-3">
                                @php $brName = array(); @endphp
                                @if(!is_null($brActivitySchedule))
                                    @foreach($brActivitySchedule as $brk => $brv)
                                        @if((!is_null($brv->brActivity) && ($brv->brActivity->para_value != '') && ($brv->brActivity->para_value == 'BR')))
                                            @php $brName = $brv->brActivity->para_value; @endphp
                                        @endif
                                    @endforeach
                                @endif

                                @if($brName == 'BR')
                                    <center>
                                        <h5>
                                            <b>
                                                BR Activities List
                                            </b>
                                        </h5>
                                    </center><br>
                                
                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>
                                                    Sr. No
                                                </th>
                                                <th>
                                                    Activity Name
                                                </th>
                                                <th>
                                                    Activity Sequence No
                                                </th>
                                                <th>
                                                    GN
                                                </th>
                                                <th>
                                                    PN
                                                </th>
                                                <th>
                                                    Required Days
                                                </th>
                                                <th>
                                                    Start Date
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!is_null($brActivitySchedule))
                                                @foreach($brActivitySchedule as $bk => $bv)
                                                    @if(!is_null($bv->brActivity))
                                                        <tr>
                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $bv->activity_name }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="activity_sequence[{{ $bv->id }}][sequence]" value="{{ $bv->activity_sequence_no }}">
                                                            </td>
                                                            <td>
                                                                {{ $bv->group_no }}
                                                            </td>
                                                            <td>
                                                                {{ $bv->period_no }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="required_days[{{ $bv->id }}][days]" value="{{ $bv->require_days }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control @if($bv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $bv->id }}" name="schedule_date[{{ $bv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $bv->id }}" data-acitivity="{{ $bv->id }}" data-study="{{ $bv->study_id }}" data-sequence="{{ $bv->activity_sequence_no }}" data-type="{{ $bv->activity_type }}" value="{{ $bv->scheduled_start_date != '' ? date('d-m-Y', strtotime($bv->scheduled_start_date)) : '' }}" />
                                                            </td>
                                                            @if($bv->scheduled_start_date != '')
                                                                <td>
                                                                    <a class="btn btn-primary waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $bv->id }}" data-study="{{ $bv->study_id }}" data-sequence="{{ $bv->activity_sequence_no }}" data-id="{{ $bv->id }}" data-type="{{ $bv->activity_type }}" data-toggle="modal" data-target="#openStudyDetailsModal" href="Javascript:void(0)">
                                                                        Update Schedule
                                                                    </a>
                                                                </td>
                                                            @else
                                                                <td>---</td>
                                                            @endif
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                @endif
                            </div>

                            <div class="form-group mb-3">

                                @php $pbName = array(); @endphp
                                @if(!is_null($pbActivitySchedule))
                                    @foreach($pbActivitySchedule as $pbk => $pbv)
                                        @if((!is_null($pbv->pbActivity) && ($pbv->pbActivity->para_value != '') && ($pbv->pbActivity->para_value == 'PB')))
                                            @php $pbName = $pbv->pbActivity->para_value; @endphp
                                        @endif
                                    @endforeach
                                @endif

                                @if($pbName == 'PB')
                                    <center>
                                        <h5>
                                            <b>
                                                PB Activities List
                                            </b>
                                        </h5>
                                    </center><br>

                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>
                                                    Sr. No
                                                </th>
                                                <th>
                                                    Activity Name
                                                </th>
                                                <th>
                                                    Activity Sequence No
                                                </th>
                                                <th>
                                                    GN
                                                </th>
                                                <th>
                                                    PN
                                                </th>
                                                <th>
                                                    Required Days
                                                </th>
                                                <th>
                                                    Start Date
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!is_null($pbActivitySchedule))
                                                @foreach($pbActivitySchedule as $pk => $pv)
                                                    @if(!is_null($pv->pbActivity))
                                                        <tr>

                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $pv->activity_name }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="activity_sequence[{{ $pv->id }}][sequence]" value="{{ $pv->activity_sequence_no }}">
                                                            </td>
                                                            <td>
                                                                {{ $pv->group_no }}
                                                            </td>
                                                            <td>
                                                                {{ $pv->period_no }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="required_days[{{ $pv->id }}][days]" value="{{ $pv->require_days }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control @if($pv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $pv->id }}" name="schedule_date[{{ $pv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $pv->id }}" data-acitivity="{{ $pv->id }}" data-study="{{ $pv->study_id }}" data-sequence="{{ $pv->activity_sequence_no }}" data-type="{{ $pv->activity_type }}" value="{{ $pv->scheduled_start_date != '' ? date('d-m-Y', strtotime($pv->scheduled_start_date)) : '' }}" />
                                                            </td>
                                                            @if($pv->scheduled_start_date != '')
                                                                <td>
                                                                    <a class="btn btn-primary waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $pv->id }}" data-study="{{ $pv->study_id }}" data-sequence="{{ $pv->activity_sequence_no }}" data-id="{{ $pv->id }}" data-type="{{ $pv->activity_type }}" data-toggle="modal" data-target="#openStudyDetailsModal" href="Javascript:void(0)">
                                                                        Update Schedule
                                                                    </a>
                                                                </td>
                                                            @else
                                                                <td>---</td>
                                                            @endif
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            
                            <div class="form-group mb-3">

                                @php $rwName = array(); @endphp
                                @if(!is_null($rwActivitySchedule))
                                    @foreach($rwActivitySchedule as $brk => $brv)
                                        @if((!is_null($brv->rwActivity) && ($brv->rwActivity->para_value != '') && ($brv->rwActivity->para_value == 'RW')))
                                            @php $rwName = $brv->rwActivity->para_value; @endphp
                                        @endif
                                    @endforeach
                                @endif

                                @if($rwName == 'RW')
                                    <center>
                                        <h5>
                                            <b>
                                                RW Activities List
                                            </b>
                                        </h5>
                                    </center><br>

                                    <table id="study-schedule" class="table table-striped table-bordered nowrap" >
                                        <thead>
                                            <tr>
                                                <th>
                                                    Sr. No
                                                </th>
                                                <th>
                                                    Activity Name
                                                </th>
                                                <th>
                                                    Activity Sequence No
                                                </th>
                                                <th>
                                                    GN
                                                </th>
                                                <th>
                                                    PN
                                                </th>
                                                <th>
                                                    Required Days
                                                </th>
                                                <th>
                                                    Start Date
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!is_null($rwActivitySchedule))
                                                @foreach($rwActivitySchedule as $sk => $sv)
                                                    @if(!is_null($sv->rwActivity))
                                                        <tr>

                                                            <td>
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td>
                                                                {{ $sv->activity_name }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="activity_sequence[{{ $sv->id }}][sequence]" value="{{ $sv->activity_sequence_no }}">
                                                            </td>
                                                            <td>
                                                                {{ $sv->group_no }}
                                                            </td>
                                                            <td>
                                                                {{ $sv->period_no }}
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" name="required_days[{{ $sv->id }}][days]" value="{{ $sv->require_days }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control @if($sv->scheduled_start_date == '') ? scheduleDate : '' @endif scheduleDate_{{ $sv->id }}" name="schedule_date[{{ $sv->id }}][date]" placeholder="dd/mm/yyyy" data-provide="datepickerStyle" data-date-autoclose="true" data-date-format="dd-mm-yyyy" autocomplete="off" data-id="{{ $sv->id }}" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-type="{{ $sv->activity_type }}" value="{{ $sv->scheduled_start_date != '' ? date('d-m-Y', strtotime($sv->scheduled_start_date)) : '' }}" />
                                                            </td>
                                                            @if($sv->scheduled_start_date != '')
                                                                <td>
                                                                    <a class="btn btn-primary waves-effect waves-light calculateDates"  role="button" title="Update Schedule" data-acitivity="{{ $sv->id }}" data-study="{{ $sv->study_id }}" data-sequence="{{ $sv->activity_sequence_no }}" data-id="{{ $sv->id }}" data-type="{{ $sv->activity_type }}" data-toggle="modal" data-target="#openStudyDetailsModal" href="Javascript:void(0)">
                                                                        Update Schedule
                                                                    </a>
                                                                </td>
                                                            @else
                                                                <td>---</td>
                                                            @endif
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="button-items">
                                <center>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light mr-1" name="btn_submit" value="save">
                                        Save
                                    </button>
                                    <a href="{{ route('admin.studyScheduleList') }}" class="btn btn-danger waves-effect">
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