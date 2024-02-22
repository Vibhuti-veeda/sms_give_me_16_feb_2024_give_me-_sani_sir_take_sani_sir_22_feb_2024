<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudySchedule;
use Carbon\Carbon;
use Log;

class ActivityStatusAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dailyActivityStatusAnalysis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check daily activity status';

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
        //$studies = StudySchedule::where('study_id', base64_decode($id))->get();
        $studies = StudySchedule::where('is_active', 1)
                                ->where('is_delete', 0)
                                ->whereNotNull('scheduled_start_date')
                                ->whereNotIn('activity_status', ['COMPLETED'])
                                ->get();
        
        $date = Carbon::now();
        $currentDate = date('Y-m-d', strtotime($date));

        if (!is_null($studies)) {
            foreach ($studies as $sk => $sv) {
                
                if (($currentDate > $sv->scheduled_start_date) && ($sv->scheduled_start_date != '') && ($sv->scheduled_end_date != '') && ($sv->actual_start_date == '') && ($sv->actual_end_date == '')) {
                    $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'DELAY']);
                    log::info([$currentDate, $sv->scheduled_start_date, $sv->study_id, 'DELAY']);
                } else if (($sv->scheduled_start_date == $currentDate) && ($sv->actual_start_date == '') && ($sv->actual_end_date == '') && ($sv->scheduled_start_date != '') && ($sv->scheduled_end_date != '')) {
                    $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'ONGOING']);
                    log::info([$currentDate, $sv->scheduled_start_date, $sv->study_id, 'ONGOING']);
                } else if (($sv->scheduled_start_date > $currentDate) && ($sv->scheduled_end_date > $currentDate) && ($sv->actual_start_date != '') && ($sv->actual_end_date == '') && ($sv->scheduled_start_date != '') && ($sv->scheduled_end_date != '')) {
                    $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'ONGOING']);
                    log::info([$currentDate, $sv->scheduled_start_date, $sv->study_id, 'ONGOING']);
                } else if (($sv->scheduled_start_date <= $sv->actual_start_date) && ($sv->scheduled_end_date < $currentDate) && ($sv->scheduled_start_date != '') && ($sv->scheduled_end_date != '') && ($sv->actual_start_date != '') && ($sv->actual_end_date == '')) {
                    $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'DELAY']);
                    log::info([$currentDate, $sv->scheduled_start_date, $sv->study_id, 'DELAY']);
                } else if (($sv->scheduled_start_date > $sv->actual_start_date) && ($sv->scheduled_end_date < $currentDate) && ($sv->scheduled_start_date != '') && ($sv->scheduled_end_date != '') && ($sv->actual_start_date != '') && ($sv->actual_end_date == '')) {
                    $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'DELAY']);
                    log::info([$currentDate, $sv->scheduled_start_date, $sv->study_id, 'DELAY']);
                } else if (($currentDate < $sv->scheduled_start_date) && ($sv->scheduled_start_date != '') && ($sv->scheduled_end_date != '') && ($sv->actual_start_date == '') && ($sv->actual_end_date == '')) {
                    $updateActivityStatus = StudySchedule::where('id', $sv->id)->update(['activity_status' => 'UPCOMING']);
                    log::info([$currentDate, $sv->scheduled_start_date, 'UPCOMING']);
                }

            }
        }
    }
}
