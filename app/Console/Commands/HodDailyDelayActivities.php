<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use Log;
use App\Models\Admin;
use App\Models\StudySchedule;
use Carbon\Carbon;

class HodDailyDelayActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:hodDailyDelayActivitiesList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check current week activities & till now delay activities report mail send to Respective HOD on every monday morning 09:05';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){

        $users = Admin::where('is_active', 1)->where('is_delete', 0)->where('is_hod', 1)->get();

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        if (!is_null($users)) {
            foreach ($users as $uk => $uv) {
                $roleIds = explode(',', $uv->multiple_roles);

                $delayActivities = StudySchedule::select('id', 'study_id', 'activity_id', 'activity_name', 'activity_status', 'activity_type', 
                                                'scheduled_start_date', 'responsibility_id', 'scheduled_end_date')
                                               ->where('activity_status', '=', 'DELAY')
                                               ->whereIn('responsibility_id', $roleIds)      
                                               ->with([
                                                    'userName'=> function($q) {
                                                        $q->select('id','name','role_id','location_id')
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0)
                                                        ->where('is_hod',0);
                                                    },
                                                    'studyNo' => function($q) {
                                                        $q->select('id', 'study_no','cr_location','br_location')
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0)
                                                        ->with([
                                                            'crLocationName'=> function($q) {
                                                                $q->select('id','location_name')
                                                                ->where('is_active', 1)
                                                                ->where('is_delete', 0);
                                                            },
                                                            'brLocationName'=> function($q) {
                                                                $q->select('id','location_name')
                                                                ->where('is_active', 1)
                                                                ->where('is_delete', 0);
                                                            },
                                                        ]);
                                                    },
                                                    'activities' => function($q) {
                                                        $q->select('id','para_value')
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0);
                                                    },
                                                    'response' => function($q) {
                                                        $q->select('id','name')
                                                        ->where('is_active', 1)
                                                        ->where('is_delete', 0);
                                                    },
                                                ])   
                                               ->where('is_active', '=', 1)     
                                               ->where('is_delete', '=', 0)
                                               ->orderBy('scheduled_start_date', 'ASC')    
                                               ->get();

                $upComingActivities = StudySchedule::select('id', 'study_id', 'activity_id', 'activity_name', 'activity_status', 'activity_type', 'scheduled_start_date', 
                                                    'scheduled_end_date','responsibility_id')
                                                   ->where('activity_status', '=', 'UPCOMING')
                                                   ->whereIn('responsibility_id', $roleIds)
                                                   ->whereNotNull('scheduled_start_date')
                                                   ->whereBetween('scheduled_start_date',[$startOfWeek, $endOfWeek])       
                                                   ->with([
                                                        'userName'=> function($q) {
                                                            $q->select('id','name','role_id','location_id')
                                                            ->where('is_active', 1)
                                                            ->where('is_delete', 0)
                                                            ->where('is_hod',0);
                                                        },
                                                        'studyNo' => function($q) {
                                                            $q->select('id', 'study_no','cr_location','br_location')
                                                            ->where('is_active', 1)
                                                            ->where('is_delete', 0)
                                                            ->with([
                                                                'crLocationName'=> function($q) {
                                                                    $q->select('id','location_name')
                                                                    ->where('is_active', 1)
                                                                    ->where('is_delete', 0);
                                                                },
                                                                'brLocationName'=> function($q) {
                                                                    $q->select('id','location_name')
                                                                    ->where('is_active', 1)
                                                                    ->where('is_delete', 0);
                                                                },
                                                            ]);
                                                        },
                                                        'activities' => function($q) {
                                                            $q->select('id','para_value')
                                                            ->where('is_active', 1)
                                                            ->where('is_delete', 0);
                                                        },
                                                        'response' => function($q) {
                                                            $q->select('id','name')
                                                            ->where('is_active', 1)
                                                            ->where('is_delete', 0);
                                                        },
                                                    ])   
                                                   ->where('is_active', '=', 1)     
                                                   ->where('is_delete', '=', 0)
                                                   ->orderBy('scheduled_start_date', 'ASC')  
                                                   ->get();

                $subject = 'Study Management System - Delay Activities';
                $name = $uv->name;
                $email = 'sani.c2654@veedacr.com';
                //$email = $uv->email;
                $bccEmail = ['priya.c2657@veedacr.com', 'sani.c2654@veedacr.com'];

                Mail::send('admin.mail.hod_daily_delay_activities_list', ['delayActivities' => $delayActivities, 'upcomingActivities' => $upComingActivities, 'name' => $name, 'role' => $uv->role_id], function ($message) use ($email,$subject,$bccEmail) {
                    $message->from('sms@veedacr.com', 'Study Management System');
                    //$message->cc($ccemail);
                    $message->bcc($bccEmail);
                    $message->subject($subject);
                    $message->to($email);
                });
                Log::info($email);
            }
        }

    }
}
