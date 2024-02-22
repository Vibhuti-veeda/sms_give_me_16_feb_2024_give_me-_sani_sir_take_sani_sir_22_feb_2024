<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use Log;
use App\View\VwStudyactivities;

class PmDailyActivitiesList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:pmDailyActivitiesList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check today activity & send mail to PM on every day morning 10:00';

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
        
        $users = VwStudyactivities::select("*")->where('scheduled_start_date', date('Y-m-d'))->groupBy('project_manager')->get();

        if (!is_null($users)) {
            foreach ($users as $dk => $dv) {

                $delayPreActivities = VwStudyactivities::where('project_manager',$dv->project_manager)
                                                    ->where('activity_status', 'ONGOING')
                                                    ->where('activity_type',123)
                                                    ->where('scheduled_start_date', date('Y-m-d'))
                                                    ->get();

                $delayPostActivities = VwStudyactivities::where('project_manager',$dv->project_manager)
                                                    ->where('activity_status', 'ONGOING')
                                                    ->whereIn('activity_type',[113,114,115,116])
                                                    ->where('scheduled_start_date', date('Y-m-d'))
                                                    ->get();

                $subject = 'Study Management System - Planned Activities for Today';
                $name = $dv->name;
                //$email = 'sani.c2654@veedacr.com';
                $email = $dv->email;
                $bccEmail = ['chandresh.v2590@veedacr.com', 'sani.c2654@veedacr.com'];

                Mail::send('admin.mail.pm_daily_activities_list', ['preActivities' => $delayPreActivities, 'postActivities' => $delayPostActivities, 'name' => $name], function ($message) use ($email,$subject,$bccEmail) {
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
