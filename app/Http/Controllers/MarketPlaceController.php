<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;


class MarketPlaceController extends Controller
{
use GGTrait;


    public function gittigidiyor(){
      //  var_dump(extension_loaded('soap'));
    //    var_dump( get_cfg_var('cfg_file_path') );
       //   dd($this->isDeveloper('grntltknlj','Garantili.2021'));
         //dd($this->getDeveloperId());
       //  dd($this->getDeveloperId());
         dd($this->getApplicationList('HFbaWVnEFBYteFg96JWp'));

        // return $this->getModifiedCategories(0,10,'01/03/2018+00:00:00');
         //return $this->getCategory('f',1);

    }


    public function bl(){

    }
}
