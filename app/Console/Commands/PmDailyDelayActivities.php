<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use Log;
use App\Models\Admin;
use App\Models\Study;
use App\View\VwStudyactivities;

class PmDailyDelayActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:pmDailyDelayActivities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check till date delay activity & send mail to them on every day morning 10:05';

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

        $users = VwStudyactivities::select("*")->where('activity_status','DELAY')->groupBy('project_manager')->get();

        if (!is_null($users)) {
            foreach ($users as $dk => $dv) {

                $delayPreActivities = VwStudyactivities::where('project_manager',$dv->project_manager)
                                                    ->where('activity_status', 'DELAY')
                                                    ->where('activity_type',123)
                                                    ->orderBy('scheduled_start_date', 'ASC')
                                                    ->get();

                $delayPostActivities = VwStudyactivities::where('project_manager',$dv->project_manager)
                                                    ->where('activity_status', 'DELAY')
                                                    ->whereIn('activity_type',[113,114,115,116])
                                                    ->orderBy('scheduled_start_date', 'ASC')
                                                    ->get();

                $subject = 'Study Management System - Delay Activities';
                $name = $dv->name;
                //$email = 'sani.c2654@veedacr.com';
                $email = $dv->email;
                $bccEmail = ['chandresh.v2590@veedacr.com', 'sani.c2654@veedacr.com'];

                Mail::send('admin.mail.pm_delay_activities_list', ['preActivities' => $delayPreActivities,'postActivities' => $delayPostActivities, 'name' => $name], function ($message) use ($email,$subject,$bccEmail) {
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
