<?php

namespace Database\Seeders;

use App\Models\ReturnProblem;
use Illuminate\Database\Seeder;

class ReturnProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $i=1;
         $data=['Eksik parça/özellik','Arızalı Ürün','Farklı renk','Farklı hafıza','Yanlış Ürün','Tarifinden Farklı'];
         foreach ($data as $item){
             $rp=new ReturnProblem();
             $rp->description=$item;
             $rp->order=$i;
             $rp->status=1;
             $rp->save();
             $i++;
         }
    }
}
