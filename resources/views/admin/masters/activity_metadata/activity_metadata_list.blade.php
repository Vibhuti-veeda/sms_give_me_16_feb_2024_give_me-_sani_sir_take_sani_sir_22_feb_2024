@extends('layouts.admin')
@section('title','All Activity Metadata')
@section('content')

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">All Activity Metadata</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                @if($access->add == '1')
                                    <a href="{{ route('admin.addActivityMetadata') }}" class="headerButtonStyle" role="button" title="Add Activity Metadata">
                                        Add Activity Metadata
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
                            <a href="{{ route('admin.exportActivityMetadata')}}" class="btn btn-secondary">Export</a>    
                        </div>   
                        <table id="tableList" class="table table-striped table-bordered tableList-search" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Activity Name</th>
                                    <th>Control Name</th>
                                    <th>Source Title</th>
                                    <th>Source Value</th>
                                    <th>Is Mandatory</th>
                                    <!-- <th>Control Validation</th> -->
                                    <th>Activity Type</th>
                                    @if($access->delete != '')
                                        <th>Status</th>
                                    @endif
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
                                @if(!is_null($activityMetadataList))
                                    @php
                                        $count = (($offset == 0) ? 1 : $offset+1); 
                                    @endphp
                                    @foreach($activityMetadataList as $amk => $amv)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>
                                                {{ ((!is_null($amv->activityName)) && ($amv->activityName->activity_name != '')) ? $amv->activityName->activity_name : '---' }}
                                            </td>
                                            <td>
                                                {{ ((!is_null($amv->controlName)) && ($amv->controlName->control_name != '')) ? $amv->controlName->control_name : '---' }}
                                            </td>
                                            
                                            <td>
                                                {{ ($amv->source_question != '') ? $amv->source_question : '---'}}
                                            </td>
                                            <td style="max-width: 50px;text-overflow: ellipsis;overflow : hidden;">
                                                {{ ($amv->source_value != '') ? $amv->source_value : '---'}}
                                            </td>
                                            <td>
                                                {{ $amv->is_mandatory == 1 ? 'Yes' : 'No' }}
                                            </td>
                                            <!-- <td>
                                                {{ ($amv->input_validation != '') ? $amv->input_validation : '---'}}
                                            </td> -->
                                            <td>
                                                {{ $amv->is_activity == 'S' ? 'Actual Start' : 'Actual End' }}
                                            </td>
                        
                                            @if($access->delete != '')
                                                @php $checked = ''; @endphp
                                                @if($amv->is_active == 1) @php $checked = 'checked' @endphp @endif
                                                <td>
                                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                                        <input class="form-check-input activityMetadataStatus" type="checkbox" id="customSwitch{{ $amk }}" value="1" data-id="{{ $amv->id }}" {{ $checked }}>
                                                        <label class="form-check-label" for="customSwitch{{ $amk }}"></label>
                                                    </div>
                                                </td>
                                            @endif
                        
                                            @if($admin == 'yes' || ($access->edit != '' && $access->delete != ''))
                                                <td>
                                                    <!-- <a class="btn btn-primary btn-sm waves-effect waves-light" href="#" role="button" title="Edit">
                                                        <i class="bx bx-edit-alt"></i>
                                                    </a> -->
                                                    
                                                    <a class="btn btn-danger btn-sm wmes-effect waves-light" href="{{ route('admin.deleteActivityMetadata', base64_encode($amv->id)) }}" role="button" onclick="return confirm('Do you want to delete this activity metadata ?');" title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                    </a>
                                                </td>
                                            @else
                                                <!-- @if($access->edit != '')
                                                    <td>
                                                        <a class="btn btn-primary btn-sm waves-effect waves-light" href="#" role="button" title="Edit">
                                                            <i class="bx bx-edit-alt"></i>
                                                        </a>
                                                    </td>
                                                @endif -->
                                                @if($access->delete != '')
                                                    <td>
                                                        <a class="btn btn-danger btn-sm waves-effect waves-light" href="{{ route('admin.deleteActivityMetadata', base64_encode($amv->id)) }}" role="button" onclick="return confirm('Do you want to delete this activity metadata ?');" title="Delete">
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
                                            <a class="page-link" href="{{ route('admin.activityMetadataList', ['page' => base64_encode(1)]) }}">First</a>
                                        </li>
                                        <li class="page-item {{ ($page == 1) ? 'disabled' : '' }}">
                                            <a class="page-link h5" href="{{ route('admin.activityMetadataList', ['page' => base64_encode($page - 1)]) }}">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        @for ($i = max(1, $page); $i <= min($page + 4, $pageCount); $i++)
                                            <li class="page-item {{($page == $i) ? 'active' : '' }}" aria-current="page">
                                                <a class="page-link" href="{{ route('admin.activityMetadataList', ['page' => base64_encode($i)]) }}">{{ $i }}</a>
                                            </li>
                                        @endfor
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link h5" href="{{ route('admin.activityMetadataList', ['page' => base64_encode($page + 1)]) }}">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item {{ ($page == $pageCount) ? 'disabled' : '' }}">
                                            <a class="page-link" href="{{ route('admin.activityMetadataList', ['page' => base64_encode($pageCount)]) }}">Last</a>
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
