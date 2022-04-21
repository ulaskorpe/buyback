<?php
        $postRequest = array(
            'order_code' => 'asdfasff556+65+',
            'result' => 112,
            'msg' => 'naber nasılsın fasfs',
        );
        var_dump($postRequest);
        $data_string = json_encode($postRequest);
       $cURLConnection = curl_init("https://buyback.garantiliteknoloji.com/api/customers/cart/payment-confirm");
//var_dump($cURLConnection);
        curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_POST, count($postRequest));
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($cURLConnection, CURLOPT_POST, true);
     //   curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $data_string);

        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER,     array('Content-Type:application/json','x-api-key:5c35640a3da4f1e3970bacbbf7b20e6c'));
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);


//        var_dump($apiResponse);
        ///return response()->json($apiResponse);
?>