<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\GlobalController;
use App\Models\ParaCode;
use App\Models\RoleDefinedDashboardElement;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\BloodGroup;
use App\Models\Inquiry;
use App\Models\League;
use App\Models\LeagueFixture;
use App\Models\Player;
use App\Models\Role;
use App\Models\Team;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\ActivityMaster;
use App\Models\Study;
use App\Models\StudySchedule;
use App\View\DepartmentActivities;

class AdminController extends GlobalController
{
    public function __construct(){
        $this->middleware('admin');
    }

    // Dashboard 
    public function index(){

        $pm = array();
        $studySchedule = Study::where('is_active', 1)
                              ->where('is_delete', 0)
                              ->with('projectManager')
                              ->whereHas('projectManager', function($q){
                                    $q->where('is_active',1);
                              })
                              ->withcount('scheduleDelay')
                              ->get();

        $pm = array();
        $delayCount = array();
        if (!is_null($studySchedule)) {
            foreach ($studySchedule as $sk => $sv) {
                $pm[] = $sv->projectManager->name;
                $delayCount[$sv->projectManager->name][] = $sv->schedule_delay_count;
            }
        }

        $pmData = array();
        if (!is_null($delayCount)) {
            foreach ($delayCount as $key => $value) {
                $pmData[$key] = array_sum($value);
            }
        }

        $graphName = array();
        if (!is_null($pmData)) {
            foreach ($pmData as $key => $value) {
                $graphName[] = $key;
            }
        }

        $graphDelay = array();
        if (!is_null($pmData)) {
            foreach ($pmData as $key => $value) {
                $graphDelay[] = $value;
            }
        }
  
        $pmCount = count($graphName);

        $activities = ActivityMaster::where('responsibility', Auth::guard('admin')->user()->role_id)->get('id')->toArray();
        $multipleRoleActivities = explode(',', Auth::guard('admin')->user()->multiple_roles);

        $studyNo = Study::where('is_active', 1)
                        ->where('is_delete', 0)
                        ->whereHas('projectManager', function($q){
                            $q->where('is_active',1);
                        })
                        ->pluck('id');

        if(((Auth::guard('admin')->user()->role_id == '7') || (Auth::guard('admin')->user()->role_id == '8') || (Auth::guard('admin')->user()->role_id == '11') || (Auth::guard('admin')->user()->role_id == '12') || (Auth::guard('admin')->user()->role_id == '15')) && (Auth::guard('admin')->user()->is_hod == '1')){
            // Study nos queries
            $totalCompletedStudy = Study::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('study_status', 'COMPLETED')
                                        ->whereHas('schedule', function($q) use($multipleRoleActivities) {
                                            $q->whereNotNull('scheduled_start_date')
                                            ->whereIn('responsibility_id', $multipleRoleActivities)
                                            ->where('is_active', 1)
                                            ->where('is_delete', 0);
                                        })
                                        ->count();

            /*$totalOngoingStudy = Study::where('is_active', 1)->where('is_delete', 0)->where('study_status', 'ONGOING')->count();*/
            
            $totalOngoingStudy = Study::where('is_active', 1)
                                      ->where('is_delete', 0)
                                      ->where('study_status', 'ONGOING')
                                      ->whereHas('schedule', function($q) use($multipleRoleActivities) {
                                            $q->whereNotNull('scheduled_start_date')
                                            ->whereIn('responsibility_id', $multipleRoleActivities)
                                            ->where('is_active', 1)
                                            ->where('is_delete', 0);
                                        })
                                      ->count();

            $totalUpcomingStudy = Study::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('study_status', 'UPCOMING')
                                       ->whereHas('schedule', function($q) use($multipleRoleActivities) {
                                            $q->whereNotNull('scheduled_start_date')
                                            ->whereIn('responsibility_id', $multipleRoleActivities)
                                            ->where('is_active', 1)
                                            ->where('is_delete', 0);
                                        })
                                       ->count();
        } else {
            // Study nos queries
            $totalCompletedStudy = Study::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('study_status', 'COMPLETED')
                                        ->whereHas('schedule', function($q) {
                                            $q->whereNotNull('scheduled_start_date')
                                            ->where('is_active', 1)
                                            ->where('is_delete', 0);
                                        })
                                        ->count();

            /*$totalOngoingStudy = Study::where('is_active', 1)->where('is_delete', 0)->where('study_status', 'ONGOING')->count();*/
            
            $totalOngoingStudy = Study::where('is_active', 1)
                                      ->where('is_delete', 0)
                                      ->where('study_status', 'ONGOING')
                                      ->whereHas('schedule', function($q) {
                                            $q->whereNotNull('scheduled_start_date')
                                            ->where('is_active', 1)
                                            ->where('is_delete', 0);
                                      })
                                      ->count();

            $totalUpcomingStudy = Study::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('study_status', 'UPCOMING')
                                       ->whereHas('schedule', function($q) {
                                            $q->whereNotNull('scheduled_start_date')
                                            ->where('is_active', 1)
                                            ->where('is_delete', 0);
                                        })
                                       ->count();
        }

        // Activity nos queries
        $crlocation = Study::where('cr_location',Auth::guard('admin')->user()->location_id)->get('id')->toArray();
        $brlocation = Study::where('br_location',Auth::guard('admin')->user()->location_id)->get('id')->toArray();

        $totalPreCompleted = 0;
        $totalPreUpcoming = 0;
        $totalPreOngoing = 0;
        $totalPreDelay = 0;
       
        if(Auth::guard('admin')->user()->role_id == '1' || Auth::guard('admin')->user()->role_id == '2' || Auth::guard('admin')->user()->role_id == '3' || Auth::guard('admin')->user()->role_id == '4' || Auth::guard('admin')->user()->role_id == '5' || Auth::guard('admin')->user()->role_id == '6' || Auth::guard('admin')->user()->role_id == '10' || Auth::guard('admin')->user()->role_id == '14'){

            $totalPreCompleted = StudySchedule::where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->where('scheduled_start_date', '!=', NULL)
                                            ->where('scheduled_end_date', '!=', NULL)
                                            ->where('activity_status', 'COMPLETED')
                                            ->where('activity_type',123)
                                            ->count();

            $totalCompleted = StudySchedule::where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->where('scheduled_start_date', '!=', NULL)
                                            ->where('scheduled_end_date', '!=', NULL)
                                            ->where('activity_status', 'COMPLETED')
                                            ->whereIn('activity_type',[113,114,115,116])
                                            ->count();

            $totalPreUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('activity_status', 'UPCOMING')
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->where('activity_type',123)
                                          ->count();

            $totalUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('activity_status', 'UPCOMING')
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->whereIn('activity_type',[113,114,115,116])
                                          ->count();

            $totalPreOngoing = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'ONGOING')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->where('activity_type',123)
                                        ->count();

            $totalOngoing = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'ONGOING')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->whereIn('activity_type',[113,114,115,116])
                                        ->count();

            $totalPreDelay = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'DELAY')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->where('activity_type',123)
                                        ->count();

            $totalDelay = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'DELAY')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->whereIn('activity_type',[113,114,115,116])
                                        ->count();

        } else if((Auth::guard('admin')->user()->role_id == '11') ||(Auth::guard('admin')->user()->role_id == '12')){
            $totalCompleted = StudySchedule::where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->where('activity_status', 'COMPLETED')
                                            ->where('scheduled_start_date', '!=', NULL)
                                            ->where('scheduled_end_date', '!=', NULL)
                                            ->where(function ($q) use ($activities, $crlocation, $multipleRoleActivities){
                                                if(Auth::guard('admin')->user()->is_hod != '1'){
                                                    $q->whereIn('activity_id',$activities)
                                                    ->whereIn('study_id',$crlocation);
                                                } elseif((Auth::guard('admin')->user()->role_id == '12') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                    $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                                } elseif ((Auth::guard('admin')->user()->role_id == '11') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                    $q->whereIn('activity_id',$activities);
                                                }
                                            })
                                            ->count();

            $totalUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('activity_status', 'UPCOMING')
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->where(function ($q) use ($activities, $crlocation, $multipleRoleActivities){
                                            if(Auth::guard('admin')->user()->is_hod != '1'){
                                                $q->whereIn('activity_id',$activities)
                                                ->whereIn('study_id',$crlocation);
                                            } elseif((Auth::guard('admin')->user()->role_id == '12') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                            } elseif ((Auth::guard('admin')->user()->role_id == '11') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                $q->whereIn('activity_id',$activities);
                                            }
                                         })
                                          ->count();

            $totalOngoing = StudySchedule::where('is_active', 1)
                                         ->where('is_delete', 0)
                                         ->where('activity_status', 'ONGOING')
                                         ->where('scheduled_start_date', '!=', NULL)
                                         ->where('scheduled_end_date', '!=', NULL)
                                         ->where(function ($q) use ($activities, $crlocation, $multipleRoleActivities){
                                            if(Auth::guard('admin')->user()->is_hod != '1'){
                                                $q->whereIn('activity_id',$activities)
                                                ->whereIn('study_id',$crlocation);
                                            } elseif((Auth::guard('admin')->user()->role_id == '12') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                            } elseif ((Auth::guard('admin')->user()->role_id == '11') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                $q->whereIn('activity_id',$activities);
                                            }
                                         })
                                         ->count();

            $totalDelay = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'DELAY')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->where(function ($q) use ($activities, $crlocation, $multipleRoleActivities){
                                            if(Auth::guard('admin')->user()->is_hod != '1'){
                                                $q->whereIn('activity_id',$activities)
                                                ->whereIn('study_id',$crlocation);
                                            } elseif((Auth::guard('admin')->user()->role_id == '12') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                            } elseif ((Auth::guard('admin')->user()->role_id == '11') && (Auth::guard('admin')->user()->is_hod == '1')){
                                                $q->whereIn('activity_id',$activities);
                                            }
                                        })
                                        ->count();

        } else if((Auth::guard('admin')->user()->role_id == '13') || (Auth::guard('admin')->user()->role_id == '15')){
            $totalCompleted = StudySchedule::where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->where('activity_status', 'COMPLETED')
                                            ->where('scheduled_start_date', '!=', NULL)
                                            ->where('scheduled_end_date', '!=', NULL)
                                            ->where(function ($q) use ($activities, $brlocation, $multipleRoleActivities){
                                                if(Auth::guard('admin')->user()->is_hod != '1'){
                                                    $q->whereIn('activity_id', $activities)
                                                    ->whereIn('study_id',$brlocation);
                                                } elseif(Auth::guard('admin')->user()->is_hod == '1'){
                                                    $q->whereIn('responsibility_id', $multipleRoleActivities);  
                                                }
                                            })
                                            ->count();

            $totalUpcoming = StudySchedule::where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->where('activity_status', 'UPCOMING')
                                            ->where('scheduled_start_date', '!=', NULL)
                                            ->where('scheduled_end_date', '!=', NULL)
                                            ->where(function ($q) use ($activities, $brlocation, $multipleRoleActivities){
                                                if(Auth::guard('admin')->user()->is_hod != '1'){
                                                    $q->whereIn('activity_id', $activities)
                                                    ->whereIn('study_id',$brlocation);
                                                } elseif(Auth::guard('admin')->user()->is_hod == '1'){
                                                    $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                                }
                                            })
                                            ->count();

            $totalOngoing = StudySchedule::where('is_active', 1)
                                           ->where('is_delete', 0)
                                           ->where('activity_status', 'ONGOING')
                                           ->where('scheduled_start_date', '!=', NULL)
                                           ->where('scheduled_end_date', '!=', NULL)
                                           ->where(function ($q) use ($activities, $brlocation, $multipleRoleActivities){
                                                if(Auth::guard('admin')->user()->is_hod != '1'){
                                                    $q->whereIn('activity_id', $activities)
                                                    ->whereIn('study_id',$brlocation);
                                                } elseif(Auth::guard('admin')->user()->is_hod == '1'){
                                                    $q->whereIn('responsibility_id', $multipleRoleActivities);  
                                                }
                                            })
                                           ->count();

            $totalDelay = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('activity_status', 'DELAY')
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->where(function ($q) use ($activities, $brlocation, $multipleRoleActivities){
                                                if(Auth::guard('admin')->user()->is_hod != '1'){
                                                    $q->whereIn('activity_id', $activities)
                                                    ->whereIn('study_id',$brlocation);
                                                } elseif(Auth::guard('admin')->user()->is_hod == '1'){
                                                    $q->whereIn('responsibility_id', $multipleRoleActivities);   
                                                }
                                            })
                                          ->count();
        
        } else {
            $totalCompleted = StudySchedule::where('is_active', 1)
                                           ->where('is_delete', 0)
                                           ->where('activity_status', 'COMPLETED')
                                           ->where('scheduled_start_date', '!=', NULL)
                                           ->where('scheduled_end_date', '!=', NULL)
                                           ->whereIn('activity_id', $activities)
                                           ->count();

            $totalUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('activity_status', 'UPCOMING')
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->whereIn('activity_id', $activities)
                                          ->count();

            $totalOngoing = StudySchedule::where('is_active', 1)
                                         ->where('is_delete', 0)
                                         ->where('activity_status', 'ONGOING')
                                         ->where('scheduled_start_date', '!=', NULL)
                                         ->where('scheduled_end_date', '!=', NULL)
                                         ->whereIn('activity_id', $activities)
                                         ->count();

            $totalDelay = StudySchedule::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('activity_status', 'DELAY')
                                       ->where('scheduled_start_date', '!=', NULL)
                                       ->where('scheduled_end_date', '!=', NULL)
                                       ->whereIn('activity_id', $activities)
                                       ->count();
        }

        return view('admin.dashboard.dashboard', compact('totalCompletedStudy', 'totalOngoingStudy', 'totalUpcomingStudy', 'totalCompleted','totalPreCompleted', 'totalUpcoming','totalPreUpcoming', 'totalOngoing','totalPreOngoing', 'totalDelay', 'totalPreDelay', 'pmCount', 'graphName', 'graphDelay'));
    }

    // Edit profile
    public function editProfile(){
        
        $profile = Admin::where('id',Auth::guard('admin')->user()->id)->first();

        return view('admin.dashboard.edit_profile',compact('profile')); 
    }

    // Update profile
    public function updateProfile(Request $request){
        
        $update = Admin::findOrFail(Auth::guard('admin')->user()->id);
        $update->name = $request->name;
        $update->email = $request->email;
        /*$update->mobile = $request->mobile_number;
        if(isset($request->profile_image)){
            $fileName = $this->uploadImage($request->profile_image,'profile');
            $update->profile_image = $fileName;
        }*/
        $update->save();

        return redirect(route('admin.dashboard'))->with('messages', [
            [
                'type' => 'success',
                'title' => 'Profile',
                'message' => 'Profile successfully updated!',
            ],
        ]);
    }

    // Change password
    public function changeAdminPassword(){

        return view('admin.dashboard.change_password');
    }

    // Update password
    public function updateAdminPassword(Request $request){

        $this->validate($request, [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);

        $adminId = Auth::guard('admin')->user()->id;
        $user = Admin::where('id', '=', $adminId)->first();

        if(Hash::check($request->old_password,$user->password)){

            $users = Admin::findOrFail($adminId);
            $users->password = Hash::make($request->new_password);
            $users->save();

            return redirect(route('admin.dashboard'))->with('messages', [
                [
                    'type' => 'success',
                    'title' => 'Password',
                    'message' => 'Password Successfully changed',
                ],
            ]); 

        } else {
          
            return redirect(route('admin.changeAdminPassword'))->with('messages', [
                [
                    'type' => 'error',
                    'title' => 'Password',
                    'message' => 'Plese check your current password',
                ],
            ]); 
        }
    }

    public function changeDashboardView(Request $request){

        $pm = array();
        $studySchedule = Study::where('is_active', 1)
                                ->where('is_delete', 0)
                                ->with('projectManager')
                                ->whereHas('projectManager', function($q){
                                    $q->where('is_active',1);
                                })
                                ->withcount('scheduleDelay')
                                ->get();

        $pm = array();
        $delayCount = array();
        if (!is_null($studySchedule)) {
            foreach ($studySchedule as $sk => $sv) {
                $pm[] = $sv->projectManager->name;
                $delayCount[$sv->projectManager->name][] = $sv->schedule_delay_count;
            }
        }

        $pmData = array();
        if (!is_null($delayCount)) {
            foreach ($delayCount as $key => $value) {
                $pmData[$key] = array_sum($value);
            }
        }

        $graphName = array();
        if (!is_null($pmData)) {
            foreach ($pmData as $key => $value) {
                $graphName[] = $key;
            }
        }

        $graphDelay = array();
        if (!is_null($pmData)) {
            foreach ($pmData as $key => $value) {
                $graphDelay[] = $value;
            }
        }
  
        $pmCount = count($graphName);

        if ($request->id == 'ALL') {

            // Study nos queries
            $totalCompletedStudy = Study::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('study_status', 'COMPLETED')
                                        ->count();

            /*$totalOngoingStudy = Study::where('is_active', 1)->where('is_delete', 0)->where('study_status', 'ONGOING')->count();*/
            $totalOngoingStudy = Study::where('is_active', 1)
                                      ->where('is_delete', 0)
                                      ->where('study_status', 'ONGOING')
                                      ->whereHas('schedule', function($q) {
                                            $q->whereNotNull('scheduled_start_date');
                                      })
                                      ->count();

            $totalUpcomingStudy = Study::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('study_status', 'UPCOMING')
                                       ->whereHas('schedule', function($q) {
                                            $q->whereNotNull('scheduled_start_date');
                                       })
                                       ->count();

            // Activity nos queries
            $totalPreCompleted = StudySchedule::where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->where('scheduled_start_date', '!=', NULL)
                                            ->where('scheduled_end_date', '!=', NULL)
                                            ->where('activity_status', 'COMPLETED')
                                            ->where('activity_type',123)
                                            ->count();

            $totalCompleted = StudySchedule::where('is_active', 1)
                                            ->where('is_delete', 0)
                                            ->where('scheduled_start_date', '!=', NULL)
                                            ->where('scheduled_end_date', '!=', NULL)
                                            ->where('activity_status', 'COMPLETED')
                                            ->whereIn('activity_type',[113,114,115,116])
                                            ->count();

            $totalPreUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('activity_status', 'UPCOMING')
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->where('activity_type',123)
                                          ->count();

            $totalUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('activity_status', 'UPCOMING')
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->whereIn('activity_type',[113,114,115,116])
                                          ->count();

            $totalPreOngoing = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'ONGOING')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->where('activity_type',123)
                                        ->count();

            $totalOngoing = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'ONGOING')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->whereIn('activity_type',[113,114,115,116])
                                        ->count();

            $totalPreDelay = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'DELAY')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->where('activity_type',123)
                                        ->count();

            $totalDelay = StudySchedule::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('activity_status', 'DELAY')
                                        ->where('scheduled_start_date', '!=', NULL)
                                        ->where('scheduled_end_date', '!=', NULL)
                                        ->whereIn('activity_type',[113,114,115,116])
                                        ->count();

        return view('admin.dashboard.dashboard',compact('totalCompletedStudy', 'totalOngoingStudy', 'totalUpcomingStudy', 'totalPreCompleted' ,'totalCompleted', 'totalUpcoming', 'totalPreUpcoming', 'totalOngoing', 'totalPreOngoing', 'totalDelay' , 'totalPreDelay', 'pmCount', 'graphName', 'graphDelay'));

        } else {

            $userId = $request->id;
            $totalCompletedStudy = Study::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('study_status', 'COMPLETED')
                                        ->where('project_manager', $request->id)
                                        ->count();

            /*$totalOngoingStudy = Study::where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->where('study_status', 'ONGOING')
                                        ->where('project_manager', $request->id)
                                        ->count();*/

            $totalOngoingStudy = Study::where('is_active', 1)
                                      ->where('is_delete', 0)
                                      ->where('study_status', 'ONGOING')
                                      ->where('project_manager', $request->id)
                                      ->whereHas('schedule', function($q) {
                                            $q->whereNotNull('scheduled_start_date');
                                        })
                                      ->count();

            $totalUpcomingStudy = Study::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('study_status', 'UPCOMING')
                                       ->where('project_manager', $request->id)
                                       ->count();

            $studies = Study::where('is_active', 1)
                            ->where('is_delete', 0)
                            //->where('study_status', 'ONGOING')
                            ->where('project_manager', $request->id)
                            ->get();

            $id = array();
            if (!is_null($studies)) {
                foreach ($studies as $sk => $sv) {
                    $id[] = $sv->id;
                }
            }

            // Activity nos queries

            $totalPreCompleted = StudySchedule::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('scheduled_start_date', '!=', NULL)
                                       ->where('scheduled_end_date', '!=', NULL)
                                       ->where('activity_status', 'COMPLETED')
                                       ->whereIn('study_id', $id)
                                       ->where('activity_type',123)
                                       ->count();
        
            $totalCompleted = StudySchedule::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('scheduled_start_date', '!=', NULL)
                                       ->where('scheduled_end_date', '!=', NULL)
                                       ->where('activity_status', 'COMPLETED')
                                       ->whereIn('study_id', $id)
                                       ->whereIn('activity_type',[113,114,115,116])
                                       ->count();

            $totalPreUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->where('activity_status', 'UPCOMING')
                                          ->whereIn('study_id', $id)
                                          ->where('activity_type',123)
                                          ->count();

            $totalUpcoming = StudySchedule::where('is_active', 1)
                                          ->where('is_delete', 0)
                                          ->where('scheduled_start_date', '!=', NULL)
                                          ->where('scheduled_end_date', '!=', NULL)
                                          ->where('activity_status', 'UPCOMING')
                                          ->whereIn('study_id', $id)
                                          ->whereIn('activity_type',[113,114,115,116])
                                          ->count();

            $totalPreOngoing = StudySchedule::where('is_active', 1)
                                         ->where('is_delete', 0)
                                         ->where('scheduled_start_date', '!=', NULL)
                                         ->where('scheduled_end_date', '!=', NULL)
                                         ->where('activity_status', 'ONGOING')
                                         ->whereIn('study_id', $id)
                                         ->where('activity_type',123)
                                         ->count();

            $totalOngoing = StudySchedule::where('is_active', 1)
                                         ->where('is_delete', 0)
                                         ->where('scheduled_start_date', '!=', NULL)
                                         ->where('scheduled_end_date', '!=', NULL)
                                         ->where('activity_status', 'ONGOING')
                                         ->whereIn('study_id', $id)
                                         ->whereIn('activity_type',[113,114,115,116])
                                         ->count();

            $totalPreDelay = StudySchedule::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('scheduled_start_date', '!=', NULL)
                                       ->where('scheduled_end_date', '!=', NULL)
                                       ->where('activity_status', 'DELAY')
                                       ->whereIn('study_id', $id)
                                       ->where('activity_type',123)
                                       ->count();

            $totalDelay = StudySchedule::where('is_active', 1)
                                       ->where('is_delete', 0)
                                       ->where('scheduled_start_date', '!=', NULL)
                                       ->where('scheduled_end_date', '!=', NULL)
                                       ->where('activity_status', 'DELAY')
                                       ->whereIn('study_id', $id)
                                       ->whereIn('activity_type',[113,114,115,116])
                                       ->count();

            $html = view('admin.dashboard.personal_dashboard',compact('totalCompletedStudy', 'totalOngoingStudy', 'totalUpcomingStudy', 'totalCompleted', 'totalPreCompleted', 'totalUpcoming', 'totalPreUpcoming', 'totalOngoing', 'totalPreOngoing', 'totalDelay', 'totalPreDelay', 'pmCount', 'graphName', 'graphDelay'))->render();
        
            return response()->json(['html'=>$html]);
        }
        
    }

}
