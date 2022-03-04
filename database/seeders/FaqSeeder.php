<?php

namespace Database\Seeders;

use App\Models\Faq;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
       for($i=0;$i<20;$i++){
           $faq = new Faq();
           $faq->title = $faker->sentence;
           $faq->content = $faker->paragraph;
           $faq->order= $i+1;
           $faq->status =1;
           $faq->save();
       }
    }
}
