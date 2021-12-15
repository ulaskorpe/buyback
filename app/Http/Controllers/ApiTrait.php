<?php
/**
 * Created by PhpStorm.
 * User: ulaskorpe
 * Date: 26.10.2021
 * Time: 14:26
 */

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\Tmp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ApiTrait
{
    private  function generateKey(){
        return md5('garantili_crm_2021');
    }


private $buyback_status_array = [0=>'Alındı',1=>'Kontrol Ediliyor',2=>'Hazırlanıyor',3=>'Tamamlandı',4=>'İptal Edildi'];
private $menu_locations = [ 1=>'Üst Menu',2=>'Başlık Menü',3=>'Sol Menü'];

    private function randomPassword($len = 16,$alphabet=0) {
        if($alphabet==1){
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        }else{
            $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890+-%/*#$%&()!@';
        }

        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $len; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    private function checkUnique($field,$table,$value,$id=0){
        if($id==0){
        $ch = DB::table($table)
            ->select('id')
            ->whereRaw($field.'= ?',[$value])->first();

        }else{
            $ch = DB::table($table)
                ->select('id')
                ->where('id','<>',$id)
                ->whereRaw($field.'= ?',[$value])->first();

        }
        return (!empty($ch->id)) ? true:false;
    }

    private function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }

    public function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
        $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
        return $this->isDigits($telephone, $minDigits, $maxDigits);
    }


    private function validate_phone_number($phone,$country_code=90)
    {


        $co = Country::where('phonecode','=',$country_code)->first();
        if(empty($co['id'])){
            return false;
        }else{
            // Allow +, - and . in phone number
            $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
            // Remove "-" from number
            $phone_to_check = str_replace("-", "", $filtered_phone_number);
            // Check the lenght of number
            // This can be customized if you want phone number from a specific country

            /*  if(in_array($co['phonecode'],[49,90])){
                  return $this->isValidTelephoneNumber($phone_to_check,10,11);
              }else{
                  return $this->isValidTelephoneNumber($phone_to_check);
              }*/
            return $this->isValidTelephoneNumber($phone_to_check);
        }


        /*    if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) {
                    return false;
             } else {

                return true;
             }

*/
        // return true;



        // return true;
        /*       if((int)$phone_to_check>1999999999 || (int)$phone_to_check<9000000000){
                   return true;
               }else{
                   return true;
               }*/


    }



    private function makeTmp($title=null,$data=null) {

        $tmp = new Tmp();
        $tmp->title = $title;
        $tmp->data = $data;
        $tmp->save();
    }

    private function validateEmail($email){
        return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

}
