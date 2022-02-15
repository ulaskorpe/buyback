<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;


class MarketPlaceController extends Controller
{
    use GGTrait;


     private $user = 'graciakids_dev';
     private $pw = 'Hb12345!';

    public function gittigidiyor(){
        //  var_dump(extension_loaded('soap'));
        //    var_dump( get_cfg_var('cfg_file_path') );
        //   dd($this->isDeveloper('grntltknlj','Garantili.2021'));
        //dd($this->getDeveloperId());
        //  dd($this->getDeveloperId());
    //    dd($this->getApplicationList('HFbaWVnEFBYteFg96JWp'));

       //  return $this->getModifiedCategories(0,10,'01/03/2018+00:00:00');
       // return $this->getCategory('f',1);

          return  $this->registerDeveloper('grntltknlj','p4xfcszN5x6GJr7SM7Q7yeb48tV3U6sd');
 return  $this->getApplicationList('grntltknlj');

      //  return view('admin.market_place.gittigidiyor');

    }


    public function hepsiBurada(){



        return view('admin.market_place.hepsiburada');
    }


    public function hepsiBuradaCats($count=500){


        $url='https://mpop-sit.hepsiburada.com/product/api/categories/get-all-categories?leaf=true&status=ACTIVE&available=true&page=0&size='.$count.'&version=1';

        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode($this->user.":".$this->pw)
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
    //    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);
        curl_close($ch);
//            $response = $response['data'];
        $response = json_decode($response,true) ;

        //$txt ="<table><tr></tr>";

foreach ($response['data'] as $item){
echo 'categoryId:'.$item['categoryId']."<br>";
echo 'name:'.$item['name']."<br>";
echo 'displayName:'.$item['displayName']."<br>";
echo 'parentCategoryId:'.$item['parentCategoryId']."<br>";
echo "<b>PATHS</b><br>";
$i=0;
foreach ($item['paths'] as $path){
    echo $i.":".$path."<br>";
}
echo 'leaf:'.$item['leaf']."<br>";
echo 'status:'.$item['status']."<br>";
echo 'type:'.$item['type']."<br>";
echo 'sortId:'.$item['sortId']."<br>";
echo 'imageURL:'.$item['imageURL']."<br>";
echo "<b>ProductType</b><br>";
foreach ($item['productTypes'] as $type){
    echo $type['name'] ."<br>";
    echo $type['productTypeId'] ."<br>";
}

echo "<hr>";
}


      //  dd(  $response['data']  );
      //  return  $response;
    }

    public function hbCatDetail($catId){

        $url='https://mpop-sit.hepsiburada.com/product/api/categories/'.$catId.'/attributes';

        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode($this->user.":".$this->pw)
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        //    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);
        curl_close($ch);
//            $response = $response['data'];
        $response = json_decode($response,true) ;
        if(empty($response['success'])){
            return  "Cat Bulunamadı";
        }

        if($response['success']==true){

            $txt ="<table style='width:100% '><tr><td><b>baseAttributes</b></td><td><b>attributes</b></td><td><b>variantAttributes</b></td></tr>";
            $txt.="<tr><td>";
            foreach ($response['data']['baseAttributes'] as $item){
                $txt.= "name:".$item['name']."<br>";
                $txt.= "id:".$item['id']."<br>";
                $txt.= "mandatory:".$item['mandatory']."<br>";
                $txt.= "type:".$item['type']."<br>";
                $txt.= "multiValue:". $item['multiValue'] ."<br>";
                $txt.= "<br><br>";
            }

            $txt.="</td>";
            $txt.="<td valign='top'>";
            foreach ($response['data']['attributes'] as $item){
                $txt.= "name:".$item['name']."<br>";
                $txt.= "id:".$item['id']."<br>";
                $txt.= "mandatory:".$item['mandatory']."<br>";
                $txt.= "type:".$item['type']."<br>";
                $txt.= "multiValue:". $item['multiValue'] ."<br>";
                $txt.= "<br><br>";
            }

            $txt.="</td>";
            $txt.="<td valign='top'>";
            foreach ($response['data']['variantAttributes'] as $item){
                $txt.= "name:".$item['name']."<br>";
                $txt.= "id:".$item['id']."<br>";
                $txt.= "mandatory:".$item['mandatory']."<br>";
                $txt.= "type:".$item['type']."<br>";
                $txt.= "multiValue:". $item['multiValue'] ."<br>";
                $txt.= "<br><br>";
            }

            $txt.="</td>";

        }else{

            $txt="CAT bulunamadı";

            }

        return $txt;
    }

    public function hbCatValues($catId){
        $url='https://mpop-sit.hepsiburada.com/product/api/categories/'.$catId.'/attributes';
        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic '. base64_encode($this->user.":".$this->pw)
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        //    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);
        curl_close($ch);
//            $response = $response['data'];
        $response = json_decode($response,true) ;


        if($response['success']==false){
            $txt="CAT bulunamadı";
        }else{

        $txt ="<table style='width:100% '><tr><td><b>baseAttributes</b></td><td><b>attributes</b></td><td><b>variantAttributes</b></td></tr>";
        $txt.="<tr><td>";
        foreach ($response['data']['baseAttributes'] as $item){
            $txt.= "name:".$item['name']."<br>";
            $txt.= "id:".$item['id']."<br>";
            $txt.= "mandatory:".$item['mandatory']."<br>";
            $txt.= "type:".$item['type']."<br>";
            $txt.= "multiValue:". $item['multiValue'] ."<br>";
            $txt.= "<br><br>";
        }

        $txt.="</td>";
        $txt.="<td valign='top'>";
        foreach ($response['data']['attributes'] as $item){
            $txt.= "name:".$item['name']."<br>";
            $txt.= "id:".$item['id']."<br>";
            $txt.= "mandatory:".$item['mandatory']."<br>";
            $txt.= "type:".$item['type']."<br>";
            $txt.= "multiValue:". $item['multiValue'] ."<br>";
            $txt.= "<br><br>";
        }

        $txt.="</td>";
        $txt.="<td valign='top'>";
        foreach ($response['data']['variantAttributes'] as $item){
            $txt.= "name:".$item['name']."<br>";
            $txt.= "id:".$item['id']."<br>";
            $txt.= "mandatory:".$item['mandatory']."<br>";
            $txt.= "type:".$item['type']."<br>";
            $txt.= "multiValue:". $item['multiValue'] ."<br>";
            $txt.= "<br><br>";
        }

        $txt.="</td>";

            }

        return $txt;
    }


}
