<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\View\VwPreStudyProjection;
use Mail;
use Log;

class PreStudyPlannedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:weeklyPreStudyPlannedData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Current week pre study activities mail every monday 09:00';

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
        $thisWeekRange = [date('Y-m-d'), date('Y-m-d', strtotime('+6 day'))];
        $nextWeekRange = [date('Y-m-d', strtotime('+7 day')), date('Y-m-d', strtotime('+13 day'))];
        
        $preStudyCurrentWeek = VwPreStudyProjection::with([
                                                        'studyNo'=> function($q) use($thisWeekRange){
                                                            $q->with([
                                                                'crLocationName',
                                                                'projectManager',
                                                                'studyScope',
                                                                'sponsorName',
                                                                'drugDetails',
                                                                'studyType',
                                                                'studyRegulatory' => function($q){
                                                                    $q->with(['paraSubmission']);
                                                                },
                                                            ]);
                                                        },
                                                    ])
                                                    ->whereHas(
                                                        'studyNo', function($q) use($thisWeekRange){
                                                            $q->whereBetween('tentative_clinical_date', [$thisWeekRange[0], $thisWeekRange[1]])
                                                              ->where('study_slotted', 'YES');
                                                        },
                                                    )
                                                   ->get();

        $preStudyNextWeek = VwPreStudyProjection::with([
                                                    'studyNo'=> function($q) use($nextWeekRange){
                                                        $q->with([
                                                            'crLocationName',
                                                            'projectManager',
                                                            'studyScope',
                                                            'sponsorName',
                                                            'drugDetails',
                                                            'studyType',
                                                            'studyRegulatory' => function($q){
                                                                $q->with(['paraSubmission']);
                                                            },
                                                        ]);
                                                    },
                                                ])
                                                ->whereHas(
                                                    'studyNo', function($q) use($nextWeekRange){
                                                        $q->whereBetween('tentative_clinical_date', [$nextWeekRange[0], $nextWeekRange[1]])
                                                          ->where('study_slotted', 'YES');
                                                    },
                                                )
                                               ->get();

        $currentWeekTotalSubject = 0;
        $currentWeekTotalMale = 0;
        $currentWeekTotalFemale = 0;
        if(!is_null($preStudyCurrentWeek)){
            foreach($preStudyCurrentWeek as $pck => $pcv){
                $currentWeekTotalSubject += $pcv->studyNo->no_of_subject;
                $currentWeekTotalMale += $pcv->studyNo->no_of_male_subjects;
                $currentWeekTotalFemale += $pcv->studyNo->no_of_female_subjects;
            }
        }

        $nextWeekTotalSubject = 0;
        $nextWeekTotalMale = 0;
        $nextWeekTotalFemale = 0;
        if(!is_null($preStudyNextWeek)){
            foreach($preStudyNextWeek as $pnk => $pnv){
                $nextWeekTotalSubject += $pnv->studyNo->no_of_subject;
                $nextWeekTotalMale += $pnv->studyNo->no_of_male_subjects;
                $nextWeekTotalFemale += $pnv->studyNo->no_of_female_subjects;
            }
        }

        $subject = 'SMS – Pre-study Projection of Current & Next Week';
        //$name = $dv->name;
        $name = 'Team';
        $email = ['Ajay.Tandon@veedacr.com', 'Ranvirsingh.Rana@veedacr.com', 'Sailendra.Goswami@veedacr.com', 'Pranav.Dalal@veedacr.com', 'Lalit.T2506@veedacr.com'];
        //$email = $dv->email;
        $bccEmail = ['Chandresh.V2590@veedacr.com', 'sani.c2654@veedacr.com', 'Sandip.C2291@veedacr.com'];

        Mail::send('admin.mail.pre_study_current_next_week_mail', ['currentWeek' => $preStudyCurrentWeek, 'nextWeek' => $preStudyNextWeek, 'name' => $name, 'crtWeekTotal' => $currentWeekTotalSubject, 'crtWeekMale' => $currentWeekTotalMale, 'crtWeekFemale' => $currentWeekTotalFemale, 'nxtWeekTotal' => $nextWeekTotalSubject, 'nxtWeekMale' => $nextWeekTotalMale, 'nxtWeekFemale' => $nextWeekTotalFemale], function ($message) use ($email,$subject,$bccEmail) {
            $message->from('sms@veedacr.com', 'Study Management System');
            //$message->cc($ccemail);
            $message->bcc($bccEmail);
            $message->subject($subject);
            $message->to($email);
        });
        Log::info($email);
    }
}
