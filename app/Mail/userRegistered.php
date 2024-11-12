<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class userRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $body;
    public $attach = null;
    public $from_mail  = [];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$data)
    {
        $this->subject = $subject;
        $this->data = $data;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->markdown('mails.createdUser', $this->data)
        ->subject($this->subject);        
      return $this;
    }
}
