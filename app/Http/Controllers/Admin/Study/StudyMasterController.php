<?php

namespace App\Http\Controllers\Admin\Study;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Study;
use App\Models\LocationMaster;
use App\Http\Controllers\GlobalController;
use App\Exports\StudyMasterExport;
use Maatwebsite\Excel\Facades\Excel;

class StudyMasterController extends GlobalController
{
    public function __construct(){
        $this->middleware('admin');
        $this->middleware('checkpermission');
    }

    public function studyMasterList(Request $request){

        $filter = 0;
        $crLocationName = '';
        $startDate = '';
        $endDate = '';

        $perPage = 25;
        if($request->page != ''){
            $page = $request->page;
        } else{
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $crLocation = LocationMaster::where('location_type', 'CRSITE')
                                    ->where('is_active', 1)
                                    ->where('is_delete', 0)
                                    ->get();

        $query = Study::where('is_active', 1)->where('is_delete', 0);
        $queryCount = Study::where('is_active', 1)->where('is_delete', 0);

        if(isset($request->cr_location) && $request->cr_location != ''){
            $filter = 1;
            $crLocationName = $request->cr_location;
            $query->where('cr_location',$crLocationName);
            $queryCount->where('cr_location',$crLocationName);
        }

        /*if($request->start_date != '' && $request->end_date != ''){
            $filter = 1;
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereHas('checkIn', function($q) use($startDate,$endDate){ $q->whereBetween('scheduled_end_date',array($this->convertDt($startDate),$this->convertDt($endDate)));});
        }*/

        if($request->start_date != '' && $request->end_date != ''){
            $filter = 1;
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('created_at', array($this->convertDt($startDate),$this->convertDt($endDate)));
            $queryCount->whereBetween('created_at', array($this->convertDt($startDate),$this->convertDt($endDate)));
        }

        $studies = $query->select('id', 'study_no', 'sponsor_study_no', 'project_manager', 'sponsor', 'no_of_subject', 'cr_location', 
                            'global_priority_no', 'no_of_female_subjects', 'cdisc_require')
                        ->with([
                                'sponsorName' => function($q){
                                    $q->select('id', 'sponsor_name');
                                },
                                'projectManager' => function($q){
                                    $q->select('id', 'name');
                                },
                                'studyRegulatory' =>function($q){
                                    $q->select('id', 'regulatory_submission', 'project_id')
                                      ->with([
                                        'paraSubmission' => function($q){
                                            $q->select('id', 'para_value');
                                        }
                                    ]);
                                },
                                'drugDetails' => function($q) {
                                    $q->select('id', 'study_id', 'drug_id', 'dosage_form_id', 'uom_id', 'dosage', 'type')->with([
                                        'drugName' => function($q){
                                            $q->select('id', 'drug_name');
                                        },
                                        'drugDosageName' => function($q){
                                            $q->select('id', 'para_value');
                                        },
                                        'drugUom' => function($q){
                                            $q->select('id', 'para_value');
                                        },
                                        'drugType' => function($q){
                                            $q->select('id', 'manufacturedby', 'type');
                                        },
                                    ]);
                                },
                                'checkIn' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'lastSample' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'CRtoQA' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'QAtoCR' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'BRtoQA' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'QAtoBR' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'BRtoPB' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'PBtoQA' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'QAtoPB' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'darftToSponsor' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_end_date', 'actual_end_date');
                                },
                                'bioanalyticalStartEnd' => function($q){
                                    $q->select('id', 'study_id', 'scheduled_start_date', 'actual_start_date', 'scheduled_end_date', 'actual_end_date');
                                },
                            ])
                        ->orderBy('global_priority_no', 'ASC')
                        ->skip($offset)
                        ->limit($perPage)
                        ->get();

        $recordCount = $queryCount->count();
        $pageCount = ceil($recordCount / $perPage);

        return view('admin.study.study_master.all_study_master_list', compact('studies', 'crLocation', 'crLocationName', 'filter', 'startDate', 'endDate', 'pageCount', 'offset' , 'page', 'recordCount', 'perPage'));
    }

    // excel export and download
    public function exportStudyMaster(){
        return Excel::download(new StudyMasterExport, 'All Study Master Study Management System.xlsx');
    }
}
