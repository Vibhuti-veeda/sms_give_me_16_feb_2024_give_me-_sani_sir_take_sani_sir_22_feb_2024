@extends('layouts.admin')
@section('title','All Scheduled Study')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">All Scheduled Study</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                @if($access->add == '1')
                                    <a href="{{ route('admin.addStudySchedule') }}" class="headerButtonStyle" role="button" title="Add Study Schedule">
                                        Add Study Schedule
                                    </a>
                                @endif
                            </li>
                        </ol>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div style="margin-top: -40px; transform: translate(137px, 51px); width: fit-content;">
                            <a href="{{ route('admin.exportStudySchedule')}}" class="btn btn-secondary">Export</a>    
                        </div>
                        <table id="tableList" class="table table-striped table-bordered nowrap tableList-search" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Study No</th>
                                    <th>Sponsor</th>
                                    <th>Drug</th>
                                    <!-- <th>Status</th> -->
                                    @if($admin == 'yes')
                                        <th class='notexport'>Actions</th>
                                    @else
                                        @if($access->edit != '' || $access->delete != '')
                                            <th class='notexport'>Actions</th>
                                        @endif
                                    @endif
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
                                            <td class="bolfFont">
                                                {{ ((!is_null($sv->studyNo)) && ($sv->studyNo->study_no != '')) ? $sv->studyNo->study_no : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($sv->studyNo)) && ($sv->studyNo->sponsorName != '') && ($sv->studyNo->sponsorName->sponsor_name != '')) ? $sv->studyNo->sponsorName->sponsor_name : '---' }}
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

                                            <!-- @php $checked = ''; @endphp
                                            @if($sv->is_active == 1) @php $checked = 'checked' @endphp @endif
                                            <td>
                                                <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                    <input class="form-check-input studyStatus" type="checkbox" id="customSwitch{{ $sk }}" value="1" data-id="{{ $sv->id }}" {{ $checked }}>
                                                    <label class="form-check-label" for="customSwitch{{ $sk }}"></label>
                                                </div>
                                            </td> -->

                                            @if($admin == 'yes' || ($access->edit != '' && $access->delete != ''))
                                                <td>
                                                    <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{ route('admin.addStudyScheduleDate',base64_encode($sv->study_id)) }}" role="button" title="Add Schedule Date">
                                                        <i class="bx bx-calendar-event"></i>
                                                    </a>

                                                    <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{ route('admin.editStudySchedule',base64_encode($sv->study_id)) }}" role="button" title="Edit">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a>
                                                    
                                                    <a class="btn btn-danger btn-sm waves-effect waves-light" href="{{ route('admin.deleteStudySchedule',base64_encode($sv->study_id)) }}" role="button" onclick="return confirm('Do you want to delete this study schedule?');" title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </td>
                                            @else
                                                @if($access->edit != '')
                                                    <td>
                                                        <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{ route('admin.addStudyScheduleDate',base64_encode($sv->study_id)) }}" role="button" title="Add Schedule Date">
                                                            <i class="bx bx-calendar-event"></i>
                                                        </a>

                                                        <a class="btn btn-primary btn-sm waves-effect waves-light" href="{{ route('admin.editStudySchedule',base64_encode($sv->study_id)) }}" role="button" title="Edit">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                                @if($access->delete != '')
                                                    <td>
                                                        <a class="btn btn-danger btn-sm waves-effect waves-light" href="{{ route('admin.deleteStudySchedule',base64_encode($sv->study_id)) }}" role="button" onclick="return confirm('Do you want to delete this study schedule?');" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </a>
                                                    </td>
                                                @endif
                                            @endif
                                            
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
                                            <a class="page-link" href="{{ route('admin.studyScheduleList', ['page' => base64_encode(1)]) }}">First</a>
                                        </li>
                                        <li class="page-item {{ ($page == 1) ? 'disabled' : '' }}">
                                            <a class="page-link h5" href="{{ route('admin.studyScheduleList', ['page' => base64_encode($page - 1)]) }}">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        @for ($i = max(1, $page); $i <= min($page + 4, $pageCount); $i++)
                                            <li class="page-item {{($page == $i) ? 'active' : '' }}" aria-current="page">
                                                <a class="page-link" href="{{ route('admin.studyScheduleList', ['page' => base64_encode($i)]) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link h5" href="{{ route('admin.studyScheduleList', ['page' => base64_encode($page + 1)]) }}">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ route('admin.studyScheduleList', ['page' => base64_encode($pageCount)]) }}">Last</a>
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