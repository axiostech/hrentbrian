<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class SendLoginCredentialEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $user_id;
    protected $temporary_password;
    protected $login_type;
/*
    login type
    1 = email
    2 = phone_number */

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_id, $temporary_password, $login_type)
    {
        $this->user_id = $user_id;
        $this->temporary_password = $temporary_password;
        $this->login_type = $login_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = User::findOrFail($this->user_id);
        return $this->subject('Happy Rent Login Credential')
                    ->view('email.login_credential')
                    ->with([
                        'user' => $user,
                        'temporary_password' => $this->temporary_password,
                        'login_type' => $this->login_type
                    ]);
    }
}
