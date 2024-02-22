<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Mail\Mailer;

class SendEmailOnStudyScheduleCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $name;
    private $studyNo;
    private $projectManager;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$name,$studyNo,$projectManager)
    {
        $this->email = $email;
        $this->name = $name;
        $this->studyNo = $studyNo;
        $this->projectManager = $projectManager;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $data['email'] = $this->email;
        $data['name'] = $this->name;
        $data['studyNo'] = $this->studyNo;
        $data['projectManager'] = $this->projectManager;

        //$email = $this->email;
        $email = 'sani.c2654@veedacr.com';
        $bccEmail = ['chandresh.v2590@veedacr.com', 'sani.c2654@veedacr.com'];

        $subject = 'Study Management System - Study Schedule Created';
        
        //if(env('APP.ENV') == 'Production'){
            $mailer->send('admin.mail.study_schedule_created', ['data' => $data], function ($message) use ($email,$subject,$bccEmail) {
                $message->from('sms@veedacr.com', 'Study Management System');
                $message->bcc($bccEmail);
                $message->subject($subject);
                $message->to($email);
            });
        //}
    }
}
