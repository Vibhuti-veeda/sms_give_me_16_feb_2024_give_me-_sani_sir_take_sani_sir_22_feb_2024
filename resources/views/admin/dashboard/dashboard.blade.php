@extends('layouts.admin')
@section('title','Dashboard')
@section('content')

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                        @if(Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 3)
                            <div class="form-group">
                                <label>Dashboard Access<span class="mandatory">*</span></label>
                                <select class="form-control select2 dashboardView" name="role" id="role_modules" data-placeholder="Select Module(s)" required >
                                    <option value="ALL">
                                        All
                                    </option>
                                    <option value="{{ Auth::guard('admin')->user()->id }}">
                                        {{ Auth::guard('admin')->user()->name }}
                                    </option>
                                </select>
                            </div>
                        @endif

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">
                                        
                                    </a>
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row allView">
                
                @if((Auth::guard('admin')->user()->role_id == 1) || (Auth::guard('admin')->user()->role_id == 2) || (Auth::guard('admin')->user()->role_id == 3) || (Auth::guard('admin')->user()->role_id == 6) || (Auth::guard('admin')->user()->role_id == 10) || (((Auth::guard('admin')->user()->role_id == 7) || (Auth::guard('admin')->user()->role_id == 8) || (Auth::guard('admin')->user()->role_id == 11) || (Auth::guard('admin')->user()->role_id == 15) || (Auth::guard('admin')->user()->role_id == 12)) && (Auth::guard('admin')->user()->is_hod == 1)))
                    <div style="border: 2px solid;">
                        <h3 class="mt-2" style="margin-left: 10px;">
                            <b>
                                Study
                            </b>
                        </h3>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="{{ route('admin.studyScheduleMonitoringList') }}?ref=COMPLETED" title="Completed">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Completed
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalCompletedStudy }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                            <span class="avatar-title">
                                                                <i class="bx bx-archive-in font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.studyScheduleMonitoringList') }}?ref=ONGOING" title="Ongoing">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Ongoing
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalOngoingStudy }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('admin.studyScheduleMonitoringList') }}?ref=UPCOMING" title="Upcoming">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Upcoming
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalUpcomingStudy }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>&nbsp;
                @endif

                @if(Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 3 || Auth::guard('admin')->user()->role_id == 4 || Auth::guard('admin')->user()->role_id == 5 || Auth::guard('admin')->user()->role_id == 6 || Auth::guard('admin')->user()->role_id == 10 || Auth::guard('admin')->user()->role_id == 14)
                    <div style="border: 2px solid;">
                        <h3 class="mt-2" style="margin-left: 10px;">
                            <b>
                                Pre Study Activity
                            </b>
                        </h3>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPreStatus=COMPLETED" title="Completed">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Completed
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalPreCompleted }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPreStatus=ONGOING" title="Ongoing">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Ongoing
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalPreOngoing }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPreStatus=UPCOMING" title="Upcoming">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Upcoming
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalPreUpcoming }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPreStatus=DELAY" title="Delay">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Delay
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalPreDelay }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>&nbsp;

                    <div style="border: 2px solid;">
                        <h3 class="mt-2" style="margin-left: 10px;">
                            <b>
                                Post Study Activity
                            </b>
                        </h3>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=COMPLETED" title="Completed">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Completed
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalCompleted }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=ONGOING" title="Ongoing">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Ongoing
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalOngoing }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=UPCOMING" title="Upcoming">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Upcoming
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalUpcoming }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=DELAY" title="Delay">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Delay
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalDelay }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                @elseif((((Auth::guard('admin')->user()->role_id == 11) || (Auth::guard('admin')->user()->role_id == 12)) && (Auth::guard('admin')->user()->location_id != '0')))

                    <div style="border: 2px solid;">
                        <h3 class="mt-2" style="margin-left: 10px;">
                            <b>
                                Activity
                            </b>
                        </h3>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=COMPLETED&crLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Completed">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Completed
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalCompleted }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=ONGOING&crLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Started on time, but not completed"   >
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Ongoing
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalOngoing }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                               <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=UPCOMING&crLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Upcoming">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Upcoming
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalUpcoming }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                               <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=DELAY&crLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Not started as per scheduled start date">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Delay
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalDelay }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                               <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @elseif((Auth::guard('admin')->user()->role_id == '13') || ((Auth::guard('admin')->user()->role_id == '15') && (Auth::guard('admin')->user()->location_id != '0')))

                    <div style="border: 2px solid;">
                        <h3 class="mt-2" style="margin-left: 10px;">
                            <b>
                                Activity
                            </b>
                        </h3>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=COMPLETED&brLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Started on time, completed on time">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Completed
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalCompleted }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                           <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=ONGOING&brLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Started on time, but not completed"   >
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Ongoing
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalOngoing }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=UPCOMING&brLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Upcoming">
                                       <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Upcoming
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalUpcoming }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=DELAY&brLocationName={{base64_encode(Auth::guard('admin')->user()->location_id)}}" title="Not started as per scheduled start date">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Delay
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalDelay }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    <div style="border: 2px solid;">
                        <h3 class="mt-2" style="margin-left: 10px;">
                            <b>
                                Activity
                            </b>
                        </h3>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=COMPLETED" title="Started on time, completed on time">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Completed
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalCompleted }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=ONGOING" title="Started on time, but not completed"   >
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Ongoing
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalOngoing }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=UPCOMING" title="Upcoming">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Upcoming
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalUpcoming }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-md-3">
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}?refPostStatus=DELAY" title="Not started as per scheduled start date">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">
                                                            Delay
                                                        </p>
                                                        <h4 class="mb-0">{{ $totalDelay }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-copy-alt font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                &nbsp;
            </div>

            <div class="row personalView" style="display: none;">
            </div>
            <br>

            @if(Auth::guard('admin')->user()->role_id == 1 || Auth::guard('admin')->user()->role_id == 2 || Auth::guard('admin')->user()->role_id == 3 || Auth::guard('admin')->user()->role_id == 10)
                <div class="row">
                    <div class="col-lg-12" style="border: 2px solid;">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-center m-t-0 mb-4">PM’s Total Delay Activity</h4>
                                <div class="row text-center">
                                           
                                </div>
                                <div class="panel panel-body text-center" data-toggle="match-height" style="height: 100%;">
                                    <iframe class="chartjs-hidden-iframe" style="width: 100%; display: block; border: 0px; height: 0px; margin: 0px; position: absolute; inset: 0px;">
                                    </iframe>

                                    <canvas data-chart="bar" data-labels="[@foreach($graphName as $key => $pm)&quot;{{ $pm }}&quot;@if($key < $pmCount - 1),@endif @endforeach]" data-values="[{&quot;backgroundColor&quot;: &quot;#d63636&quot;, &quot;borderColor&quot;: &quot;#d63636&quot;, &quot;borderWidth&quot;: 1, &quot;label&quot;: &quot;Total Delay Activity&quot;, &quot;data&quot;: [@foreach($graphDelay as $key => $gd){{ $gd }} @if($key < $pmCount - 1),@endif @endforeach]}]" data-hide="[&quot;legend&quot;]" data-scales='{"xAxes": [ { &quot;gridLines&quot;: { &quot;drawOnChartArea&quot;: false}}], &quot;yAxes&quot;: [ { &quot;ticks&quot;: { &quot;min&quot;: 0}}]}' height="260" width="900" style="display: block; width: 790px; height: 395px;">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
