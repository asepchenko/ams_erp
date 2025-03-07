<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class financeNotifMail extends Mailable
{
    use Queueable, SerializesModels;
  
    public $details;
   
    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->subject('Finance Notification')
                    ->view('emails.finance_notify');
    }
}
