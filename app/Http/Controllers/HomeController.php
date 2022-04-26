<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Helpers\GeneralHelper;
use App\Mail\OrderEmail;
use App\Models\Answer;
use App\Models\Bank;
use App\Models\BankPurchase;
use App\Models\BuyBack;
use App\Models\BuyBackAnswer;
use App\Models\BuyBackUser;
use App\Models\City;
use App\Models\Color;
use App\Models\ColorModel;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Log;
use App\Models\Memory;
use App\Models\ModelAnswer;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductMemory;
use App\Models\ProductModel;
use App\Models\ProductImage;
use App\Models\Tmp;
use App\Models\Town;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Mail\Mailer;

class HomeController extends Controller
{
    use ApiTrait;
    public function home(){
        //echo route('banka-taksitler',40);
//        Session::put('admin_id',$check['id']);
//        Session::put('name_surname',$check['name']." ".$check['surname']);
//        Session::put('sudo',$check['sudo']);


//        for($i=0;$i<100;$i++){
//            $this->makeTmp($this->randomPassword(16,1),rand(100,85555));
//        }

//return  Session::get('auth_array');
       // return view('react');

       return view('index',['brands'=>ProductBrand::all()]);
    //   return redirect(route('admin.index'));
    }
    public function kkForm(){
        //echo route('banka-taksitler',40);
//        Session::put('admin_id',$check['id']);
//        Session::put('name_surname',$check['name']." ".$check['surname']);
//        Session::put('sudo',$check['sudo']);


//        for($i=0;$i<100;$i++){
//            $this->makeTmp($this->randomPassword(16,1),rand(100,85555));
//        }

//return  Session::get('auth_array');
       // return view('react');

       return view('kkform' );
    //   return redirect(route('admin.index'));
    }
    public function postForm(){
        //echo route('banka-taksitler',40);
//        Session::put('admin_id',$check['id']);
//        Session::put('name_surname',$check['name']." ".$check['surname']);
//        Session::put('sudo',$check['sudo']);


//        for($i=0;$i<100;$i++){
//            $this->makeTmp($this->randomPassword(16,1),rand(100,85555));
//        }

//return  Session::get('auth_array');
       // return view('react');

       return view('post_form' );
    //   return redirect(route('admin.index'));
    }
    public function react(){


         return view('react');

    }


    public function createCoupons($count=10){


        for($i=0;$i<$count;$i++){
            $code  = 'GRNTL-'.rand(1000,9999).'-'.rand(1000,9999).'-'.rand(1000,9999);
            $c = Coupon::where('code','=',$code)->first();
            if(empty($c['id'])){
                $c=new Coupon();
                $c->code = $code;
                $c->amount = 0;
                $c->percentage=10;
                $c->is_active=1;
                $c->usage = 1000000;
                $c->expires_at=Carbon::now()->addYear(1);
           //     $c->save();
            }



        }

        return $count;

    }


    public function postConfirm(Request $request){


        $postRequest = array(
            'order_code' =>$request['order_code'],
            'result' => $request['result'],
            'msg' => $request['msg'],
        );
/*
        $postRequest = '<?xml version="1.0" encoding="UTF-8"?>
                            <CC5Request>
                            <Order_code>asdfasff55623132</Order_code>
                            <Result>1</Result>
                            <Msg>burası msg</Msg>

                            </CC5Request>';*/
        $data_string = json_encode($postRequest);
        $cURLConnection = curl_init("https://buyback.garantiliteknoloji.com/api/customers/cart/payment-confirm");
        curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
     //   curl_setopt($cURLConnection, CURLOPT_POST, count($postRequest));
   //     curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($cURLConnection, CURLOPT_POST, true);
     //   curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $data_string);

       // curl_setopt($cURLConnection, CURLOPT_HTTPHEADER,     array('Content-Type:application/json','x-api-key:5c35640a3da4f1e3970bacbbf7b20e6c'));
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER,     array( 'x-api-key:5c35640a3da4f1e3970bacbbf7b20e6c'));
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        return response()->json($apiResponse);
// $apiResponse - available data from the API request



    }

    public function ziraatPOst(){


        $strHostAddress = "https://sanalpos2.ziraatbank.com.tr/fim/api"; //Provizyon için xml'in post edilecegi adres

/*<
        $strXML = '<?xml version="1.0" encoding="UTF-8"?>
                            CC5Request>
                            <Name>NAme</Name>
                            <Password>'.$Password.'</Password>
                            <ClientId>'.$ClientId.'</ClientId>
                            <IPAddress>'.$IPAddress.'</IPAddress>
                            <OrderId>'.$OrderId.'</OrderId>
                            <Type>'.$Type.'</Type>
                            <Number>'.$Number.'</Number>
                            <Amount>'.$Amount.'</Amount>
                            <Taksit>'.$taksit.'</Taksit>
                            <Currency>'.$Currency.'</Currency>
                            <PayerTxnId>'.$PayerTxnId.'</PayerTxnId>
                            <PayerSecurityLevel>'.$PayerSecurityLevel.'</PayerSecurityLevel>
                            <PayerAuthenticationCode>'.$PayerAuthenticationCode.'</PayerAuthenticationCode>
                            </CC5Request>';

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $strHostAddress);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1) ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data=".$strXML);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $results = curl_exec($ch);
        curl_close($ch);

        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser,$results,$vals,$index);
        xml_parser_free($xml_parser);
*/


    }


    public function customerFix(){
        $cs = Customer::all();
        foreach ($cs as $c){
            $address = CustomerAddress::where('customer_id','=',$c['id'])->first();
            if(empty($address['id'])){
                echo $c['id']."<br>";
            }
        }
die();
        $addreses = CustomerAddress::all();
        foreach ($addreses as $a){
            $c = Customer::find($a['customer_id']);
            if(empty($c['id'])){
                echo $a['id']."<br>";
            }
        }

        //return view('react');

    }



    public function sendEmail($email='ulaskorpe@gmail.com',Mailer $mailer){

        $txt = date("Y-m-d His");

        $mailer->to($email)->send(new OrderEmail($txt));
    }
    private  function checkModelsTable(){
        $models = ProductModel::all();
        foreach ($models as $model){
            if($model['micro_id']>0){
                $ch = ProductModel::where('micro_id','=',$model['micro_id'])->where('id','<>',$model['id'])->first();
                if(!empty($ch['id'])){
                    echo $model['id']."--". $model['Modelname'].":".$model['micro_id']."<br>";
                    echo $ch['id']."--". $ch['Modelname'].":".$ch['micro_id']."<br>";

                        Product::where('model_id','=',$ch['id'])->update(['model_id'=>$model['id']]);
                     $ch->delete();
                    echo "<hr>";
                }
            }


            $ch = ProductModel::where('Modelname','=',$model['Modelname'])->where('id','<>',$model['id'])->first();
            if(!empty($ch['id'])){
                echo $model['id']."--". $model['Modelname'].":".$model['micro_id']."<br>";
                echo $ch['id']."--". $ch['Modelname'].":".$ch['micro_id']."<br>";

                    Product::where('model_id','=',$ch['id'])->update(['model_id'=>$model['id']]);
                 $ch->delete();
                echo "<hr>";
            }

        }

    }

    private function getRandomArray($n=5){////for colors
        $rands = array();
        for($i=0; $i<$n;$i++) {
            $ok = true;
            while($ok) {
                $x=rand(1,19);//mt_rand(0,$n-1);

                $ok = (in_array($x,$rands)) ? true : false;//!in_array($x, $rands) && $x != $i;
            }
            $rands[$i]=$x;
        }

        return $rands;
    }

    public function listProducts()
    {
        $products = Product::where('fake', '=', 0)->get();

        foreach ($products as $product) {
            if($product['price']==0){
            $price=rand(10,50)*100;
            $price_ex = $price+rand(1,10)*100;
            echo $product['id']."<br>";
            $product->price=$price;
            $product->price_ex=$price_ex;
            $product->save();
            }

            $image = ProductImage::where('product_id','=',$product['id'])->first();
            if(empty($image['id'])){
                $this->assignImage($product['id']);
            }
            $colors = ColorModel::where('model_id','=',$product['model_id'])->first();
            if(empty($colors['id'])){
                $this->assignColor($product['model_id']);
            }


            $model = ProductModel::where('id','=',$product['model_id'])->first();
            if(empty($model['id'])){
                echo $model['id']."<hr>";
            }

//            $model = ProductModel::where('id','=',$product['model_id'])->first();
//            if(empty($model['id'])){
//
//
//            echo "PRODUCTID". $product['model_id']."<br>";
//            echo "<hr>";
//            }

//                $mem = ProductMemory::where('product_id', '=', $product['id'])->first();
//                if (empty($mem['id'])) {
//                    $mem = new ProductMemory();
//                    $mem->product_id = $product['id'];
//                    $mem->memory_id = rand(5, 7);
//                    $mem->save();
//                    echo "PRODUCTID" . $product['id'] . "<br>";
//                    echo "<hr>";
//                }


            }


        //die();
    }

    public function listBrands(){
        return view('brand_list',['brands'=>ProductBrand::all()]);
    }

    public function clearProductMemories(){

        $productMemories = ProductMemory::all();
        foreach ($productMemories as $pm){
            $ch = ProductMemory::where('product_id','=',$pm['product_id'])
                ->where('memory_id','=',$pm['memory_id'])->where('id','<>',$pm['id'])->count();
            if($ch>0){
                ProductMemory::where('product_id','=',$pm['product_id'])
                    ->where('memory_id','=',$pm['memory_id'])->where('id','<>',$pm['id'])->delete();
            echo $pm['product_id'].":".$pm['memory_id']."<br>";
            }
        }


    }

    private function assignColor($model_id){
        foreach ($this->getRandomArray(rand(2, 7)) as $color_id) {
               $cm = new ColorModel();
               $cm->model_id=$model_id;
               $cm->color_id= $color_id;
                $cm->save();
              // echo $color_id."<br>";
        };
    }

    private function assignImage( $product_id){
         $img = ProductImage::where('product_id','=',$product_id)->first();
            if(empty($img['id'])){
                $r = rand(1,10);
                $img = new ProductImage();
                $img->product_id = $product_id;
                $img->thumb = 'images/products/THL'.$r.'.jpg';
                $img->image = 'images/products/L'.$r.'.jpg';
                $img->order=1;
                $img->first=1;
                $img->status=1;
                $img->save();
            }
    }


    private function connectApi($call){
        $payload = [
            'uyeno'		=> 2033,
            'storekey' 		=> '0b763e9cf94fc77819dc408b81c1be93',
            'call'    =>$call
        ];
        $ch = curl_init("https://garantili.com.tr/eticaret/api.php");

        //curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }

    public function getProducts(){


        $arr_1 = $this->connectApi('ilandakiler');
echo count($arr_1)." Adet <hr>";
die();
$uid="";
$added=0;
        foreach ($arr_1 as $item){

           $brand = ProductBrand::where('BrandName','=',$item['marka_id'])->first();
                   if(empty( $brand['id'])){
                       echo "MARKA" .$item['marka']."YOK";
                   }else { /// marka var

                       //       echo $item['marka'].":".$brand['BrandName'].":".$item['marka_id']."<br>";


                       $model = ProductModel::where('micro_id', '=', $item['model_id'])->first();
                       if (empty($model['id'])) {
                           $model = ProductModel::where('Modelname', '=', $item['model'])->first();
                           if (empty($model['id'])) {
                               echo "MODEL" . $item['model'] . "  BULUNAMADI<br>";
                               $model = new ProductModel();
                               $model->Modelname = $item['model'];
                               $model->Brandid = $brand['id'];
                               $model->Catid = 2;
                               $model->micro_id = $item['model_id'];
                               $model->save();
                               $this->assignColor($model['id']);
                                echo "EKLENDİ".$model['Modelname'].":".$model['id']."<br>";
//                           } else {
//                           //    echo "MODEL" . $item['model'] . "VAR ::::" . $model['micro_id'] . ":" . $item['model_id'] . "ZZZ";
//                               $model->micro_id = $item['model_id'];
//                               //   $model->micro_id = $item['model_id'];
//                            //   $model->save();

                           }


                       } elseif ($model['Modelname'] != $item['model']) {
                           echo $model['Modelname'] . "  !=    " . $item['model'].":".$model['id']."<br>";
                           $model->Modelname = $item['model'];
                        //   $model->save();
                       }else{

                           $product = Product::where('micro_id','=',$item['urun_id'])->first();
                           if(empty($product['id'])){
                               $uid.=$item['urun_id'].",";
                               echo $item['model']." YOK!<br>".$item['marka']." ".$item['model']."UID".$item['urun_id']." EKLENDİ <hr>";
                               $price=rand(10,50)*100;
                               $price_ex = $price+rand(1,10)*100;
                       $product= new Product();
                       $product->title = $item['marka']." ".$item['model'];
                       $product->micro_id =$item['urun_id'];
                       $product->brand_id = $brand['id'];
                       $product->model_id = $model['id'];
                       $product->category_id = 2;
                       $product->description ="";
                       $product->price = $price;
                       $product->price_ex =$price_ex;
                       $product->status =1;
                    $product->save();
                    $added++;

                    $this->assignImage($product['id']);
                     $mem_array = explode("/", str_replace("-","",trim($item['hafiza'])));
             //        var_dump($mem_array);
                       foreach ($mem_array as $mem) {
                           if (!empty($mem)) {
                               $m = Memory::where('memory_value', '=', (int)$mem)->first();
                               //       echo $mem.":::".$m['memory_value']."/".$m['id']."<br>";
                               $pm = ProductMemory::where('memory_id','=',$m['id'])->where('product_id','=',$product['id'])->first();
                                if(empty($pm['id'])){
                               $pm = new ProductMemory();
                               $pm->memory_id = $m['id'];
                               $pm->product_id = $product['id'];
                              $pm->save();
                                }
                           }

                       }
                               echo "<hr>";
                           }else{
                           //    echo $product['title']." VAR!";
                           }

                       }////model

                   }///brand


        }
        //return $arr_1;
        //fclose($fp);
        echo $uid;
    }

    public function getBrands(){


        $arr_1 = $this->connectApi('eticaret-markalar');




        foreach ($arr_1 as $item){
            $pb = ProductBrand::where('BrandName','=',$item['marka'])->first();
            if(!empty($pb['id'])){
                echo $item['marka']."<br>";
                echo $item['marka_id']."<br>";
//                $pb->micro_id = $item['marka_id'];
                //      $pb->save();

            }else{
                $brand = new ProductBrand();
                $brand->micro_id= $item['marka_id'];
                $brand->BrandName = $item['marka'];
                   $brand->save();
                echo $item['marka']." Eklendi<br>";
                echo $item['marka_id']." Eklendi<br>";
                echo "<hr>";
            }
       //   var_dump($item);
          echo "<hr>";
        }
        //return $arr_1;
        //fclose($fp);
    }

    public function getModels(){

         $arr_1 = $this->connectApi('eticaret-modeller');
        //echo count($arr_1);
        foreach ($arr_1 as $item){
            $model = ProductModel::where('Modelname','=',trim($item['model']))->first();
            if(!empty($model['id'])){

                if($model['micro_id']!=$item['model_id']){
//                    $model->micro_id = $item['model_id'];
//                    $model->save();
                 echo $model['Modelname'].":".$item['model'].":MID : ".$model['micro_id'].":".$item['model_id']."<br>";
                }
          //      echo $model['Modelname'].":".$item['model'].":MID : ".$model['micro_id'].":".$item['model_id']."<br>";
                //echo $model['micro_id']."<br>";
            }else{
                $brand = ProductBrand::where('micro_id','=',$item['marka_id'])->first();
                $pm = new ProductModel();
                $pm->Modelname = $item['model'];
                $pm->micro_id=$item['model_id'];
                $pm->Brandid = $brand['id'];
                $pm->Catid=2;
                $pm->status = 1;
                $pm->save();
                $this->assignColor($pm['id']);

                echo $item['model']." YOK!". ":".$brand['BrandName']."-".$brand['id'].":".$brand['micro_id'].":".$item['marka_id']."<br>";
            }

         //   var_dump($item)."<hr>";
        }
        die();


    }

    public function getMemories(){


    //    $url = curl_init("https://garantili.com.tr/eticaret/api.php?uyeno=2033&storekey=0b763e9cf94fc77819dc408b81c1be93&call=eticaret-modeller");

        $payload = [
            'uyeno'		=> 2033,
            'storekey' 		=> '0b763e9cf94fc77819dc408b81c1be93',
            'call'    => 'eticaret-hafizalar'
        ];

        $ch = curl_init("https://garantili.com.tr/eticaret/api.php");

        //curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $output = curl_exec($ch);


        curl_close($ch);

        $arr_1 = json_decode($output, TRUE);
        $c=0;
        foreach ($arr_1 as $item){

if($item['hafiza']!='Tanımsız'){
            $memory = new Memory();
            $memory->id =(int)$item['hafiza_id'];
            $memory->memory_value =(int)$item['hafiza'];
            $memory->status = 1;
       //     $memory->save();
}


                var_dump($item);
                echo "<hr>";
                $c++;

        }
        echo $c;
        //return $arr_1;
        //fclose($fp);
    }
    
    public function getColors(){


    //    $url = curl_init("https://garantili.com.tr/eticaret/api.php?uyeno=2033&storekey=0b763e9cf94fc77819dc408b81c1be93&call=eticaret-modeller");

        $payload = [
            'uyeno'		=> 2033,
            'storekey' 		=> '0b763e9cf94fc77819dc408b81c1be93',
            'call'    => 'eticaret-renkler'
        ];

        $ch = curl_init("https://garantili.com.tr/eticaret/api.php");

        //curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $output = curl_exec($ch);


        curl_close($ch);

        $arr_1 = json_decode($output, TRUE);
        $c=0;
        foreach ($arr_1 as $item){

            $color = new Color();
            $color->color_name = $item['renk'];
            $color->filter_name = GeneralHelper::fixName($item['renk']);
            $color->status =1;
            //$color->save();



                var_dump($item);
                echo "<hr>";
                $c++;

        }
        echo $c;
        //return $arr_1;
        //fclose($fp);
    }

    public function banksList(){

        $arr_1 = $this->connectApi( 'eticaret-bankalar') ;


     //   return $arr_1;

        Bank::query()->truncate();
        foreach ($arr_1 as $item){
            $b= new Bank();
                $b->bank_name=$item['banka'];
                $b->bank_id=$item['id'];
                $b->is_active = 0;
                $b->save();


            echo $item['banka']."<br>";
            echo $item['id']."<br>";
            echo "<hr>";
        }
        return null;
    }

    public function bankPurchases(){

        $arr_1 =$this->connectApi('eticaret-taksitlendirme');
      //return $arr_1;
        Bank::where('id','>',0)->update(['is_active'=>0]);
        BankPurchase::query()->truncate();
        foreach ($arr_1 as $item){

            $bank=Bank::where('bank_id','=',$item['banka'])->where('is_active','=',0)->first();
            if(!empty($bank['id'])){
                $bank->is_active=1;
                $bank->save();
            }

            $b = BankPurchase::where('bank_id','=',$item['banka'])->where('purchase','=',$item['taksit'])->first();
            if(empty($b['id'])){

            $b= new BankPurchase();
            }
            $b->bank_id=$item['banka'];
            $b->purchase_id = $item['id'];
            $b->purchase = $item['taksit'];
            $b->commission = $item['komisyon'];
            $b->payment_plan_id = $item['odeme_plani'];
            $b->description_id = $item['taksit_yazi'];
                 $b->save();


            echo $item['banka']."<br>";
            echo $item['id']."<br>";
            echo $item['komisyon']."<br>";
            echo $item['taksit_yazi']."<br>";
            echo "<hr>";
        }
        return null;
    }

    public function ccPayment(){


    //    $url = curl_init("https://garantili.com.tr/eticaret/api.php?uyeno=2033&storekey=0b763e9cf94fc77819dc408b81c1be93&call=eticaret-modeller");

        $payload = [
        'uyeno'		=> 2033,
            'storekey' 		=> '0b763e9cf94fc77819dc408b81c1be93',
            'call'    => 'eticaret-renkler'
        ];

        $ch = curl_init("https://garantili.com.tr/eticaret/api.php?uyeno=2033&storekey=0b763e9cf94fc77819dc408b81c1be93&call=taksitlendirme");

        //curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_POST, 1);
      //  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        $output = curl_exec($ch);


        curl_close($ch);

        return $output;
        //return $arr_1;
        //fclose($fp);
    }

    public function site(){


        return view('site.index');

    }

    public function login(){
        if(!empty(Session::get('admin_id'))){
            return redirect('/admin');
        }else{
            $email = (!empty(Cookie::get('email'))) ? Cookie::get('email'):'';
            $password = (!empty(Cookie::get('password'))) ? Cookie::get('password'):'';
            $remember = (!empty(Cookie::get('remember'))) ? Cookie::get('remember'):'';


            return view('login',['email'=>$email,'password'=>$password,'remember'=>$remember]);
        }
    }

    public function parsecities(){
//        $cities = City::all();
//        foreach ($cities as $city){
//
//            $city->plate_no = ( intval($city['plate_no']) <10)?"0".$city['plate_no']:$city['plate_no'];
//                $city->save();
//        }

        $country = Town::with('districts','city')->find(12);
        dd( $country);
    }


    public function logs(){

        return  view('react');
        for($i=0;$i<10;$i++){
            $log = new Log();
            $log->data = '{
   "title":"First Blog Post",
   "body" :"Lorem Ipsum, etc.",
   "slug" :"first-blog-post"
}';
            //$log->save();
        }

         return Log::all();
    }

    public function loginPost(Request $request){



        if ($request->isMethod('post')) {
            // return $request;

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $check = User::where('email','=',$request['email'])->where('password','=',md5($request['password']))->first();

                if($check['id']){
                    Session::put('admin_id',$check['id']);
                    Session::put('name_surname',$check['name']." ".$check['surname']);
                    Session::put('sudo',$check['sudo']);
                    if($check['group_id']>0){
                    $auth = UserGroup::find($check['group_id']);


                    Session::put('auth_array',['buyback'=>$auth['buybacks'],'users'=>$auth['users'],'system'=>$auth['system']
                        ,'site'=>$auth['site'],'market_place'=>$auth['market_place'],'products'=>$auth['products'],'customers'=>$auth['customers']]);

                  //  return  Session::get('auth_array');
                    }



                    if(!empty($request['remember_me']))
                    {
//                        Cookie::queue('admin_id',);
                        Cookie::queue("email",$request['email'],120);
                        Cookie::queue("password",$request['password'],120);
                        Cookie::queue("remember",true,120);
                    }

                    return [ 'Giriş Başarılı', 'success', route('admin.index'), '', ''];
                }else{
                    return ['Kullanıcı bulunamadı', 'error', route('admin.index'), '', ''];

                }



            });
            return json_encode($resultArray);

        }
    }

    public function checkImage($image)
    {
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = GeneralHelper::findExtension($image);
        //echo $ext;

        if (in_array($ext, $allowed)) {
            return "ok";
        } else {
            return 0;
        }
    }

    public function createBuyBackPost(Request $request)
    {

        //  return  $request['answers'];

        if ($request->isMethod('post')) {
            //    return $request['offered_price'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $user_id = (!empty(Session::get('admin_id'))) ? Session::get('admin_id') : 0;
//                if ($user_id > 0) {
//                    $bbUser = BuyBackUser::where('user_id', '=', $user_id)->first();
//                    if (empty($bbUser['id'])) {
//                        $bbUser = new BuyBackUser();
//                        $bbUser->user_id = $user_id;
//                    }
//                } else {
//
//                }
                $bbUser = new BuyBackUser();
                $bbUser->user_id = 0;

                $bbUser->name = $request['name'];
                $bbUser->surname = $request['surname'];
                $bbUser->email = $request['email'];
                $bbUser->phone = $request['phone'];
                $bbUser->tckn = $request['tckn'];
                $bbUser->iban = $request['iban'];
                $bbUser->city_id = $request['city_id'];
                $bbUser->town_id = $request['town_id'];
                $bbUser->district_id = $request['district_id'];
                $bbUser->neighborhood_id = $request['neighborhood_id'];
                $bbUser->address = (!empty($request['address'])) ? $request['address'] : '';
                $bbUser->terms_of_use = (!empty($request['terms_of_use'])) ? 1 : 0;
                $bbUser->campaigns = (!empty($request['campaigns'])) ? 1 : 0;
                $bbUser->save();

                $bb = new BuyBack();
                $bb->buyback_user_id = $bbUser['id'];
                $bb->imei_id =(!empty($request['imei_id']))? $request['imei_id']:0;
                $bb->imei = $request['imei'];
                $bb->model_id = $request['model_id'];
                $bb->color_id = $request['color_id_'];
                $bb->offer_price = $request['offered_price'];
                $bb->save();

                $answer_array = explode('@', substr($request['answers'], 0, strlen($request['answers']) - 1));
                $answers = Answer::whereIn('id', $answer_array)->get();
                foreach ($answers as $answer) {
                    $val = ModelAnswer::where('answer_id', '=', $answer['id'])->where('model_id', '=', $request['model_id'])->first();
                    $bbanswer = new BuyBackAnswer();
                    //  'buyback_id','question_id','answer_id','value'
                    $bbanswer->buyback_id = $bb['id'];
                    $bbanswer->question_id = $answer['question_id'];
                    $bbanswer->answer_id = $answer['id'];
                    $bbanswer->value = $val['value'];
                    $bbanswer->save();
                    //   echo $answer['answer'].":".$answer['question_id'].":".$val['value']."<br>";
                }
                // return  $answer_array;
                if(empty(Session::get('admin_id'))){
                    return ['Alım Talebi Eklendi', 'success', route('index'), '', ''];
                }else{

                    return ['Alım Talebi Eklendi', 'success', route('buyback.buyback-list'), '', ''];
                }

            });
            return json_encode($resultArray);

        }
    }

}
