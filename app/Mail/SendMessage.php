<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $nom;
    public $remarque;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$nom,$remarque)
    {
        $this->email    = $email;
        $this->nom      = $nom;
        $this->remarque = $remarque;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact')
            ->from('info@rjne.ch')
            ->cc('cindy.leschaud@gmail.com')
            ->subject('Demande depuis le site rjne.ch');
    }
}
