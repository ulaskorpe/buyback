<?php

namespace Database\Seeders;

use App\Models\News;
use Faker\Factory;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for($i=0;$i<50;$i++){
            $img = rand(1,7);
            $n= new News();
            $n->title = $faker->sentence;
            $n->image = $img.".jpg";
            $n->thumb = $img."TH.jpg";
            $n->url = "/news/".($i+1);
            $n->description = substr($faker->paragraph,0,200);
            $n->content = $faker->paragraph(rand(6,10));
            $n->date = date('Y-m-d');
            $n->active = 1;

            $n->save();
        }
    }
}
