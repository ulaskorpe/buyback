<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

     //      'bank_name','name_surname','branch','account_number','iban','status'
        $banks = [
          ['Garanti','Şişli'],
          ['YapıKredi','Güneşli'],
          ['QNBC','Kağıthane'],
          ['İşBankası','Eskişehir']
        ];
        foreach ($banks as $bank){
            $ba = new BankAccount();
            $ba->bank_name = $bank[0];
            $ba->name_surname = $faker->name;
            $ba->branch = $bank[1];
            $ba->account_number = rand(100000,999999);
            $ba->iban = $faker->iban;
            $ba->status = 1;
            $ba->save();
        }
    }
}
