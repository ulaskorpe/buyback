<?php

namespace App\Observers;

use App\Mail\OrderEmail;
use App\Models\Order;
use App\Models\Tmp;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function saved(Order $order )
    {



        $tmp = new Tmp();
        $tmp->title  = $order['id'];
         $tmp->data  = $order['cargo_code'].": ".$order['status'].":".$order->customer()->first()->email;
        $tmp->save();

        if(empty($order['cargo_code'])){
            $txt = "Siparişiniz alınmıştır";
        }else{
            $txt = "Siparişiniz ".$order['cargo_code']."  kodu ile yola çıkmıştır";
        }



       Mail::to($order->customer()->first()->email)->send(new OrderEmail($txt));

    }
}
