<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pw)
    {
        $this->pw = $pw;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('bilgi@garantiliteknoloji.com')
            ->subject('Şifre Güncelleme')
            ->with([
                //  'name' => 'ulaş körpe',

            ])
            ->view('email.forget_pw',[ 'pw'=>$this->pw]);

        // return $this->view('view.name');
    }
}
