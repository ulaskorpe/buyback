<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brands = ['apple','bosch','cannon','connect','galaxy','gopro','handspot','kinova','nespresso','samsung','speedway','yoko'];
    $i=1;
        foreach ($brands as $brand){
            $b = new Banner();
            $b->title=$brand;
            $b->image="images/banners/".$i.".png";
            $b->link ='';
            $b->status =1;
            $b->order = $i;
            $b->save();
            $i++;
        }

    }
}
