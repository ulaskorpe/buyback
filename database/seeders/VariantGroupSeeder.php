<?php

namespace Database\Seeders;

use App\Models\MarketVariantGroup;
use App\Models\VariantValue;
use Illuminate\Database\Seeder;

class VariantGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $values = ['info','Kamera','Depolama','İşlemci','Ekran','Ağ/Bağlantı','Batarya','Gövde','Özellikler'];
       $count=1;
       foreach ($values as $value){
           $gr = new MarketVariantGroup();
           $gr->group_name= $value;
           $gr->order = $count;
           $gr->save();
            $count++;
       }
    }
}
