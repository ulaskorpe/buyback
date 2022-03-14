<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($txt)
    {
        $this->txt = $txt;
    }


    public function build()
    {
        return $this->from('bilgi@garantiliteknoloji.com')
            ->subject('Sipariş Bilgi ')
            ->with([
                //  'name' => 'ulaş körpe',

            ])
            ->view('email.order_info',[ 'txt'=>$this->txt]);
    }
}
