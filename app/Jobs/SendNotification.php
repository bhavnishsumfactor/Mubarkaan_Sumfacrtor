<?php

namespace App\Jobs;
use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $data;
    protected $sendEmail;
    protected $sendNotification;
    protected $sendSms;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notificationData, $sendEmail = true, $sendNotification = false, $sendSms = false)
    {
        $this->data = $notificationData;
        $this->sendEmail = $sendEmail;
        $this->sendNotification = $sendNotification;
        $this->sendSms = $sendSms;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
  
        Notification::sendNotification($this->data, $this->sendEmail, $this->sendNotification, $this->sendSms);
    }
}
