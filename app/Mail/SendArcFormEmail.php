<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Arc;
use DB;

class SendArcFormEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $arc_id;
    protected $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($arc_id, $url)
    {
        $this->arc_id = $arc_id;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $arc = Arc::findOrFail($this->arc_id);

        return $this->subject('Auto Rental Collection (ARC) Approval')
                    ->view('email.arc_form')
                    ->with([
                        'arc' => $arc,
                        'url' => $this->url
                    ]);
    }
}
