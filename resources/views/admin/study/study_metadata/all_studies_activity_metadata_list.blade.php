@extends('layouts.admin')
@section('title','All Activity Metadata List')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">All Activity Metadata List</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                All Activity Metadata List
                            </li>
                        </ol>
                    </div>                    
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionExample">
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button fw-medium @if(isset($filter) && ($filter == 1)) @else collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#activityMetadataCollapseFilter" aria-expanded="false" aria-controls="activityMetadataCollapseFilter">
                        Filters
                    </button>
                </h2>
                <div id="activityMetadataCollapseFilter" class="accordion-collapse @if(isset($filter) && ($filter == 1)) @else collapse @endif" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form method="post" action="{{ route('admin.allStudiesActivityMetadataList') }}" name="allActivityMetadataFilter" id="allActivityMetadataFilter">
                            @csrf

                            <div class="row">

                                <div class="col-md-3">
                                    <label class="control-label">Study No</label>
                                    <select class="form-control select2" name="study_no" id="study_no" style="width: 100%;">
                                        <option value="">Select Study No</option>
                                        @if (!is_null($studyNo))
                                            @foreach ($studyNo as $snk => $snv)
                                                @if ((!is_null($snv->studySchedule)) && (!is_null($snv->studySchedule->studyNo)) && (!is_null($snv->studySchedule->studyNo->study_no)))
                                                    <option value="{{ $snv->studySchedule->studyNo->id }}" {{ ((($studyId != '') && ($snv->studySchedule->studyNo->id == $studyId)) ? 'selected' : '') }} >{{ $snv->studySchedule->studyNo->study_no }}</option>
                                                @endif  
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="control-label">Activity Name</label>
                                    <select class="form-control select2" name="activity_name" id="activity_name" style="width: 100%;">
                                        <option value="">Select Activity Name</option>
                                        @if (!is_null($activityNames))
                                            @foreach ($activityNames as $ank => $anv)
                                                @if ((!is_null($anv->studySchedule)) && (!is_null($anv->studySchedule->activityMaster)) && (!is_null($anv->studySchedule->activityMaster->activity_name)))
                                                    <option value="{{ $anv->studySchedule->activityMaster->id }}" {{ ((($activityId != '') && ($anv->studySchedule->activityMaster->id == $activityId)) ? 'selected' : '') }} >{{ $anv->studySchedule->activityMaster->activity_name }}</option>
                                                @endif  
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="control-label">Date Range</label>
                                    <div>
                                        <div class="input-daterange input-group" data-date-format="dd/mm/yyyy" data-date-autoclose="true" data-provide="datepickerStyle" autocomplete="off">
                                            <input type="text" class="form-control datepickerStyle" name="from_date" value="{{ $fromDate }}" autocomplete="off" placeholder="From Date">
                                            <input type="text" class="form-control datepickerStyle" name="to_date" value="{{ $toDate }}" autocomplete="off" placeholder="To Date">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-1 pt-1">
                                    <button type="submit" class="btn btn-primary vendors save_button mt-4">Submit</button>
                                </div>
                                @if(isset($filter) && ($filter == 1))
                                    <div class="col-md-1 pt-1 ps-5">
                                        <a href="{{ route('admin.allStudiesActivityMetadataList') }}" class="btn btn-danger mt-4 cancel_button" id="filter" name="reset" value="reset">
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
                        <div style="margin-top: -40px; transform: translate(137px, 51px); width: fit-content;">
                            <a href="{{ route('admin.exportStudyMetaData')}}" class="btn btn-secondary">Export</a>    
                        </div>
                        <table id="tableList" class="table table-striped table-bordered tableList-search" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Study No</th>
                                    <th>Period</th>
                                    <th>Activity Name</th>
                                    <th>Title</th>
                                    <th>Output Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!is_null($allActivityMetadataList))
                                    @php
                                        $count = (($offset == 0) ? 1 : $offset+1); 
                                    @endphp
                                    @foreach($allActivityMetadataList as $aamlk => $aamlv)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>
                                                {{ ( ( (!is_null($aamlv->studySchedule)) && (!is_null($aamlv->studySchedule->studyNo)) ) && ($aamlv->studySchedule->studyNo->study_no != '') ) ? $aamlv->studySchedule->studyNo->study_no : '---'   }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($aamlv->studySchedule)) && ($aamlv->studySchedule->period_no != '') ) ? $aamlv->studySchedule->period_no : '---'   }}
                                            </td>
                                            <td>
                                                {{ ( ( (!is_null($aamlv->activityMetadata)) && (!is_null($aamlv->activityMetadata->activityName)) ) && ($aamlv->activityMetadata->activityName->activity_name != '') ) ? $aamlv->activityMetadata->activityName->activity_name : '---'   }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($aamlv->activityMetadata)) && ($aamlv->activityMetadata->source_question != '')) ? $aamlv->activityMetadata->source_question : '---' }}
                                            </td>
                                            <td>
                                                {{ ($aamlv->actual_value != '') ? $aamlv->actual_value : '---' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div class="mt-2">
                            Showing {{ $offset + 1 }} to {{ min($page * $perPage, $recordCount) }} of {{ $recordCount }} entries
                        </div>
                        <div style="float:right;">
                            @if ($pageCount >= 1)
                                <nav aria-label="...">
                                    <ul class="pagination">
                                        <li class="page-item {{ ($page == 1) ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ route('admin.allStudiesActivityMetadataList', ['page' => base64_encode(1)]) }}">First</a>
                                        </li>
                                        <li class="page-item {{ ($page == 1) ? 'disabled' : '' }}">
                                            <a class="page-link h5" href="{{ route('admin.allStudiesActivityMetadataList', ['page' => base64_encode($page - 1)]) }}">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        @for ($i = max(1, $page); $i <= min($page + 4, $pageCount); $i++)
                                            <li class="page-item {{($page == $i) ? 'active' : '' }}" aria-current="page">
                                                <a class="page-link" href="{{ route('admin.allStudiesActivityMetadataList', ['page' => base64_encode($i)]) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link h5" href="{{ route('admin.allStudiesActivityMetadataList', ['page' => base64_encode($page + 1)]) }}">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ route('admin.allStudiesActivityMetadataList', ['page' => base64_encode($pageCount)]) }}">Last</a>
                                        </li>
                                    </ul>
                                </nav>
                            @endif
                        </div>                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
