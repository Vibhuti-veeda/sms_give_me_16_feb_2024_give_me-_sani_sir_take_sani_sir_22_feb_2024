@extends('layouts.admin')
@section('title','All Studies')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">All Study Master Data</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">All Study Master Data</li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionExample">
            
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button fw-medium @if(isset($filter) && ($filter == 1)) @else collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#studyCollapseFilter" aria-expanded="false" aria-controls="studyCollapseFilter">
                        Filters
                    </button>
                </h2>
                <div id="studyCollapseFilter" class="accordion-collapse @if(isset($filter) && ($filter == 1)) @else collapse @endif" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body collapse show">
                        <form method="post" action="{{ route('admin.studyMasterList') }}">
                            @csrf
                            <input type="hidden" name="page" id="page" value="{{ $page }}">
                            <div class="row">

                                <div class="col-md-2">
                                    <label class="control-label">CR Location</label>
                                    <select class="form-control select2" name="cr_location" id="cr_location" style="width: 100%;">
                                        <option value="">Select CR Location</option>
                                        @if(!is_null($crLocation))
                                            @foreach($crLocation as $ck => $cv)
                                                <option @if($crLocationName == $cv->id) selected @endif value="{{ $cv->id }}">
                                                    {{ $cv->location_name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date Filter</label>
                                        <div>
                                            <div class="input-daterange input-group" data-date-format="dd/mm/yyyy"data-date-autoclose="true" data-provide="datepickerStyle" autocomplete="off">
                                                <input type="text" class="form-control datepickerStyle" name="start_date" id="start_date" value="{{ $startDate }}" autocomplete="off" placeholder="From Date">
                                                <input type="text" class="form-control datepickerStyle" name="end_date" id="end_date" value="{{ $endDate }}" autocomplete="off" placeholder="To Date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary vendors save_button mt-4">Submit</button>
                                </div>
                                @if(isset($filter) && ($filter == 1))
                                    <div class="col-md-2">
                                        <a href="{{ route('admin.studyMasterList') }}" class="btn btn-danger mt-4 cancel_button" id="filter" name="save_and_list" value="save_and_list" style="margin-left:-10px !important;">
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
                            <a href="{{ route('admin.exportStudyMaster')}}" class="btn btn-secondary">Export</a>    
                        </div>
                        <table id="tableList" class="table table-striped table-bordered nowrap tableList-search" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Study No</th>
                                    <th>Global Priority No</th>
                                    <th>Sponsor Study No</th>
                                    <th>Submission Type</th>
                                    <th>Cdisc</th>
                                    <th>Drug Name</th>
                                    <th>PM Name</th>
                                    <th>Sponsor Name</th>
                                    <th>No Of Subject</th>
                                    <th>No Of Female</th>
                                    <th>Period 1 Check In(S)</th>
                                    <th>Period 1 Check In(A)</th>
                                    <th>Last Sample(S)</th>
                                    <th>Last Sample(A)</th>
                                    <th>CR to QA(S)</th>
                                    <th>CR to QA(A)</th>
                                    <th>QA to CR(S)</th>
                                    <th>QA to CR(A)</th>
                                    <th>Bio Analyitcal Start(S)</th>
                                    <th>Bio Analyitcal Start(A)</th>
                                    <th>Bio Analyitcal End(S)</th>
                                    <th>Bio Analyitcal End(A)</th>
                                    <th>BR to QA(S)</th>
                                    <th>BR to QA(A)</th>
                                    <th>QA to BR(S)</th>
                                    <th>QA to BR(A)</th>
                                    <th>BR to PB(S)</th>
                                    <th>BR to PB(A)</th>
                                    <th>PB to QA(S)</th>
                                    <th>PB to QA(A)</th>
                                    <th>QA to PB(S)</th>
                                    <th>QA to PB(A)</th>
                                    <th>Draft Report Date(S)</th>
                                    <th>Draft Report Date(A)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!is_null($studies))
                                    @php
                                        $count = (($offset == 0) ? 1 : $offset+1); 
                                    @endphp
                                    @foreach($studies as $sk => $sv)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $sv->study_no }}</td>
                                            <td>{{ $sv->global_priority_no != '' ? $sv->global_priority_no : '---' }}</td>
                                            <td>{{ $sv->sponsor_study_no != '' ? $sv->sponsor_study_no : '---' }}</td>
                                            <td>
                                                @if(!is_null($sv->studyRegulatory)) 
                                                    @php $submission = array(); @endphp
                                                    @foreach($sv->studyRegulatory as $srk => $srv)
                                                        @if((!is_null($srv->paraSubmission)) && ($srv->paraSubmission->para_value != ''))
                                                            @php 
                                                                $submission[] = $srv->paraSubmission->para_value;
                                                            @endphp
                                                        @endif    
                                                        
                                                    @endforeach
                                                    <p>{{ implode(' | ', $submission) }}</p>
                                                @endif
                                            </td>
                                            <td>{{ $sv->cdisc_require == 1 ? 'Yes' : 'No' }}</td>
                                            <td>
                                                @if(!is_null($sv->drugDetails)) 
                                                    @php $drug = ''; @endphp
                                                    @foreach($sv->drugDetails as $dk => $dv)
                                                        @if((!is_null($dv->drugName)) && !is_null($dv->drugDosageName) && ($dv->dosage != '') && !is_null($dv->drugUom) && !is_null($dv->drugType) && ($dv->drugType->type == 'TEST'))
                                                            @php 
                                                                $drug = $dv->drugName->drug_name.' - '.$dv->drugDosageName->para_value .' - '.$dv->dosage .''.$dv->drugUom->para_value;
                                                            @endphp
                                                        @endif    
                                                        
                                                    @endforeach
                                                    <p>{{ $drug != '' ? $drug : '---' }}</p>
                                                @endif
                                            </td>
                                            <td>{{ $sv->projectManager->name }}</td>
                                            <td>{{ $sv->sponsorName->sponsor_name }}</td>
                                            <td>{{ $sv->no_of_subject }}</td>
                                            <td>{{ $sv->no_of_female_subjects }}</td>
                                            <td>
                                                {{ ((!is_null($sv->checkIn)) && ($sv->checkIn->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->checkIn->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->checkIn)) && ($sv->checkIn->actual_end_date != '')) ? date('d M Y', strtotime($sv->checkIn->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->lastSample)) && ($sv->lastSample->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->lastSample->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->lastSample)) && ($sv->lastSample->actual_end_date != '')) ? date('d M Y', strtotime($sv->lastSample->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->CRtoQA)) && ($sv->CRtoQA->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->CRtoQA->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->CRtoQA)) && ($sv->CRtoQA->actual_end_date != '')) ? date('d M Y', strtotime($sv->CRtoQA->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->QAtoCR)) && ($sv->QAtoCR->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->QAtoCR->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->QAtoCR)) && ($sv->QAtoCR->actual_end_date != '')) ? date('d M Y', strtotime($sv->QAtoCR->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->bioanalyticalStartEnd)) && ($sv->bioanalyticalStartEnd->scheduled_start_date != '')) ? date('d M Y', strtotime($sv->bioanalyticalStartEnd->scheduled_start_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->bioanalyticalStartEnd)) && ($sv->bioanalyticalStartEnd->actual_start_date != '')) ? date('d M Y', strtotime($sv->bioanalyticalStartEnd->actual_start_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->bioanalyticalStartEnd)) && ($sv->bioanalyticalStartEnd->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->bioanalyticalStartEnd->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->bioanalyticalStartEnd)) && ($sv->bioanalyticalStartEnd->actual_end_date != '')) ? date('d M Y', strtotime($sv->bioanalyticalStartEnd->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->BRtoQA)) && ($sv->BRtoQA->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->BRtoQA->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->BRtoQA)) && ($sv->BRtoQA->actual_end_date != '')) ? date('d M Y', strtotime($sv->BRtoQA->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->QAtoBR)) && ($sv->QAtoBR->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->QAtoBR->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->QAtoBR)) && ($sv->QAtoBR->actual_end_date != '')) ? date('d M Y', strtotime($sv->QAtoBR->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->BRtoPB)) && ($sv->BRtoPB->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->BRtoPB->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->BRtoPB)) && ($sv->BRtoPB->actual_end_date != '')) ? date('d M Y', strtotime($sv->BRtoPB->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->PBtoQA)) && ($sv->PBtoQA->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->PBtoQA->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->PBtoQA)) && ($sv->PBtoQA->actual_end_date != '')) ? date('d M Y', strtotime($sv->PBtoQA->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->QAtoPB)) && ($sv->QAtoPB->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->QAtoPB->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->QAtoPB)) && ($sv->QAtoPB->actual_end_date != '')) ? date('d M Y', strtotime($sv->QAtoPB->actual_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->darftToSponsor)) && ($sv->darftToSponsor->scheduled_end_date != '')) ? date('d M Y', strtotime($sv->darftToSponsor->scheduled_end_date)) : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->darftToSponsor)) && ($sv->darftToSponsor->actual_end_date != '')) ? date('d M Y', strtotime($sv->darftToSponsor->actual_end_date)) : '---' }}
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
                                            <a class="page-link" data-page="{{1}}" href="javascript:void(0)">First</a>
                                        </li>
                                        <li class="page-item {{ ($page == 1) ? 'disabled' : '' }}">
                                            <a class="page-link h5" data-page= "{{ ($page - 1) }}" href="javascript:void(0)">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        @for ($i = max(1, $page); $i <= min($page + 4, $pageCount); $i++)
                                            <li class="page-item {{($page == $i) ? 'active' : '' }}" aria-current="page">
                                                <a class="page-link" data-page= "{{ $i }}" href="javascript:void(0)">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link h5" data-page= "{{ ($page + 1) }}" href="javascript:void(0)">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link" data-page= "{{ $pageCount }}" href="javascript:void(0)">Last</a>
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