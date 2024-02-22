<?php

namespace App\Http\Controllers\Admin\Study;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalController;
use App\Models\StudyActivityMetadata;
use Illuminate\Http\Request;
use App\Exports\StudyMetaDataExport;
use Maatwebsite\Excel\Facades\Excel;

class StudyActivityMetadataController extends GlobalController
{
    public function __construct() {
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    // All activity metadata list
    public function allStudiesActivityMetadataList(Request $request){

        $filter = 0;
        $studyNo = '';
        $activityNames = '';
        $studyId = '';
        $activityId = '';
        $fromDate = '';
        $toDate = '';

        $perPage = 25;
        if($request->page != ''){
            $page = base64_decode($request->query('page', base64_decode(1)));
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $studyNo = StudyActivityMetadata::select('id', 'study_schedule_id')
                                        ->with([
                                            'studySchedule' => function($q) {
                                                $q->select('id', 'study_id')
                                                  ->groupBy('study_id')
                                                  ->with([
                                                    'studyNo' => function($q) {
                                                        $q->select('id', 'study_no')
                                                          ->where('is_active', 1)
                                                          ->where('is_delete', 0);
                                                    },
                                                ])
                                                ->where('is_active', 1)
                                                ->where('is_delete', 0);
                                            }
                                        ])
                                        ->where('is_active', 1)
                                        ->where('is_delete', 0)
                                        ->groupBy('study_schedule_id')
                                        ->orderBy('id', 'DESC')
                                        ->get();

        $activityNames = StudyActivityMetadata::select('id', 'study_schedule_id')
                                              ->with([
                                                   'studySchedule' => function($q) {
                                                       $q->select('id', 'activity_id')
                                                         ->groupBy('activity_id')
                                                         ->with([
                                                                'activityMaster' => function($q) {
                                                                    $q->select('id', 'activity_name')
                                                                      ->where('is_active', 1)
                                                                      ->where('is_delete', 0);
                                                                },
                                                           ])
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0);
                                                    }
                                                ])
                                               ->where('is_active', 1)
                                               ->where('is_delete', 0)
                                               ->groupBy('study_schedule_id')
                                               ->orderBy('id', 'DESC')
                                               ->get();

        $query = StudyActivityMetadata::select('id', 'study_schedule_id', 'activity_meta_id', 'actual_value');

        if((isset($request->study_no)) && ($request->study_no != '')) {
            $filter = 1;
            $studyId = $request->study_no;
            $query->whereHas('studySchedule', function($q) use ($request) {
                $q->where('study_id', $request->study_no);
            });
        }

        if((isset($request->activity_name)) && ($request->activity_name != '')) {
            $filter = 1;
            $activityId = $request->activity_name;
            $query->whereHas('studySchedule', function($q) use ($request) {
                $q->where('activity_id', $request->activity_name);
            });
        }

        if($request->from_date != '' && $request->to_date != ''){
            $filter = 1;
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $query->whereHas('studySchedule', function($q) use ($request,$fromDate,$toDate) {
                $q->whereBetween('actual_end_date',array($this->convertDateTime($fromDate),$this->convertDateTime($toDate)));
            });
        }

        $allActivityMetadataList = $query->with([
                                            'activityMetadata' => function($q) {
                                                $q->select('id', 'activity_id', 'source_value', 'source_question','is_activity')
                                                    ->with([
                                                        'activityName' => function($q) {
                                                            $q->select('id','activity_name')
                                                              ->where('is_active', 1)
                                                              ->where('is_delete', 0);
                                                        },
                                                    ])
                                                    ->where('is_active', 1)
                                                    ->where('is_delete', 0);
                                            },
                                            'studySchedule' => function($q) {
                                                $q->select('id', 'study_id','activity_id','actual_start_date','actual_end_date', 'period_no')
                                                  ->with([
                                                        'studyNo' => function($q) {
                                                            $q->select('id', 'study_no')
                                                              ->where('is_active', 1)
                                                              ->where('is_delete', 0);
                                                        },
                                                    ])
                                                ->where('is_active', 1)
                                                ->where('is_delete', 0);
                                            },
                                        ])
                                      ->where('is_active', 1)
                                      ->where('is_delete', 0)
                                      ->orderBy('id', 'DESC')
                                      ->skip($offset)
                                      ->limit($perPage)
                                      ->get();

        $recordCount = StudyActivityMetadata::where('is_delete', 0)->count();
        $pageCount = ceil($recordCount / $perPage);
                                      
        return view('admin.study.study_metadata.all_studies_activity_metadata_list', compact('allActivityMetadataList', 'filter', 'studyNo', 'activityNames', 'studyId', 'activityId' , 'fromDate','toDate', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    // excel export and download
    public function exportStudyMetaData(){
        return Excel::download(new StudyMetaDataExport, 'All Study MetaData  Study Management System.xlsx');
    }
}
