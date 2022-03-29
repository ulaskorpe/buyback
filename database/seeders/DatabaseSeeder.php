<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //$this->call(UserSeeder::class);
        //$this->call(SiteLocationSeeder::class);
        //$this->call(VariantGroupSeeder::class);
//    $this->call(BannerSeeder::class);
    //$this->call(BankAccountSeeder::class);
//    $this->call(FaqSeeder::class);
//    $this->call(NewsSeeder::class);
        $this->call(ReturnProblemSeeder::class);
    }
}
