@extends('layouts.admin')
@section('title','All Study Projection List')
@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 font-size-18">All Pre Study Projection List</h4>

                        <form method="post" action="{{ route('admin.preStudyProjectionList') }}">
                            @csrf

                            <div class="row">

                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label>Date Range</label>
                                        <div>
                                            <div class="input-daterange input-group">
                                                <input type="date" class="form-control" name="start_date" value="{{ date('Y-m-d', strtotime($start)) }}">
                                                <input type="date" class="form-control" name="end_date" value="{{ date('Y-m-d', strtotime($end)) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-1 pt-1">
                                    <button type="submit" class="btn btn-primary btn-sm vendors save_button mt-4">Submit</button>
                                </div>
                                @if(isset($filter) && ($filter == 1))
                                    <div class="col-md-1 mt-4 pt-1">
                                        <a href="{{ route('admin.preStudyProjectionList') }}" class="btn btn-danger btn-sm mt-4 cancel_button" id="filter" name="save_and_list" value="save_and_list" style="margin-left:-10px !important;">
                                            Reset
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">All Pre Study Projection List</li>
                            </ol>
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- <div class="accordion" id="filter">
        
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#studyCollapseFilter" aria-expanded="true" aria-controls="studyCollapseFilter">
                            Filters
                        </button>
                    </h2>
                    <div id="studyCollapseFilter" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#filter">
                        <div class="accordion-body"> -->
                            
                        <!-- </div>
                    </div>
                </div>
                
            </div> -->

            <div class="row" id="gridContainer">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            
                        </div>
                    </div>
                </div>
            </div><br><br>


            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo" style="color: #4d63cf !important; background-color: #eef1fd !important;">
                        <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#postStudyCollapseFilter" aria-expanded="false" aria-controls="postStudyCollapseFilter">
                            All Executed Study List
                        </button>
                    </h2>
                    <div id="postStudyCollapseFilter" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                        <div class="accordion-body collapse show">

                            <!-- <div class="row">
                                <div class="col-12">
                                    <div class="page-title-box d-flex align-items-center justify-content-between">
                                        <h4 class="mb-0 font-size-18">All Post Study Projection List</h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                                <li class="breadcrumb-item active">All Pre Study Projection List</li>
                                            </ol>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> -->

                            <div class="row" id="postStudyGridContainer">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection