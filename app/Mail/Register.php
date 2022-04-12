<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('bilgilendirme.form@garantili.com.tr')
            ->subject('Hoşgeldiniz')
            ->with([
                //  'name' => 'ulaş körpe',

            ])
            ->view('email.register',[ 'key'=>$this->key]);

       // return $this->view('view.name');
    }
}
