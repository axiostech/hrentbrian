<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendArcFormEmail;
use App\Traits\FilterPhoneNumber;
use App\Traits\GenerateArcUrlContent;
use App\Traits\SendWhatsapp;
use App\Traits\Sms;
use App\Arc;


class SendArcForm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Sms, SendWhatsapp, FilterPhoneNumber, GenerateArcUrlContent;

    protected $arc_id;
    protected $channel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arc_id, $channel = null)
    {
        $this->arc_id = $arc_id;
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd($this->arc_id, $this->channel);

        $arc = Arc::findOrFail($this->arc_id);

        $url = $this->generateArcUrl($arc->id);

        $phone_number_noplus = $this->removePhoneNumberPlus($arc->tenancy->tenant->phone_number);
        // dd($phone_number_noplus);

        $message = $this->generateArcMessage($arc->id, $url);

        switch($this->channel) {
            case 'sms':
                $this->sendSms($phone_number_noplus, $message);
                break;
            case 'email':
                if($arc->tenancy->tenant->email) {
                    Mail::to($arc->tenancy->tenant->email)->send(new SendArcFormEmail($arc->id, $url));
                }
                break;
            case 'whatsapp':
                break;
            default:
                $this->sendSms($phone_number_noplus, $message);
                if($arc->tenancy->tenant->email) {
                    Mail::to($arc->tenancy->tenant->email)->send(new SendArcFormEmail($arc->id, $url));
                }
        }
    }
}
