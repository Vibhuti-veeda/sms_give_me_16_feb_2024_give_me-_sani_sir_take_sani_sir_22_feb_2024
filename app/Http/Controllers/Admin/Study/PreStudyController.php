<?php

namespace App\Http\Controllers\Admin\Study;

use App\Http\Controllers\Controller;
use App\Models\StudyTrail;
use App\Models\Admin;
use App\Models\LocationMaster;
use App\Models\StudySchedule;
use App\Models\Study;
use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use App\View\VwPreStudyProjection;
use App\View\VwPostStudyProjection;

class PreStudyController extends GlobalController
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }
 
    public function preStudyProjectionList(Request $request){

        $filter = 0;
        $startMonth = '';
        $endMonth = '';
        
        if((isset($request->start_date) && isset($request->end_date)) && ($request->start_date != '') && ($request->end_date != '')){
            $start = $request->start_date;
            $end = $request->end_date;

            $startDate = $start;
            $endDate = $end;
        } else {
            $currentDate = date('d-m-Y');
            $start = date('01-m-Y', strtotime($currentDate));
            $end = date('t-m-Y', strtotime($currentDate));
            
            $startDate = $this->convertDt($start);
            $endDate = $this->convertDt($end);
        }
 
        request()->session()->put('startDate', $startDate);
        request()->session()->put('endDate', $endDate);
 
        return view('admin.study.pre_study.pre_study_projection_list', compact('filter', 'start', 'end'));
 
    }
 
    public function getPreStudyProjectionList(){
 
        $startDate = request()->session()->get('startDate');
        $endDate = request()->session()->get('endDate');

        $preStudyProjection = VwPreStudyProjection::whereNotNull('study_no')
                                                 ->where('tentative_clinical_date','>=', $startDate)->where('tentative_clinical_date','<=', $endDate)
                                                 //->whereBetween('tentative_clinical_date', [$startDate, $endDate])
                                                 ->with([
                                                    'studyNo' => function($q){
                                                        $q->select('id', 'study_no', 'no_of_subject', 'no_of_male_subjects', 'no_of_female_subjects', 'cr_location', 'project_manager', 'sponsor', 'study_type', 'study_slotted', 'remark', 'projection_status')
                                                          ->with([
                                                            'crLocationName' => function($q){
                                                                $q->select('id', 'location_name');
                                                            },
                                                            'projectManager' => function($q){
                                                                $q->select('id', 'name', 'employee_code');
                                                            },
                                                            'sponsorName' => function($q){
                                                                $q->select('id', 'sponsor_name');
                                                            },
                                                            'studyType' => function($q){
                                                                $q->select('id', 'para_value');
                                                            },
                                                        ]);
                                                    },
                                                ])
                                               ->orderBy('tentative_clinical_date', 'ASC')
                                               ->get();
 
        return $preStudyProjection;
    }

    public function getPostStudyProjectionList(){

        $startDate = request()->session()->get('startDate');
        $endDate = request()->session()->get('endDate');

        $postStudyProjection = VwPostStudyProjection::whereNotNull('study_no')
                                                    ->where('check_in_date','>=', $startDate)->where('check_in_date','<=', $endDate)
                                                    ->with([
                                                        'studyNo' => function($q){
                                                            $q->select('id', 'study_no', 'no_of_subject', 'no_of_male_subjects', 'no_of_female_subjects', 'cr_location', 'project_manager', 'sponsor', 'study_type', 'study_slotted', 'remark')
                                                              ->with([
                                                                'crLocationName' => function($q){
                                                                    $q->select('id', 'location_name');
                                                                },
                                                                'projectManager' => function($q){
                                                                    $q->select('id', 'name', 'employee_code');
                                                                },
                                                                'sponsorName' => function($q){
                                                                    $q->select('id', 'sponsor_name');
                                                                },
                                                                'studyType' => function($q){
                                                                    $q->select('id', 'para_value');
                                                                },
                                                            ]);
                                                        },
                                                    ])
                                                   ->orderBy('check_in', 'ASC')
                                                   ->get();
 
        return $postStudyProjection;
    }
 
   
}