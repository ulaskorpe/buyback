<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Mail\ForgetPassword;
use App\Mail\Register;

use App\Models\CartItem;
use App\Models\Color;
use App\Models\ColorModel;
use App\Models\Contact;
use App\Models\Customer;

use App\Models\CustomerAddress;
use App\Models\CustomerFavorite;
use App\Models\Guest;

use App\Models\GuestCartItem;
use App\Models\Neighborhood;
use App\Models\NewsLetter;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductMemory;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;


class ApiCustomerController extends Controller
{



    use ApiTrait;

    private function guestWelcome($guid,$c_id){
        $guest = Guest::where('guid','=',$guid)->first();
            if(!empty($guest['id'])){

        $items = GuestCartItem::where('guid','=',$guest['id'])->get();
        foreach ($items as $item){
            $cart_item = new CartItem();
            $cart_item->item_code = $item['item_code'];
            $cart_item->customer_id = $c_id;
            $cart_item->product_id = $item['product_id'];
            $cart_item->memory_id = $item['memory_id'];
            $cart_item->color_id = $item['color_id'];
            $cart_item->order_id = 0;
            $cart_item->status = 0;
            $cart_item->quantity = $item['quantity'];
            $cart_item->price = $item['price'];
            $cart_item->save();
        }

        GuestCartItem::where('guid','=',$guest['id'])->delete();
        Guest::where('guid','=',$guid)->delete();
            }
    }

    public function create(Request $request,Mailer $mailer)
    {
        if ($request->isMethod('post')) {
            $status_code =201;
            if ($request->header('x-api-key') == $this->generateKey()) {

                /*
                         $faker = Factory::create();
                         $customers = Customer::all();
                         foreach ($customers as $customer){
                             $address = Neighborhood::with('district.town.city')->where('district_id','>',0)->inRandomOrder()->first();
                             $ca = new CustomerAddress();
                             $ca->customer_id=$customer['id'];
                             $ca->title  = "EV adresi";
                             $ca->name_surname = $customer['name']." ".$customer['surname'];
                             $ca->address = $faker->address;
                             $ca->neighborhood_id = $address['id'];
                             $ca->district_id =$address->district()->first()->id;
                             $ca->town_id =$address->district()->first()->town()->first()->id;
                             $ca->city_id =$address->district()->first()->town()->first()->city()->first()->id;
                             $ca->phone_number = $faker->phoneNumber;
                             $ca->phone_number_2 = $faker->phoneNumber;
                             $ca->save();


                         }



                           // return $address->district()->first()->town()->first()->city()->first() ;


                            die();*/
                if (!empty($request['name']) && !empty($request['surname']) && !empty($request['email'])) {

                    $ch = Customer::select('id')->where('email', '=', $request['email'])->first();
                    if (filter_var($request['email'], FILTER_VALIDATE_EMAIL) && empty($ch['id'])) {
                        $last = Customer::select('customer_id')->orderBy('customer_id', 'DESC')->first();


                        $c = new Customer();
                        $c->customer_id = $last['customer_id'] + 1;
                        $c->name = $request['name'];
                        $c->surname = $request['surname'];
                        $c->gender = (!empty($request['gender']))?$request['gender']:'';
                        $c->email = $request['email'];
                        $c->password = md5($request['password']);
                        $c->status = 0;
                        $ch = true;

                        while ($ch) {
                            $key = rand(100000, 999999);

                            $ch = $this->checkUnique('activation_key', 'customers', $key);
                        }
                        $c->activation_key = $key;
                        $c->ip_address =$request->ip;

                        $file = $request->file('avatar');
                        if (!empty($file)) {

                            $filename = GeneralHelper::fixName($request['name'].$request['surname']) . "_"
                                . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                            $path =  "images/customers" ;
                            $th = GeneralHelper::fixName($request['name'].$request['surname']) . "TH_"
                                . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());


                            $img = Image::make($file->getRealPath());
                            $img->save($path . '/' . $filename);
                            $img->resize(150, 150, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($path . '/' . $th);
                            //       $image->image = "images/products/" . $filename;
                            $c->avatar = "images/customers/" . $th;
                        }


                        //  $c->save();


                        //////send email ////////////////
                        //if(){
                        $c->save();



                        if(!empty($request['guid'])){
                            $this->guestWelcome($request['guid'],$c['id']);
                        }//////guest


                        //return  response()->json( $c);
                        $returnArray['status'] = true;
                        // $returnArray['status_code'] = 201;

                        $returnArray['data'] =['customer'=>$c];
                        $mailer->to($request['email'])->send(new Register($key));
//                        }else{
//                            $returnArray['status'] = false;
//                            //$returnArray['status_code'] = 409;
//                            $status_code=409;
//                            $returnArray['errors'] =['msg'=>'conflict/invalid'] ;
//                        }
                        //////send email ////////////////



                    } else {
                        $returnArray['status'] = false;
                        //$returnArray['status_code'] = 409;
                        $status_code=409;
                        $returnArray['errors'] =['msg'=>'conflict/invalid'] ;


                    }

                } else {
                    $returnArray['status'] = false;
                    //$returnArray['status_code'] = 406;
                    $status_code=406;
                    //$returnArray['msg'] = 'missing data';
                    $returnArray['errors'] =['msg'=>'missing data'] ;
                }


            } else {
                $returnArray['status'] = false;
                //  $returnArray['status_code'] = 498;
                $status_code=498;
                //   $returnArray['msg'] = 'invalid key';
                $returnArray['errors'] =['msg'=>'invalid key'] ;
            }



        }else{
            $returnArray['status'] = false;
            //$returnArray['status_code'] = 405;
            $status_code=405;

            //      $returnArray['msg'] ='method_not_allowed';
            $returnArray['errors'] =['msg'=>'method_not_allowed'] ;
        }

        return response()->json($returnArray,$status_code);
    }

    public function updateProfile(Request $request)
    {



        if ($request->isMethod('post')) {

            if ($request->header('x-api-key') == $this->generateKey()) {
                $status_code=200;

                if (!empty($request['name']) && !empty($request['surname']) && !empty($request['customer_id'])  ) {

                    $c = Customer::where('customer_id','=',$request['customer_id'])->first();

                    $c->name = $request['name'];
                    $c->surname = $request['surname'];

                    if(!empty($request['gender']) && in_array($request['gender'],['male','female'])){


                        $c->gender =$request['gender'];
                    }

                    $c->ip_address = (!empty($request['ip_address'])) ? $request['ip_address'] : '';
                    $file = $request->file('avatar');
                    if (!empty($file)) {
                        $filename = GeneralHelper::fixName($request['name'].$request['surname']) . "_"
                            . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                        $path =  "images/customers" ;
                        $th = GeneralHelper::fixName($request['name'].$request['surname']) . "TH_"
                            . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                        $img = Image::make($file->getRealPath());
                        $img->save($path . '/' . $filename);
                        $img->resize(150, 150, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($path . '/' . $th);
                        //       $image->image = "images/products/" . $filename;
                        $c->avatar = "images/customers/" . $th;
                    }


                    $c->save();
                    //return  response()->json( $c);
                    $returnArray['status'] = true;
                    //    $returnArray['status_code'] = 200;
                    $returnArray['data'] =['customer'=>$c] ;


                } else {
                    $returnArray['status'] = false;
                    // $returnArray['status_code'] = 406;
                    $status_code=406;
                    $returnArray['errors'] = ['msg'=>'missing data'];
                }


            } else {
                $returnArray['status'] = false;
                //$returnArray['status_code'] = 498;
                $status_code=498;
                //$returnArray['msg'] = 'invalid key';
                $returnArray['errors'] = ['msg'=>'missing data'];
            }



        }else{
            $returnArray['status'] = false;
            //$returnArray['status_code'] = 405;
            $status_code=405;
            //$returnArray['msg'] ='method_not_allowed';
            $returnArray['errors'] = ['msg'=>'method_not_allowed'];
        }

        return response()->json($returnArray,$status_code);
    }

    public function updatePassword(Request $request)
    {



        if ($request->isMethod('post')) {

            if ($request->header('x-api-key') == $this->generateKey()) {

                $status_code=200;
                if ( !empty($request['customer_id'])  && !empty($request['password'])) {

                    $c = Customer::where('customer_id','=',$request['customer_id'])->first();

                    $c->password = md5($request['password']);
                    $c->ip_address = (!empty($request['ip_address'])) ? $request['ip_address'] : '';



                    $c->save();
                    //return  response()->json( $c);
                    $returnArray['status'] = true;
                    //     $returnArray['status_code'] = 200;
                    $returnArray['data'] = ['customer'=>$c];


                } else {
                    $returnArray['status'] = false;
//                    $returnArray['status_code'] = 406;
//                    $returnArray['msg'] = 'missing data';
                    $status_code=406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }


            } else {
                $returnArray['status'] = false;
                //$returnArray['status_code'] = 498;
                //  $returnArray['msg'] = 'invalid key';
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid_key'];

            }



        }else{
            $returnArray['status'] = false;
            // $returnArray['status_code'] = 405;
            $status_code=405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }

        return response()->json($returnArray,$status_code);
    }

    public function login(Request $request)
    {

        /*  $customers = Customer::all();
          foreach ($customers as $customer){

              $ch = true;
              while ($ch){
                  $key = rand(100000,999999);

                  $ch = $this->checkUnique('activation_key','customers',$key);
              }
              $customer->activation_key = $key;
              $customer->save();


          }

          die();**/
        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                $status_code = 200;
                if(!empty($request['email']) && !empty($request['password']) ){



                    $ch =  Customer::select('id','customer_id','name','surname','email','status')->where('email','=',$request['email'])
                        ->where('password','=',md5($request['password']))
                        //    ->where('status','=',1)
                        ->first();

                    if(empty($ch['customer_id'])){
                        $returnArray['status']=false;
                        $status_code=404;
                        $returnArray['errors'] =['msg'=>'kullanıcı bulunamadı'];
                        return response()->json($returnArray,$status_code);
                    }

                    if($ch['status']==1) {
                        $item_array=array();
                        $cart_items = CartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                            ->where('status','=',0)
                            ->where('customer_id','=',$ch['id'])
                            ->orderBy('created_at','DESC')
                            ->get();
                        $i=0;
                        foreach ($cart_items as $cart_item){
                            $item_array[$i] = $this->cartItemDetail($cart_item);
                            $i++;
                        }



                        $returnArray['status']=true;
                        $returnArray['data'] =['customer'=>$ch,'cart_items'=>$item_array];//['customer'=>$ch];


                        if(!empty($request['guid'])){


                            $this->guestWelcome($request['guid'],$ch['id']);
                        }//////guest


                    }elseif ($ch['status']==0){
                        $returnArray['status']=false;
                        $status_code=403;
                        $returnArray['data'] = $ch['email'];
                        $returnArray['errors'] =['msg'=>'','code'=>'not_verified'];
                    }elseif ($ch['status']==2){
                        $returnArray['status']=false;
                        $status_code=403;
                        $returnArray['errors'] =['msg'=>'banned user'];
                    }



                }else{
                    $returnArray['status']=false;

                    $status_code=406;
                    $returnArray['errors'] =['msg'=>'missing data'];

                }



            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];

            }

        }else{
            $returnArray['status'] = false;
            //$returnArray['status_code'] = 405;
            $status_code=405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function templateFx(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['email']) && !empty($request['password']) ){

                    $ch =  Customer::where('email','=',$request['email'])
                        ->where('password','=',md5($request['password']))
                        ->first();

                    if($ch['status']==1) {

                        $returnArray['status']=true;
                        $status_code=200;
                        $returnArray['data'] =$ch;


                    }elseif ($ch['status']==0){
                        $returnArray['status']=false;
                        $returnArray['status_code']=403;
                        $returnArray['msg'] ='inactive user';
                    }elseif ($ch['status']==2){
                        $returnArray['status']=false;
                        $returnArray['status_code']=403;
                        $returnArray['msg'] ='banned user';
                    }



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }



            }else{
                $returnArray['status']=false;
                $returnArray['status_code']=498;
                $returnArray['msg'] ='invalid key';
            }

        }else{
            $returnArray['status'] = false;
            $returnArray['status_code'] = 405;

            $returnArray['msg'] ='method_not_allowed';
        }
        return response()->json($returnArray);
    }

    public function activate(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['email']) && !empty($request['activation_key']) ){


                    $ch =  Customer::where('email','=',$request['email'])
                        ->where('activation_key','=',$request['activation_key'])
                        ->first();

                    if(!empty($ch['id'])){

                        if($ch['status']==0) {
                            $ch->status= 1;
                            $ch->activation_key=0;
                            if(!empty($request['ip_address'])){
                                $ch->ip_address=$request['ip_address'];
                            }

                            $ch->save();
                            $returnArray['status']=true;
                            $status_code =200;
                            $returnArray['data'] =['customer'=>$ch];

                        }else{
                            $returnArray['status']=false;
                            $status_code =304;
                            $returnArray['errors'] =['msg'=>"not modified"];

                        }

                    }else{
                        $returnArray['status']=false;
                        $status_code =404;
                        $returnArray['errors'] =['msg'=>"not found"];

                    }



                }else{
                    $returnArray['status']=false;
                    $status_code =406;
                    $returnArray['errors'] =['msg'=>"missing data"];

                }

            }else{
                $returnArray['status']=false;
                $status_code =498;
                $returnArray['errors'] =['msg'=>"invalid key"];

            }

        }else{
            $returnArray['status'] = false;
            $status_code =405;
            $returnArray['errors'] =['msg'=>"method_not_allowed"];
        }
        return response()->json($returnArray,$status_code);
    }

    public function newsletterPost(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['email']) && filter_var($request['email'], FILTER_VALIDATE_EMAIL) ){

                    $n = NewsLetter::where('email','=',$request['email'])->first();
                    if(empty($n['id'])){
                        $n = new NewsLetter();
                        $n->customer_id  = (!empty($request['customer_id']))?$request['customer_id']:0;
                        $n->guid  = (!empty($request['guid']))?$request['guid']:'';
                        $n->email = $request['email'];
                        $n->status = 1;
                    }else{
                        $n->customer_id  = (!empty($request['customer_id']))?$request['customer_id']:0;
                        $n->guid  = (!empty($request['guid']))?$request['guid']:'';

                    }
                    $n->save();

                    $returnArray['status']=true;
                    $status_code =201;
                    $returnArray['data'] =$n;


                }else{
                    $returnArray['status']=false;
                    $status_code =406;
                    $returnArray['errors'] =['msg'=>"missing data"];

                }

            }else{
                $returnArray['status']=false;
                $status_code =498;
                $returnArray['errors'] =['msg'=>"invalid key"];

            }

        }else{
            $returnArray['status'] = false;
            $status_code =405;
            $returnArray['errors'] =['msg'=>"method_not_allowed"];
        }
        return response()->json($returnArray,$status_code);
    }

    public function contactPost(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

               // if(!empty($request['email']) && filter_var($request['email'], FILTER_VALIDATE_EMAIL)  && !empty($request['message'])){
                if(  !empty($request['message'])){

                    if(!empty($request['customer_id'])){
                        $customer = Customer::where('customer_id','=',$request['customer_id'])->first();
                        $name = $customer['name'];
                        $surname = $customer['surname'];
                        $email = $customer['email'];
                    }else{
                        $name = '';
                        $surname ='';
                        $email ='';

                    }

                    $c = new Contact();
                    $c->customer_id  = (!empty($request['customer_id']))?$request['customer_id']:0;
                    $c->guid  = (!empty($request['guid']))?$request['guid']:'';
                    $c->name  = (!empty($request['name']))?$request['name']:$name;
                    $c->surname  = (!empty($request['surname']))?$request['surname']:$surname;
                    $c->email  = (!empty($request['surname']))?$request['surname']:$email;
                    $c->phone_number  = (!empty($request['phone_number']))?$request['phone_number']:'';
                    $c->subject  = (!empty($request['subject']))?$request['subject']:'';
                    $c->message  = (!empty($request['message']))?$request['message']:'';
                      $c->save();
                    if(!empty($request['email'])){
                    $n = NewsLetter::where('email','=',$request['email'])->first();

                    if(empty($n['id'])){
                        $n = new NewsLetter();

                        $n->email = $request['email'];
                        $n->status = 1;
                    }else{
                        $n->customer_id  = (!empty($request['customer_id']))?$request['customer_id']:0;
                        $n->guid  = (!empty($request['guid']))?$request['guid']:'';

                    }
                    $n->save();
                    }
                    $returnArray['status']=true;
                    $status_code =201;
                    $returnArray['data'] =$c;


                }else{
                    $returnArray['status']=false;
                    $status_code =406;
                    $returnArray['errors'] =['msg'=>"missing data"];

                }

            }else{
                $returnArray['status']=false;
                $status_code =498;
                $returnArray['errors'] =['msg'=>"invalid key"];

            }

        }else{
            $returnArray['status'] = false;
            $status_code =405;
            $returnArray['errors'] =['msg'=>"method_not_allowed"];
        }
        return response()->json($returnArray,$status_code);
    }

    public function resendActivate(Request $request,Mailer $mailer)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['email'])){


                    $ch =  Customer::where('email','=',$request['email'])
                        //     ->where('activation_key','=',$request['activation_key'])
                        ->first();

                    if(!empty($ch['id'])){

                        if($ch['status']==0) {


                            if(!empty($request['ip_address'])){
                                $ch->ip_address=$request['ip_address'];
                                $ch->save();
                            }

                            $mailer->to($request['email'])->send(new Register($ch['activation_key']));
                            $returnArray['status']=true;
                            $status_code =200;
                            $returnArray['data'] =$ch['email'];//['customer'=>$ch];

                        }else{
                            $returnArray['status']=false;
                            $status_code =304;
                            $returnArray['errors'] =['msg'=>"zaten aktif"];

                        }

                    }else{
                        $returnArray['status']=false;
                        $status_code =404;
                        $returnArray['errors'] =['msg'=>"not found"];

                    }



                }else{
                    $returnArray['status']=false;
                    $status_code =406;
                    $returnArray['errors'] =['msg'=>"missing data"];

                }

            }else{
                $returnArray['status']=false;
                $status_code =498;
                $returnArray['errors'] =['msg'=>"invalid key"];

            }

        }else{
            $returnArray['status'] = false;
            $status_code =405;
            $returnArray['errors'] =['msg'=>"method_not_allowed"];
        }
        return response()->json($returnArray,$status_code);
    }


    public function forgetPassword(Request $request,Mailer $mailer)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['email']) ){
                    $ch =  Customer::select('customer_id','email','status')
                        ->where('email','=',$request['email'])->first();

                    if(!empty($ch['customer_id'])){
                        if($ch['status']==1) {
                            ///////////SEND EMAIL///////////////
                            $pw = GeneralHelper::randomPassword(8,1);
                            $mailer->to($request['email'])->send(new ForgetPassword($pw));
                            ///////////SEND EMAIL///////////////
                            $ch->password= md5($pw);
                            //$ch->activation_key=0;
                            if(!empty($request['ip_address'])){
                                $ch->ip_address=$request['ip_address'];
                            }

                            $ch->save();
                            $returnArray['status']=true;
                            $returnArray['data']=$ch['email'];//['customer'=>$ch];
                            $status_code = 200;

///                        $returnArray['status']=true;


                        }elseif ($ch['status']==0){
                            $returnArray['status']=false;
                            $status_code=403;
                            $returnArray['data'] = $ch['email'];
                            $returnArray['errors'] =['msg'=>'','code'=>'not_verified'];
                        }elseif ($ch['status']==2){
                            $returnArray['status']=false;
                            $status_code=403;
                            $returnArray['errors'] =['msg'=>'banned user'];
                        }


                    }else{
                        $returnArray['status']=false;
                        $status_code=404;

                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code=406;

                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code=498;

                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

///////////////////////////////ADDRESS////////////////////////////////////////////////////////





    public function getAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) ){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    if(!empty($ch['id'])){
                        $address = CustomerAddress::where('customer_id','=',$ch['id'])->where('id','=',$request['address_id'])->first();
                        $ch->ip_address=$request->ip();
                        $ch->save();
                        $returnArray['status']=true;
                        //  $status_code=200;
                        $status_code =200;
                        $returnArray['data'] =['address'=>$this->makeAddress($address)];



                    }else{
                        $returnArray['status']=false;
                        $status_code =404;
                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code =406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code =498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code =405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function showAddresses(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) ){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    if(!empty($ch['id'])){

                        $page = (!empty($request['page']))?($request['page']-1):0;
                        $page_count = (!empty($request['page_count']))?($request['page_count']):5;
                        $addresses = CustomerAddress::where('customer_id','=',$ch['id'])->skip($page*$page_count)
                            ->limit($page_count)
                            ->orderBy('first','DESC')
                            ->orderBy('updated_at','DESC')
                            ->get();

                        $array  = array();
                        $i=0;
                        foreach ($addresses as $address){

                            $array[$i]=$this->makeAddress($address);
                            $i++;
                        }

                        $address_count = CustomerAddress::select('id')->where('customer_id','=',$ch['id'])->count();
                        $ch->ip_address=$request->ip();
                        $ch->save();


                        $returnArray['status']=true;
                        //  $status_code=200;
                        $status_code =200;
                        $returnArray['data'] =['addresses'=>$array,'item_count'=>$address_count];



                    }else{
                        $returnArray['status']=false;
                        $status_code =404;
                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code =406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code =498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code =405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function addAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) && !empty($request['title']) && !empty($request['city_id']) && !empty($request['address'])){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    if(!empty($ch['id'])){


                        $address = new CustomerAddress();
                        $address->customer_id = $ch['id'];
                        $address->title = $request['title'];

                        if(!empty($request['name_surname'])){
                            $address->name_surname = $request['name_surname'];
                        }else{
                            $address->name_surname = $ch['name']." ".$ch['surname'];
                        }
                        $address->address = $request['address'];
                        $address->city_id = $request['city_id'];
                        if(!empty($request['town_id'])){
                            $address->town_id=$request['town_id'];
                            if(!empty($request['district_id'])){
                                $address->district_id=$request['district_id'];
                                if(!empty($request['neighborhood_id'])){
                                    $address->neighborhood_id=$request['neighborhood_id'];
                                }else{
                                    $address->neighborhood_id=0;
                                }
                            }else{
                                $address->district_id=0;
                                $address->neighborhood_id=0;
                            }

                        }else{
                            $address->town_id=0;
                            $address->district_id=0;
                            $address->neighborhood_id=0;
                        }

                        $address->phone_number=(!empty($request['phone_number']))?$request['phone_number']:'';
                        $address->phone_number_2=(!empty($request['phone_number_2']))?$request['phone_number_2']:'';

                        $ach=CustomerAddress::where('customer_id','=',$ch['id'])->where('first','=',1)->first();
                        if(empty($ach['id'])){
                            $address->first=1;
                        }else{

                            if(($request['first']==1)){
                                $address->first=1;
                                CustomerAddress::where('customer_id','=',$ch['id'])->update(['first'=>0]);
                            }else{
                                $address->first=0;
                            }

                        }
                        $address->save();

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status']=true;
                        $status_code= 201;
                        $returnArray['data'] =['address'=>$address];



                    }else{
                        $returnArray['status']=false;
                        $status_code= 404;

                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code= 406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code= 498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code= 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function updateAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) && !empty($request['title']) && !empty($request['city_id'])  && !empty($request['address_id'])){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    $address =  CustomerAddress::where('customer_id','=',$ch['id'])
                        ->where('id','=',$request['address_id'])->first();

                    if(!empty($ch['id']) && !empty($address['id'])){


                        $address->title = $request['title'];

                        if(!empty($request['name_surname'])){
                            $address->name_surname = $request['name_surname'];
                        }else{
                            $address->name_surname = $ch['name']." ".$ch['surname'];
                        }
                        $address->address = $request['address'];
                        $address->city_id = $request['city_id'];
                        if(!empty($request['town_id'])){
                            $address->town_id=$request['town_id'];
                            if(!empty($request['district_id'])){
                                $address->district_id=$request['district_id'];
                                if(!empty($request['neighborhood_id'])){
                                    $address->neighborhood_id=$request['neighborhood_id'];
                                }else{
                                    $address->neighborhood_id=0;
                                }
                            }else{
                                $address->district_id=0;
                                $address->neighborhood_id=0;
                            }

                        }else{
                            $address->town_id=0;
                            $address->district_id=0;
                            $address->neighborhood_id=0;
                        }

                        $address->phone_number=(!empty($request['phone_number']))?$request['phone_number']:'';
                        $address->phone_number_2=(!empty($request['phone_number_2']))?$request['phone_number_2']:'';

                        $ach=CustomerAddress::where('customer_id','=',$ch['id'])->where('first','=',1)->first();
                        if(empty($ach['id'])){
                            $address->first=1;
                        }else{

                            if(($request['first']==1)){
                                $address->first=1;
                                CustomerAddress::where('customer_id','=',$ch['id'])->update(['first'=>0]);
                            }else{
                                $address->first=0;
                            }

                        }
                        $address->save();

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status']=true;
                        $status_code = 200;
                        $returnArray['data'] =['address'=>$address];



                    }else{
                        $returnArray['status']=false;
                        $status_code = 404;

                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code = 406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code = 498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function deleteAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) && !empty($request['address_id'])){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    $address =  CustomerAddress::where('customer_id','=',$ch['id'])
                        ->where('id','=',$request['address_id'])->first();

                    if(!empty($ch['id']) && !empty($address['id'])){


                        if($address['first']==1){
                            $a = CustomerAddress::where('customer_id','=',$ch['id'])
                                ->where('id','<>',$request['address_id'])->first();

                            if(!empty($a['id'])){
                                $a->first = 1;
                                $a->save();
                            }
                        }
                        $address->delete();

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status']=true;
                        $status_code=200;
                        //$returnArray['msg'] ='address deleted';



                    }else{
                        $returnArray['status']=false;
                        $status_code=404;

                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code=406;

                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code=498;

                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }

        return response()->json($returnArray,$status_code);
    }

///////////////////////////////ADDRESS////////////////////////////////////////////////////////

/// /////////////////////////// FAVORITES ////////////////////////////////////////////////

    public function addToFavorites(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) && !empty($request['product_id'])){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();
                    $product=  Product::find($request['product_id']);

                    if(!empty($ch['id']) && !empty($product['id'])){

                        $fav = CustomerFavorite::where('customer_id','=',$ch['id'])
                            ->where('product_id','=',$request['product_id'])->first();
                        if(empty($fav['id'])){
                            $fav = new CustomerFavorite();
                            $fav->customer_id = $ch['id'];
                            $fav->product_id = $request['product_id'];
                        }


                        $fav->memory_id  = (!empty($request['memory_id']))?$request['memory_id']:0;
                        $fav->color_id  = (!empty($request['color_id']))?$request['color_id']:0;
                        $fav->save();

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status']=true;
                        $status_code=201;




                    }else{
                        $returnArray['status']=false;
                        $status_code=404;

                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code=406;

                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code=498;

                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function removeFromFavorites(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) && !empty($request['product_id'])){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();
                    $product=  Product::find($request['product_id']);

                    if(!empty($ch['id']) && !empty($product['id'])){

                        $fav = CustomerFavorite::where('customer_id','=',$ch['id'])
                            ->where('product_id','=',$request['product_id'])->first();
                        if(!empty($fav['id'])){
                            $fav->delete();

                            $returnArray['status']=true;
                            $status_code=200;

                        }else{

                            $returnArray['status']=false;
                            $status_code=404;

                            $returnArray['errors'] =['msg'=>'not found'];
                        }

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }




                    }else{
                        $returnArray['status']=false;
                        $status_code=404;

                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code=406;

                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code=498;

                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function showFavorites(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) ){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    if(!empty($ch['id'])  ){


                        $returnArray['status']=true;
                        $status_code=200;
                        $returnArray['data'] =CustomerFavorite::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                            ->where('customer_id','=',$ch['id'])
                            ->orderBy('created_at','DESC')
                            ->get();

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }




                    }else{
                        $returnArray['status']=false;
                        $status_code=404;

                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $returnArray['status']=false;
                    $status_code=406;

                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code=498;

                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }




/// /////////////////////////// FAVORITES ////////////////////////////////////////////////
///addToCart


    private function generateItemCode(){
        $ok =true;
        while($ok){
            $code ="ITEM".date("Ymd")."-".rand(10000,100000);
            $ch= CartItem::where('item_code','=',$code)->first();
            $ok = (empty($ch['id']))?false:true;
        }

        return $code;
    }

    private function generateOrderCode(){
        return "GRNTL".date("YmdHis").rand();
    }

    public function addToCart(Request $request)
    {



        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                $product=  Product::find($request['product_id']);
                if(!empty($product['id'])){
                    $memory_id=(!empty($request['memory_id']))?$request['memory_id']:0;
                    $color_id=(!empty($request['color_id']))?$request['color_id']:0;

                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();


                    if(!empty($ch['id'])){ ///// customer process

                        $cart = CartItem::where('customer_id','=',$ch['id'])
                            ->where('product_id','=',$request['product_id'])
                            ->where('status','=',0)
                            ->where('memory_id','=',$memory_id)
                            ->where('color_id','=',$color_id)
                            ->where('status','=',0)
                            ->first();
                        if(empty($cart['id'])){
                            $item_code=$this->generateItemCode();
                            $cart = new CartItem();
                            $cart->item_code = $item_code;
                            $cart->customer_id = $ch['id'];
                            $cart->product_id = $request['product_id'];
                            $cart->memory_id  = $memory_id ;
                            $cart->color_id  = $color_id;
                        }else{
                            $item_code = $cart['item_code'];
                        }
                        $qty=(!empty($request['quantity']))?$request['quantity']:1;

                        $cart->quantity  = $qty;
                        if($request['price']>0){
                            $cart->price = ((float)$request['price'])*$qty;
                        }else{
                            $price = ($product['price_ex']>$product['price'])?$product['price']:$product['price_ex'];
                            $cart->price = ((float)$price)*$qty;
                        }
                        $cart->save();

                        //$ch->activation_key=0;
                        $ch->ip_address=$request->ip();
                        $ch->save();


                        ///////buy TOGETHER//////////////////////////////////////////
                        if(!empty($request['buy_together'])){

                            $together_array=explode(",",trim($request['buy_together']));

                            foreach ($together_array as $together){
                                $product_2  = Product::select('model_id')->where('id','=',$together)->first();

                                $memory_id_2 = ProductMemory::where('product_id','=',$together)->inRandomOrder()->limit(1)->pluck('memory_id')->first();
                                $color_id_2 = ColorModel::where('model_id','=',$product_2['model_id'])->inRandomOrder()->limit(1)->pluck('color_id')->first();
                            $cart = CartItem::where('customer_id','=',$ch['id'])
                                ->where('product_id','=',$together)
                                ->where('status','=',0)
                                ->where('memory_id','=',$memory_id_2)
                                ->where('color_id','=',$color_id_2)
                                ->where('status','=',0)
                                ->first();
                            if(empty($cart['id'])){
                                $item_code_2=$this->generateItemCode();
                                $cart = new CartItem();
                                $cart->item_code = $item_code_2;
                                $cart->customer_id = $ch['id'];
                                $cart->product_id = $together;
                                $cart->memory_id  = $memory_id_2 ;
                                $cart->color_id  = $color_id_2;
                            }else{
                                $item_code = $cart['item_code'];
                            }


                            $cart->quantity  = 1;

                                $p = Product::find($together);
                                $price = ($p['price_ex']>$p['price'])?$p['price']:$p['price_ex'];
                                $cart->price = ((float)$price)*1;

                             $cart->save();
                            }/////////////
                            //$ch->activation_key=0;


                        }
                        ///////buy TOGETHER//////////////////////////////////////////
                        $ch->ip_address=$request->ip();
                        $ch->save();



                        $item_array=array();
                        $cart_items = CartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                            ->where('status','=',0)
                            ->where('customer_id','=',$ch['id'])
                            ->orderBy('created_at','DESC')
                            ->get();
                        $i=0;
                        foreach ($cart_items as $cart_item){
                            $item_array[$i] = $this->cartItemDetail($cart_item);
                            $i++;
                        }



                        $returnArray['status']=true;
                        $status_code=201;
                        $returnArray['data']=['item_code'=>$item_code,'cart_items'=>$item_array];




                    }else{///// guest processs

                        if(!empty($request['guid'])){
                            // return $request->ip();
                            $guest = Guest::where('guid','=',$request['guid'])->first();
                            if(empty($guest['id'])){
                                $guest = new Guest();
                                $guest->ip = $request->ip();
                                $guest->guid= $request['guid'];
                                $guest->expires_at = Carbon::now()->addYear(1)->format('Y-m-d');
                                $guest->save();
                            }


                            $cart = GuestCartItem::where('guid','=',$guest['id'])
                                ->where('product_id','=',$request['product_id'])

                                ->where('memory_id','=',$memory_id)
                                ->where('color_id','=',$color_id)

                                ->first();
                            if(empty($cart['id'])){
                                $item_code=$this->generateItemCode();
                                $cart = new GuestCartItem();
                                $cart->item_code = $item_code;
                                $cart->guid = $guest['id'];
                                $cart->product_id = $request['product_id'];
                                $cart->memory_id  = $memory_id ;
                                $cart->color_id  = $color_id;
                            }else{
                                $item_code = $cart['item_code'];
                            }

                            $qty=(!empty($request['quantity']))?$request['quantity']:1;

                            $cart->quantity  = $qty;
                            if($request['price']>0){
                                $cart->price = ((float)$request['price'])*$qty;
                            }else{
                                $price = ($product['price_ex']>$product['price'])?$product['price']:$product['price_ex'];
                                $cart->price = ((float)$price)*$qty;
                            }
                            $cart->save();




                            $item_array=array();
                            $cart_items = GuestCartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                                ->where('guid','=',$guest['id'])
                                ->orderBy('created_at','DESC')
                                ->get();
                            $i=0;
                            foreach ($cart_items as $cart_item){
                                $item_array[$i]=$this->cartItemDetail($cart_item);
                                $i++;
                            }

                            $returnArray['status']=true;
                            $status_code=201;
                            $returnArray['data']=['item_code'=>$item_code,'cart_items'=>$item_array];



                        }else{  /////empty!!!!
                            $returnArray['status']=false;
                            $status_code=406;
                            $returnArray['errors'] =['msg'=>'missing data'];
                        }


                    }



                }else{

                    $returnArray['status']=false;
                    $status_code=404;
                    $returnArray['errors'] =['msg'=>'not found'];



                }

            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];

            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function removeFromCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['item_code']) && (!empty($request['customer_id']) || !empty($request['guid']))){

                    if(!empty($request['customer_id'])){


                        $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();
                        $item=  CartItem::where('item_code','=',$request['item_code'])->where('customer_id','=',$ch['id'])->first(); //Product::find($request['product_id']);

                        if(!empty($ch['id']) && !empty($item['id'])){
                            $item->delete();
                            $returnArray['status']=true;
                            $status_code=200;


                            $ch->ip_address=$request->ip();
                            $ch->save();

                            $item_array=array();
                            $cart_items = CartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                                ->where('status','=',0)
                                ->where('customer_id','=',$ch['id'])
                                ->orderBy('created_at','DESC')
                                ->get();
                            $i=0;
                            foreach ($cart_items as $cart_item){
                                $item_array[$i] = $this->cartItemDetail($cart_item);
                                $i++;
                            }

                            $returnArray['data']=['cart_items'=>$item_array];

                        }else{
                            $returnArray['status']=false;
                            $status_code=404;
                            $returnArray['errors'] =['msg'=>'not found'];
                        }

                    }else{////guest

                        $guest =  Guest::where('guid','=',$request['guid'])->first();
                        $item=  GuestCartItem::where('item_code','=',$request['item_code'])->where('guid','=',(!empty($guest['id'])?$guest['id']:0))->first();

                        if(!empty($guest['id']) && !empty($item['id'])){
                            $item->delete();


                            $item_array=array();
                            $cart_items = GuestCartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                                ->where('guid','=',$guest['id'])
                                ->orderBy('created_at','DESC')
                                ->get();
                            $i=0;
                            foreach ($cart_items as $cart_item){
                                $item_array[$i]=$this->cartItemDetail($cart_item);
                                $i++;
                            }
                            ///////////////////////////////////////////////////////////////////////

                            $returnArray['status']=true;
                            $status_code=200;
                            $returnArray['data'] =['cart_items'=>$item_array];



                        }else{
                            $returnArray['status']=false;
                            $status_code=404;
                            $returnArray['errors'] =['msg'=>'not found'];
                        }


                    }



                }else{
                    $returnArray['status']=false;
                    $status_code=406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    public function updateCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['item_code']) && (!empty($request['customer_id']) || !empty($request['guid']))){
                    $qty = (!empty($request['quantity']))?$request['quantity']:1;



                    if(!empty($request['customer_id'])){


                        $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();


                        $item=  CartItem::where('item_code','=',$request['item_code'])->where('customer_id','=',$ch['id'])->first(); //Product::find($request['product_id']);

                        if(!empty($ch['id']) && !empty($item['id'])){

                            $product = Product::find($item['product_id']);

                            if($request['price']>0){
                                $item->price = ((float)$request['price'])*$qty;
                            }else{
                                $price = ($product['price_ex']>$product['price'])?$product['price']:$product['price_ex'];

                                $item->price = ((float)$price)*$qty;
                            }

                            $item->quantity = $qty;

                            $item->save();

                            $returnArray['status']=true;
                            $status_code=200;


                            $ch->ip_address=$request->ip();
                            $ch->save();

                            $item_array=array();
                            $cart_items = CartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                                ->where('status','=',0)
                                ->where('customer_id','=',$ch['id'])
                                ->orderBy('created_at','DESC')
                                ->get();
                            $i=0;
                            foreach ($cart_items as $cart_item){
                                $item_array[$i] = $this->cartItemDetail($cart_item);
                                $i++;
                            }

                            $returnArray['data']=['cart_items'=>$item_array];

                        }else{
                            $returnArray['status']=false;
                            $status_code=404;
                            $returnArray['errors'] =['msg'=>'not found'];
                        }

                    }else{////guest

                        $guest =  Guest::where('guid','=',$request['guid'])->first();
                        $item=  GuestCartItem::where('item_code','=',$request['item_code'])->where('guid','=',(!empty($guest['id'])?$guest['id']:0))->first();

                        if(!empty($guest['id']) && !empty($item['id'])){




                            if($request['price']>0){
                                $item->price = ((float)$request['price'])*$qty;
                            }else{
                                $product = Product::find($item['product_id']);
                                $price = ($product['price_ex']>$product['price'])?$product['price']:$product['price_ex'];
                                $item->price = ((float)$price)*$qty;
                            }

                            $item->quantity = $qty;

                            $item->save();


                            $item_array=array();
                            $cart_items = GuestCartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                                ->where('guid','=',$guest['id'])
                                ->orderBy('created_at','DESC')
                                ->get();
                            $i=0;
                            foreach ($cart_items as $cart_item){
                                $item_array[$i]=$this->cartItemDetail($cart_item);
                                $i++;
                            }
                            ///////////////////////////////////////////////////////////////////////

                            $returnArray['status']=true;
                            $status_code=200;
                            $returnArray['data'] =['cart_items'=>$item_array];



                        }else{
                            $returnArray['status']=false;
                            $status_code=404;
                            $returnArray['errors'] =['msg'=>'not found'];
                        }


                    }



                }else{
                    $returnArray['status']=false;
                    $status_code=406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }

            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }


    private function cartItemDetail($cart_item){
        $item_array=array();
        $item_array['item_code']=$cart_item['item_code'];
        $item_array['price']=$cart_item['price'];
        $item_array['quantity']=$cart_item['quantity'];
        $item_array['created_at']=$cart_item['created_at'];
        $item_array['product']=$cart_item->product()->first()->title;
        $item_array['description']=$cart_item->product()->first()->description;
        $item_array['brand']=$cart_item->product()->first()->brand()->first()->BrandName;
        $item_array['model']=$cart_item->product()->first()->model()->first()->Modelname;
        $item_array['category']=$cart_item->product()->first()->category()->first()->category_name;
        $item_array['imageUrl']=url($cart_item->product()->first()->firstImage()->first()->image);
        $item_array['thumb']=url($cart_item->product()->first()->firstImage()->first()->thumb);
        $item_array['color']=$cart_item->color()->first()->color_name;
        $item_array['memory']=$cart_item->memory()->first()->memory_value." GB";
        return $item_array;
    }


    public function showCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) ){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    if(!empty($ch['id'])  ){


                        $returnArray['status']=true;
                        $status_code=200;
                        $item_array=array();


                        $cart_items = CartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                            ->where('status','=',0)
                            ->where('customer_id','=',$ch['id'])
                            ->orderBy('created_at','DESC')
                            ->get();
                        $i=0;
                        foreach ($cart_items as $cart_item){
                            $item_array[$i] = $this->cartItemDetail($cart_item);
                            $i++;
                        }


                        $returnArray['data'] =['cart_items'=>$item_array];
                        //$ch->activation_key=0;

                        $ch->ip_address=$request->ip();
                        $ch->save();





                    }else{
                        $returnArray['status']=false;
                        $status_code=404;
                        $returnArray['errors'] =['msg'=>'not found'];
                    }



                }else{
                    $guest = Guest::where('guid','=',$request['guid'])->first();

                    if(!empty($guest['id'])){
                        ///////////////////////////////////////////////////////////////////////


                        $item_array=array();
                        $cart_items = GuestCartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                            ->where('guid','=',$guest['id'])
                            ->orderBy('created_at','DESC')
                            ->get();
                        $i=0;
                        foreach ($cart_items as $cart_item){
                            $item_array[$i]=$this->cartItemDetail($cart_item);
                            $i++;
                        }
                        ///////////////////////////////////////////////////////////////////////

                        $returnArray['status']=true;
                        $status_code=200;
                        $returnArray['data'] =['cart_items'=>$item_array];
                    }else{
                        $returnArray['status']=false;
                        $status_code=404;
                        $returnArray['errors'] =['msg'=>'not found'];
                    }


                }

            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);
    }

    private function calculateShipping($order_id){
        return rand(5,10)*10;
    }

    private function addressCheck($customer_id,$address_id=0){
        if($address_id>0){
            $address= CustomerAddress::find($address_id);
            if($customer_id != $address['customer_id']){
                $address = CustomerAddress::where('customer_id','=',$customer_id)->orderBy('first','DESC')->first();
                $address_id = (!empty($address['id'])) ? $address['id'] :0;
            }
        }else{
            $address = CustomerAddress::where('customer_id','=',$customer_id)->orderBy('first','DESC')->first();
            $address_id = (!empty($address['id'])) ? $address['id'] :0;
        }
        return $address_id;
    }

    public function placeOrder(Request $request)
    {

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if(!empty($request['customer_id']) ){
                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->where('status','=',1)->first();


                    if(!empty($ch['id']) && !empty($request['cargo_company_id'])  ){

                        $order_status=0;

                        $address_id = $this->addressCheck($ch['id'],(!empty($request['customer_address_id'])?$request['customer_address_id']:0));
                        if($address_id == 0){
                            $returnArray['status']=false;
                            $status_code=406;
                            $returnArray['errors'] =['msg'=>'address not found'];
                            return response()->json($returnArray,$status_code);
                        }

                        $billing_address_id = $this->addressCheck($ch['id'],(!empty($request['billing_address_id'])?$request['billing_address_id']:0));



                        if(!empty($request['item_array'])){
                            $item_array= explode(',',$request['item_array']);
                            $items = CartItem::with('product.firstImage','memory','color')
                                ->where('status','=',0)
                                ->where('customer_id','=',$ch['id'])->whereIn('item_code',$item_array)->get();
                        }else{
                            $items = CartItem::with('product.firstImage','memory','color')
                                ->where('status','=',0)
                                ->where('customer_id','=',$ch['id'])->get();
                        }

                        if($items->count()==0){
                            $returnArray['status']=false;
                            $status_code=404;
                            $returnArray['errors'] =['msg'=>'no item found'];
                            return response()->json($returnArray,$status_code);
                        }
                        $price = 0 ;
                        $delete_item_array = array();
                        foreach ($items as $item){
                            ///     echo $item['price']."x".$item['quantity']."\n";
                            $price += $item['price']*$item['quantity'];
                            $delete_item_array[]=$item['id'];
                        }


                        //    return $price ;
                        $shipping_price=$this->calculateShipping(3);


                        if($request['payment_method']=='cc' || $request['payment_method']==0){
                            if(!empty($request['cc_no']) && !empty($request['expires_at']) && !empty($request['cvc'])){
                                if(!$this->ccPayment($request['cc_no'],$request['expires_at'],$request['cvc'],($price+$shipping_price))){
                                    $returnArray['status']=false;
                                    $status_code=402;
                                    $returnArray['errors'] =['msg'=>'payment_required'];
                                    return response()->json($returnArray,$status_code);
                                }
                                $order_status=1;
                            }else{
                                $returnArray['status']=false;
                                $status_code=406;
                                $returnArray['errors'] =['msg'=>'missing data'];
                                return response()->json($returnArray,$status_code);

                            }
                        }/////CC PAYMENT



                        $order=new Order();
                        $order->order_code = $this->generateOrderCode();
                        $order->name_surname = (!empty($request['name_surname'])) ? $request['name_surname']:$ch['name']." ".$ch['surname'];
                        $order->cargo_company_id = $request['cargo_company_id'];
                        $order->customer_id= $ch['id'];
                        $order->customer_address_id = $address_id;
                        $order->order_method = (!empty($request['payment_method'])) ? $request['payment_method']:0;
                        $order->status = $order_status;
                        $order->amount = $price;
                        $order->shipping_price = $shipping_price;
                        $order->save();

                        $result = array();
                        $i=0;
                        foreach ($items as $item){


                            $result[$i]['item_code']=$item['item_code'];
                            $result[$i]['product']=$item->product()->first()->title;
                            $result[$i]['image']=$item->product()->first()->firstImage()->first()->thumb;
                            $result[$i]['memory']=$item->memory()->first()->memory_value."GB";
                            $result[$i]['color']=$item->color()->first()->color_name;
                            $result[$i]['quantity']=$item['quantity'];
                            $result[$i]['price']=$item['price'];
                            $i++;
                        }

                        //$result['shipping_price']=50;

                        CartItem::whereIn('id',$delete_item_array)->update(['status'=>1,'order_id'=>$order['id']]);//->delete();
                        $returnArray['status']=true;
                        $status_code=200;
                        $returnArray['data'] =['items'=>$result,'amount'=>$price,'shipping_price'=>$shipping_price];
                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }
                    }else{
                        $returnArray['status']=false;
                        $status_code=406;
                        $returnArray['errors'] =['msg'=>'missing data'];
                    }
                }else{
                    $returnArray['status']=false;
                    $status_code=406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }
            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);

    }

    private $orderStatus =['Open','Paid','Sent','Completed','Cancelled'];


    private function orderDetail($order){
        $result['order_id']=$order['order_code'];
        $items = array();
        $i=0;
        foreach ( $order->cart_items()->get() as $item){
            $items[$i]['id']=$item->product()->first()->id;
            $items[$i]['title']=$item->product()->first()->title;
            $items[$i]['model']=$item->product()->first()->brand()->first()->BrandName." ".$item->product()->first()->model()->first()->Modelname;
            $items[$i]['url']='/urun-detay/'.$item->product()->first()->id;
            $items[$i]['imageUrl']=url($item->product()->first()->firstImage()->first()->image);
            $items[$i]['color']=$item->color()->first()->color_name;
            $items[$i]['memory']=$item->memory()->first()->memory_value."GB";
            $items[$i]['quantity']=$item['quantity'];
            $items[$i]['price']=$item['price'];
            $items[$i]['datetime']=$item['price'];
            $i++;
        }
        $result['items']=$items;
        $result['date_time']=(int)Carbon::parse($order['created_at'])->format('U');

        $result['amount']=$order['amount'];
        $result['shipping_price']=$order['shipping_price'];
        $result['cargo_company']=$order->cargo_company()->first()->name;
        if($order['cargo_company_branch_id']>0){
            $result['cargo_company_branch']=$order->cargo_company_branch()->first()->title;
        }
        $shipping_address=$order->customer_address()->first()->title.", "
            .$order->customer_address()->first()->address;
        $shipping_address.=($order->customer_address()->first()->neighborhood_id>0)?", ".$order->customer_address()->first()->neighborhood()->first()->name:"";
        $shipping_address.=($order->customer_address()->first()->district_id>0)?", ".$order->customer_address()->first()->district()->first()->name:"";
        $shipping_address.=($order->customer_address()->first()->town_id>0)?", ".$order->customer_address()->first()->town()->first()->name:"";
        $shipping_address.=($order->customer_address()->first()->city_id>0)?", ".$order->customer_address()->first()->city()->first()->name:"";

        $result['shipping_address']['name_surname']=$order->customer_address()->first()->name_surname;
        $result['shipping_address']['address']=$shipping_address;
        $result['shipping_address']['phone']=$order->customer_address()->first()->phone_number;
        $result['shipping_address']['phone'].=(!empty($order->customer_address()->first()->phone_number_2))?" (".$order->customer_address()->first()->phone_number_2.")":"";
        $result['invoice_address']=$result['shipping_address'];

        $result['shipping_price']=$order['shipping_price'];
        if($order['order_method']==0){
            $result['order_method']='Credit Card';
        }else{
            $result['order_method']=$order->order_method()->first()->bank_name." - ".$order->order_method()->first()->iban;
        }



        $result['status']=$this->orderStatus[$order['status']];

        return $result;
    }

    private function paymentInfo($order){
        $id= rand(100,200);



        $items = array();
        $items[]=['id'=>$id, 'name'=>'Ara Toplam','value'=>($order['amount']*0.82) ];
        $items[]=['id'=>$id+1, 'name'=>'KDV/Vergi','value'=>($order['amount']*0.18)];
        $items[]=['id'=>$id+2, 'name'=>'Kargo','value'=>$order['shipping_price']];
        return ['details'=>$items,'total'=>$order['amount']+$order['shipping_price']];

    }


    public function orderSummary(Request $request){

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if(!empty($request['customer_id']) ){


                    $customer =  Customer::where('customer_id','=',((!empty($request['customer_id']))?$request['customer_id']:0))->where('status','=',1)->first();
                    $order= Order::with('cart_items.product')->where('customer_id','=',$customer['id'])
                        ->where('order_code','=',((!empty($request['order_id']))?$request['order_id']:0))->first();

                    if(!empty($customer['id']) && !empty($order['id'])&&  $order->cart_items()->count()>0){

                        $returnArray['status']=true;
                        $status_code=200;



                        $returnArray['data'] = array_merge(['order'=>$this->orderDetail($order) ],['payment_informations'=>$this->paymentInfo($order)]);

                        /*
                         *
  "payment_informations": {
    details: [
      {
        id: 1,
        name: "Ara Toplam",
        value: 48000,
      },
      {
        id: 2,
        name: "Kargo",
        value: 60,
      }
    ],
    total: 48060,
  }
}
                         * **/

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $customer->ip_address=$request['ip_address'];
                            $customer->save();
                        }
                    }else{
                        $returnArray['status']=false;
                        $status_code=406;
                        $returnArray['errors'] =['msg'=>'missing data'];
                    }
                }else{
                    $returnArray['status']=false;
                    $status_code=406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }
            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);

    }

    public function orderHistory(Request $request){

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if(!empty($request['customer_id']) ){
                    $customer =  Customer::where('customer_id','=',((!empty($request['customer_id']))?$request['customer_id']:0))->where('status','=',1)->first();

                    $page = (!empty($request['page']))?($request['page']-1):0;
                    $page = ($page<0)?0:$page;
                    $page_count = (!empty($request['page_count']))?$request['page_count']:20;


                    $orders= Order::with('cart_items.product')
                        ->where('customer_id','=',$customer['id'])
                        ->skip($page*$page_count)
                        ->limit($page_count)
                        ->orderBy('created_at','DESC')->get();
                    $order_count=Order::select('id') ->where('customer_id','=',$customer['id'])->count();

                    if(!empty($customer['id'])   ){

                        if(  $orders->count()>0){
                        $result=array();
                        $i=0;
                        foreach ($orders as $order){
                            $result[$i]=$this->orderDetail($order);
                            $i++;
                        }
                        $returnArray['status']=true;
                        $status_code=200;
                        $returnArray['data'] =['orders'=>$result,'item_count'=>$order_count ];
                        //$ch->activation_key=0;
                        }else{
                            $returnArray['status']=true;
                            $status_code=200;
                            $returnArray['data'] =['orders'=>[],'item_count'=>$order_count ];
                        }
                        $customer->ip_address=$request->ip();
                        $customer->save();

                    }else{
                        $returnArray['status']=false;
                        $status_code=406;
                        $returnArray['errors'] =['msg'=>'missing data'];
                    }
                }else{
                    $returnArray['status']=false;
                    $status_code=406;
                    $returnArray['errors'] =['msg'=>'missing data'];
                }
            }else{
                $returnArray['status']=false;
                $status_code=498;
                $returnArray['errors'] =['msg'=>'invalid key'];
            }

        }else{
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] =['msg'=>'method_not_allowed'];
        }
        return response()->json($returnArray,$status_code);

    }

    private function ccPayment($ccno,$expires_at,$cvc,$amount){
        $mo = (int)$expires_at;
        //  return (int)date('Ym').":".$mo;


        if($mo>(int)date('ym') && strlen($ccno)==16 && strlen($cvc)==3 && $amount>0){
            return true;
        }else{
            return  false;
        }
    }

}
