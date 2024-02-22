<!-- <div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="mdi mdi-chart-areaspline"></i>
                        <span key="t-chat">Dashboard</span>
                    </a>
                </li>

                @if(in_array('admin',$module))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bxs-briefcase"></i>
                            <span>Admin</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if(in_array('role',$module))
                                <li><a href="{{ route('admin.roleList') }}">Role</a></li>
                            @endif
                            @if(in_array('team-member',$module))
                                <li><a href="{{ route('admin.teamMemberList') }}">Team Member</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(in_array('masters',$module))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bxs-briefcase"></i>
                            <span>Masters</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if(in_array('activity-master',$module))
                                <li><a href="{{ route('admin.activityMasterList') }}">Activity Master</a></li>
                            @endif                            
                        </ul>
                    </li>
                @endif

                @if(in_array('study',$module))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bxs-briefcase"></i>
                            <span>Study</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if(in_array('study',$module))
                                <li><a href="{{ route('admin.studyList') }}">Study Master</a></li>
                            @endif
                            @if(in_array('study-schedule',$module))
                                <li><a href="{{ route('admin.studyScheduleList') }}">Study Schedule</a></li>
                            @endif
                            @if(in_array('study-schedule-monitoring',$module))
                                <li><a href="{{ route('admin.studyScheduleMonitoringList') }}">Study Schedule Monitoring</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(in_array('role',$module))
                    <li>
                        <a href="{{ route('admin.roleList') }}" class="waves-effect">
                            <i class="bx bxs-briefcase"></i>
                            <span key="t-chat">Role</span>
                        </a>
                    </li>
                @endif

                @if(in_array('team-member',$module))
                    <li>
                        <a href="{{ route('admin.teamMemberList') }}" class="waves-effect">
                            <i class="bx bxs-user-pin"></i>
                            <span key="t-chat">Team Member</span>
                        </a>
                    </li>
                @endif

                @if(in_array('activity-master',$module))
                    <li>
                        <a href="{{ route('admin.activityMasterList') }}" class="waves-effect">
                            <i class="mdi mdi-chart-areaspline"></i>
                            <span key="t-chat">Activity Master</span>
                        </a>
                    </li>
                @endif

                @if(in_array('study',$module))
                    <li>
                        <a href="{{ route('admin.studyList') }}" class="waves-effect">
                            <i class="mdi mdi-chart-areaspline"></i>
                            <span key="t-chat">Study</span>
                        </a>
                    </li>
                @endif

                @if(in_array('study-schedule',$module))
                    <li>
                        <a href="{{ route('admin.studyScheduleList') }}" class="waves-effect">
                            <i class="mdi mdi-chart-areaspline"></i>
                            <span key="t-chat">Study Schedule</span>
                        </a>                        
                    </li>
                @endif

                @if(in_array('study-schedule-monitoring',$module))
                    <li>
                        <a href="{{ route('admin.studyScheduleMonitoringList') }}" class="waves-effect">
                            <i class="mdi mdi-chart-areaspline"></i>
                            <span key="t-chat">Study Schedule Monitoring</span>
                        </a>                        
                    </li>
                @endif

                @if(in_array('state',$module))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bxs-briefcase"></i>
                            <span>State</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('admin.addState') }}">Add State</a></li>
                            <li><a href="{{ route('admin.stateList') }}">All States</a></li>
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div> -->


<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="mdi mdi-chart-areaspline"></i>
                        <span key="t-chat">Dashboard</span>
                    </a>
                </li>

                @if(in_array('admin',$module))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-user"></i>
                            <span>Admin</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if(in_array('role',$module))
                                <li><a href="{{ route('admin.roleList') }}">Role</a></li>
                            @endif
                            @if(in_array('team-member',$module))
                                <li><a href="{{ route('admin.teamMemberList') }}">Team Member</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if(in_array('masters',$module))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-briefcase"></i>
                            <span>Masters</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if(in_array('activity-master',$module))
                                <li><a href="{{ route('admin.activityMasterList') }}">Activity Master</a></li>
                            @endif

                            @if(in_array('sla-activity-master',$module))
                                <li><a href="{{ route('admin.slaActivityMasterList') }}">SLA Activity Master</a></li>
                            @endif             
                        
                            @if(in_array('drug-master',$module))
                                <li><a href="{{ route('admin.drugMasterList') }}">Drug Master</a></li>
                            @endif                            
                        
                            @if(in_array('para-master',$module))
                                <li><a href="{{ route('admin.paraMasterList') }}">Para Master</a></li>
                            @endif                          
                        
                            @if(in_array('sponsor-master',$module))
                                <li><a href="{{ route('admin.sponsorMasterList') }}">Sponsor Master</a></li>
                            @endif                          
                        
                            @if(in_array('holiday-master',$module))
                                <li><a href="{{ route('admin.holidayMasterList')}}">Holiday Master</a></li>
                            @endif                     
                        
                            @if(in_array('location-master',$module))
                                <li><a href="{{ route('admin.locationMasterList')}}">Location Master</a></li>
                            @endif

                            @if(in_array('reason-master',$module))
                                <li><a href="{{ route('admin.reasonMasterList')}}">Reason Master</a></li>
                            @endif

                            @if(in_array('activity-metadata',$module))
                                <li><a href="{{ route('admin.activityMetadataList') }}">Activity Metadata</a></li>
                            @endif

                        </ul>
                    </li>
                @endif

                @if(in_array('study',$module))
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-clipboard"></i>
                            <span>Study</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            @if(in_array('study-master',$module))
                                <li>
                                    <a href="{{ route('admin.studyList') }}">
                                        Study Master
                                    </a>
                                </li>
                            @endif
                            @if(in_array('study-schedule',$module))
                                <li>
                                    <a href="{{ route('admin.studyScheduleList') }}">
                                        Study Schedule
                                    </a>
                                </li>
                            @endif
                            @if(in_array('study-schedule-monitoring',$module))
                                <li>
                                    <a href="{{ route('admin.studyScheduleMonitoringList') }}">
                                        Study Schedule Tracking
                                    </a>
                                </li>
                            @endif
                            @if(in_array('study-activity-monitoring',$module))
                                <li>
                                    <a href="{{ route('admin.studyActivityMonitoringList') }}">
                                        Study Activity Tracking
                                    </a>
                                </li>
                            @endif
                            @if(in_array('study-master-data',$module))
                                <li>
                                    <a href="{{ route('admin.studyMasterList') }}">
                                        Study Master Data
                                    </a>
                                </li>
                            @endif
                            @if(in_array('pre-study-projection-data',$module))
                                <li>
                                    <a href="{{ route('admin.preStudyProjectionList') }}">
                                        Pre Study Projection Data
                                    </a>
                                </li>
                            @endif
                            @if(in_array('study-metadata',$module))
                                <li>
                                    <a href="{{ route('admin.allStudiesActivityMetadataList') }}">
                                        Studies Metadata List
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</div>
