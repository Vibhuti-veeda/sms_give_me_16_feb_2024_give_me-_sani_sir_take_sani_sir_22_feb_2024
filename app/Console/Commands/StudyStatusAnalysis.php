<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StudySchedule;
use Carbon\Carbon;
use DB;
use App\Models\Study;
use Log;

class StudyStatusAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dailyStudyStatusAnalysis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check daily study status';

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
        $study = Study::where('is_active', 1)
                        ->where('is_delete', 0)
                        ->where('study_status', '!=','COMPLETED')
                        ->where('study_status', '!=','HOLD')
                        ->with([
                            'schedule' => function ($query) {
                                $query->whereNotNull('scheduled_start_date')
                                      ->select('study_id', DB::raw('MIN(scheduled_start_date) as min_schedule_date'))
                                      ->groupBy('study_id');
                            }
                        ])
                        ->get();

        if (!is_null($study)) {
            foreach ($study as $sk => $sv) {
                if (!is_null($sv->schedule)) {
                    foreach ($sv->schedule as $ssk => $ssv) {
                        $minScheduleDate = $ssv->min_schedule_date;
                        if ($minScheduleDate <= date('Y-m-d')) {
                            // The minimum schedule date is greater than or equal to the current date
                            $updateStudyStatus = Study::where('id', $sv->id)->update(['study_status' => 'ONGOING']);
                        } else {
                            // The minimum schedule date is earlier than the current date
                            $updateStudyStatus = Study::where('id', $sv->id)->update(['study_status' => 'UPCOMING']);
                        }
                    }
                }
            }
        }
    }
}
