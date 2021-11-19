<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Ulaş Körpe';
        $user->email='ulas.korpe@garantili.com.tr';
        $user->email_verified_at =Carbon::now();
        $user->phone='5066063000';
        $user->password = md5('123123');
        $user->save();

    }
}
