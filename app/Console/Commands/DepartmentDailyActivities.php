<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\View\DepartmentActivities;
use Log;
use Mail;

class DepartmentDailyActivities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dailyDepartmentActivities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Department wise daily schedule & delay activities list send to user on mail on everyday at 10:10';

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
    public function handle()
    {
        $users = DepartmentActivities::groupBy('name')->get();

        if (!is_null($users)) {
            foreach ($users as $uk => $uv) {

                $delayActivities = DepartmentActivities::where('user_id',$uv->user_id)
                                                        ->where('activity_status', 'DELAY')
                                                        ->orderBy('scheduled_start_date', 'ASC')
                                                        //->whereNull('location_name')
                                                        ->get();

                $dailyActivities = DepartmentActivities::where('user_id',$uv->user_id)
                                                        ->where('activity_status', 'ONGOING')
                                                        ->where('scheduled_start_date', date('Y-m-d'))
                                                        //->whereNull('location_name')
                                                        ->get();

                $subject = 'Study Management System - Activities List';
                $name = $uv->name;
                //$email = 'sani.c2654@veedacr.com';
                $email = $uv->email;
                $bccEmail = ['chandresh.v2590@veedacr.com', 'sani.c2654@veedacr.com'];

                Mail::send('admin.mail.department_activities_list', ['data' => $delayActivities, 'name' => $name, 'daily' => $dailyActivities], function ($message) use ($email,$subject,$bccEmail) {
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
