<?php
/**
 * Created by PhpStorm.
 * User: ulas.korpe
 * Date: 15.04.2022
 * Time: 12:39
 */

namespace App\Http\Controllers;
use App\Models\BankPurchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CCPayment
{

private function makeAmount($tutar){
    $dz = explode(".",$tutar);
    return (empty($dz[1])) ?  $dz[0]."00": $dz[0].substr($dz[1],0,2);
}

    private function yapiKredi($tutar,$siparis_no,$taksit,$cardHolderName,$ccno,$expDate,$cvc ){

        $mid="6700623435"; //uyeisyeri no
        $tid="67881033";
        $posnetid="1010213151267590";
         $amount=  $this->makeAmount($tutar);//str_replace(".","",$tutar);
        $tranType="Sale"; //Sale - Auth
        $XID=$siparis_no;


        $xml_data ='<?xml version="1.0"  encoding="utf-8"?> 
<posnetRequest>
 <mid>'.$mid.'</mid>
 <tid>'.$tid.'</tid>
 <oosRequestData>
 <posnetid>'.$posnetid.'</posnetid>
 <XID>'.$XID.'</XID>
 <amount>'.$amount.'</amount>
 <currencyCode>TL</currencyCode>
 <installment>'.$taksit.'</installment>
 <tranType>'.$tranType.'</tranType>
 <cardHolderName>'.$cardHolderName.'</cardHolderName>
 <ccno>'.$ccno.'</ccno>
 <expDate>'.$expDate.'</expDate>
 <merchantData>'.$siparis_no.'</merchantData>
 <cvc>'.$cvc.'</cvc> 
 </oosRequestData>
 </posnetRequest>';

        $url = "https://posnet.yapikredi.com.tr/PosnetWebService/XML";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type=application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS,  "xmldata=" .$xml_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $xml   = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
        $array = json_decode(json_encode((array)$xml), TRUE);


        return $array;

//        $approved=$array[approved];
//        $posnetData=$array[oosRequestDataResponse][data1];
//        $posnetData2=$array[oosRequestDataResponse][data2];
//        $sign=$array[oosRequestDataResponse][sign];
    }



    private function  makeFloat( $num){
        $dz = explode(".",$num);

        if(!empty($dz[1])){
            return $dz[0].".".substr($dz[1],0,2);
        }else{
            return $dz[0];
        }

    }
    private function findBank($banka_id,$taksit,$tutar ){
        $purchase= BankPurchase::with('bank')->where('bank_id','=',$banka_id)->where('purchase','=',$taksit)->first();
        $result = array();
        if(empty($purchase['id']) || $taksit==1 ||$tutar < 100){
            $result['bank_id'] = 58;////Yapı kredi
        }else{
            if(empty($purchase['id'])){
                $result['bank_id'] = 58;////Yapı kredi
            }else{
            $result['bank_id'] = $purchase->bank()->first()->bank_id;
            }
        }
      //  $result['bank_id'] = 58;
        return $result;
/*
        if(empty($purchase['id']) || $taksit==1 ||$tutar < 100){
            $result['bank_name'] = 'Yapı Kredi';
            $result['kdv'] =$this->makeFloat($tutar*0.18)*1;
            $sayi=1;
            $result['taksit']=$tutar*1;
        }else{
            $result['bank_name'] = $purchase->bank()->first()->bank_name;

            $result['kdv'] =$this->makeFloat($tutar*0.18);
            $sayi=(int)$purchase['purchase'];

            $tutar =   ( $tutar/  (100-$purchase['commission']) )*100;

            $result['taksit']=  $tutar / $sayi;
            // $tutar = str_replace(",","",number_format($tutar,2))*1;
        }
        $result['taksit_sayisi']=$sayi;
        //$result['tutar'] =number_format($tutar,2);
        $result['tutar'] =($calculated>0)?$calculated:$tutar;*/


    }
}