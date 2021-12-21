<?php

namespace Database\Seeders;

use App\Models\ProductLocation;
use App\Models\SiteLocation;
use Illuminate\Database\Seeder;

class SiteLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations =['Süper Teklif','Haftanın Fırsatları','Çok Satanlar','Yeni Ürünler','En Yüksek Puanlı'];
        foreach ($locations as $location){
            $l= new SiteLocation();
            $l->name=$location;
            $l->status=1;
            $l->save();
        }
    }
}
