<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerFavorite;
use App\Models\Neighborhood;
use App\Models\Product;
use Faker\Factory;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ApiCustomerController extends Controller
{



    use ApiTrait;
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {

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
                        $c->email = $request['email'];
                        $c->password = md5($request['password']);
                        $c->status = 0;
                        $ch = true;
                        while ($ch) {
                            $key = rand(100000, 999999);

                            $ch = $this->checkUnique('activation_key', 'customers', $key);
                        }
                        $c->activation_key = $key;
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
                        $returnArray['status_code'] = 201;
                        $returnArray['data'] = $c;

                    } else {
                        $returnArray['status'] = false;
                        $returnArray['status_code'] = 409;
                        $returnArray['msg'] = 'conflict/invalid';
                    }

                } else {
                    $returnArray['status'] = false;
                    $returnArray['status_code'] = 406;
                    $returnArray['msg'] = 'missing data';
                }


            } else {
                $returnArray['status'] = false;
                $returnArray['status_code'] = 498;
                $returnArray['msg'] = 'invalid key';
            }



        }else{
            $returnArray['status'] = false;
            $returnArray['status_code'] = 405;

            $returnArray['msg'] ='method_not_allowed';
        }

        return response()->json($returnArray);
    }

    public function updateProfile(Request $request)
    {



        if ($request->isMethod('post')) {

            if ($request->header('x-api-key') == $this->generateKey()) {


                if (!empty($request['name']) && !empty($request['surname']) && !empty($request['customer_id'])  ) {

                        $c = Customer::where('customer_id','=',$request['customer_id'])->first();

                        $c->name = $request['name'];
                        $c->surname = $request['surname'];
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
                        $returnArray['status_code'] = 200;
                        $returnArray['data'] = $c;


                } else {
                    $returnArray['status'] = false;
                    $returnArray['status_code'] = 406;
                    $returnArray['msg'] = 'missing data';
                }


            } else {
                $returnArray['status'] = false;
                $returnArray['status_code'] = 498;
                $returnArray['msg'] = 'invalid key';
            }



        }else{
            $returnArray['status'] = false;
            $returnArray['status_code'] = 405;

            $returnArray['msg'] ='method_not_allowed';
        }

        return response()->json($returnArray);
    }

    public function updatePassword(Request $request)
    {



        if ($request->isMethod('post')) {

            if ($request->header('x-api-key') == $this->generateKey()) {


                if ( !empty($request['customer_id'])  && !empty($request['password'])) {

                        $c = Customer::where('customer_id','=',$request['customer_id'])->first();

                        $c->password = md5($request['password']);
                        $c->ip_address = (!empty($request['ip_address'])) ? $request['ip_address'] : '';



                        $c->save();
                        //return  response()->json( $c);
                        $returnArray['status'] = true;
                        $returnArray['status_code'] = 200;
                        $returnArray['data'] = $c;


                } else {
                    $returnArray['status'] = false;
                    $returnArray['status_code'] = 406;
                    $returnArray['msg'] = 'missing data';
                }


            } else {
                $returnArray['status'] = false;
                $returnArray['status_code'] = 498;
                $returnArray['msg'] = 'invalid key';
            }



        }else{
            $returnArray['status'] = false;
            $returnArray['status_code'] = 405;

            $returnArray['msg'] ='method_not_allowed';
        }

        return response()->json($returnArray);
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

            if(!empty($request['email']) && !empty($request['password']) ){

                $ch =  Customer::where('email','=',$request['email'])
                    ->where('password','=',md5($request['password']))
                    ->where('status','=',1)
                    ->first();

                    if($ch['status']==1) {

                        $returnArray['status']=true;
                        $returnArray['status_code']=200;
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
                $returnArray['msg'] ='missing data';
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
                        $returnArray['status_code']=200;
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
                $returnArray['msg'] ='missing data';
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
                        $returnArray['status_code']=200;
                        $returnArray['data'] =$ch;

                    }else{
                        $returnArray['status']=false;
                        $returnArray['status_code']=304;
                        $returnArray['msg'] ="not modified";

                    }

                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=404;
                    $returnArray['msg'] ="not found";
                }



            }else{
                $returnArray['status']=false;
                $returnArray['status_code']=406;
                $returnArray['msg'] ='missing data';
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


    public function forgetPassword(Request $request)
    {


        if ($request->isMethod('post')) {
        if ($request->header('x-api-key') == $this->generateKey()) {

            if(!empty($request['email']) ){
                $ch =  Customer::where('email','=',$request['email'])->first();

                if(!empty($ch['id'])){


                    ///////////SEND EMAIL///////////////
                    $pw = GeneralHelper::randomPassword(8,1);

                    ///////////SEND EMAIL///////////////
                    $ch->password= md5($pw);
                    //$ch->activation_key=0;
                    if(!empty($request['ip_address'])){
                        $ch->ip_address=$request['ip_address'];
                    }

                    $ch->save();


                        $returnArray['status']=true;
                        $returnArray['status_code']=200;
                        $returnArray['msg'] ='pw sent';



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=404;
                    $returnArray['msg'] ="not found";
                }



            }else{
                $returnArray['status']=false;
                $returnArray['status_code']=406;
                $returnArray['msg'] ='missing data';
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

///////////////////////////////ADDRESS////////////////////////////////////////////////////////

    public function showAddresses(Request $request)
    {


        if ($request->isMethod('post')) {
        if ($request->header('x-api-key') == $this->generateKey()) {

            if(!empty($request['customer_id']) ){


                $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                if(!empty($ch['id'])){

                        $addresses = CustomerAddress::where('customer_id','=',$ch['id'])->get();

                    //$ch->activation_key=0;
                    if(!empty($request['ip_address'])){
                        $ch->ip_address=$request['ip_address'];
                        $ch->save();
                    }

                        $returnArray['status']=true;
                        $returnArray['status_code']=200;
                        $returnArray['addresses'] =$addresses;



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=404;
                    $returnArray['msg'] ="not found";
                }



            }else{
                $returnArray['status']=false;
                $returnArray['status_code']=406;
                $returnArray['msg'] ='missing data';
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
                        $returnArray['status_code']=201;
                        $returnArray['msg'] ='address added';



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=404;
                    $returnArray['msg'] ="not found";
                }



            }else{
                $returnArray['status']=false;
                $returnArray['status_code']=406;
                $returnArray['msg'] ='missing data';
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
                        $returnArray['status_code']=200;
                        $returnArray['msg'] ='address updated';



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=404;
                    $returnArray['msg'] ="not found";
                }



            }else{
                $returnArray['status']=false;
                $returnArray['status_code']=406;
                $returnArray['msg'] ='missing data';
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
                        $returnArray['status_code']=200;
                        $returnArray['msg'] ='address deleted';



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=404;
                    $returnArray['msg'] ="not found";
                }



            }else{
                $returnArray['status']=false;
                $returnArray['status_code']=406;
                $returnArray['msg'] ='missing data';
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
                        $returnArray['status_code']=201;
                        $returnArray['msg'] ='favorite added';



                    }else{
                        $returnArray['status']=false;
                        $returnArray['status_code']=404;
                        $returnArray['msg'] ="not found";
                    }



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=406;
                    $returnArray['msg'] ='missing data';
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
                            $returnArray['status_code']=200;
                            $returnArray['msg'] ='favorite deleted';
                        }else{

                            $returnArray['status']=false;
                            $returnArray['status_code']=404;
                            $returnArray['msg'] ='favorite not found';
                        }

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }




                    }else{
                        $returnArray['status']=false;
                        $returnArray['status_code']=404;
                        $returnArray['msg'] ="not found";
                    }



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=406;
                    $returnArray['msg'] ='missing data';
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

    public function showFavorites(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) ){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    if(!empty($ch['id'])  ){


                            $returnArray['status']=true;
                            $returnArray['status_code']=200;
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
                        $returnArray['status_code']=404;
                        $returnArray['msg'] ="not found";
                    }



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=406;
                    $returnArray['msg'] ='missing data';
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




/// /////////////////////////// FAVORITES ////////////////////////////////////////////////
///addToCart


    private function generateItemCode(){
            return "ITEM".date("YmdHis").rand(100,2000);
    }

    private function generateOrderCode(){
        return "ORDER".date("YmdHis").rand(100,2000);
    }
    public function addToCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) && !empty($request['product_id'])){
                    $memory_id=(!empty($request['memory_id']))?$request['memory_id']:0;
                    $color_id=(!empty($request['color_id']))?$request['color_id']:0;

                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();
                    $product=  Product::find($request['product_id']);

                    if(!empty($ch['id']) && !empty($product['id'])){

                        $cart = CartItem::where('customer_id','=',$ch['id'])
                            ->where('product_id','=',$request['product_id'])
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

                        $cart->quantity  = (!empty($request['quantity']))?$request['quantity']:1;
                        $cart->save();

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status']=true;
                        $returnArray['status_code']=201;
                        $returnArray['data']=['item_code'=>$item_code];
                        $returnArray['msg'] ='item added to cart';



                    }else{
                        $returnArray['status']=false;
                        $returnArray['status_code']=404;
                        $returnArray['msg'] ="not found";
                    }



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=406;
                    $returnArray['msg'] ='missing data';
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

    public function removeFromCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) && !empty($request['product_id'])){

                    $memory_id=(!empty($request['memory_id']))?$request['memory_id']:0;
                    $color_id=(!empty($request['color_id']))?$request['color_id']:0;

                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();
                    $product=  Product::find($request['product_id']);

                    if(!empty($ch['id']) && !empty($product['id'])){

                        $cart = CartItem::where('customer_id','=',$ch['id'])
                            ->where('product_id','=',$request['product_id'])
                            ->where('memory_id','=',$memory_id)
                            ->where('color_id','=',$color_id)
                            ->first();
                        if(!empty($cart['id'])){
                            $cart->delete();

                            $returnArray['status']=true;
                            $returnArray['status_code']=200;
                            $returnArray['msg'] ='cart item deleted';
                        }else{

                            $returnArray['status']=false;
                            $returnArray['status_code']=404;
                            $returnArray['msg'] ='item not found';
                        }

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }




                    }else{
                        $returnArray['status']=false;
                        $returnArray['status_code']=404;
                        $returnArray['msg'] ="not found";
                    }



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=406;
                    $returnArray['msg'] ='missing data';
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

    public function showCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if(!empty($request['customer_id']) ){


                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->first();

                    if(!empty($ch['id'])  ){


                        $returnArray['status']=true;
                        $returnArray['status_code']=200;
                        $returnArray['data'] =CartItem::with('product.brand','product.model','product.category','color','memory','product.firstImage')
                            ->where('customer_id','=',$ch['id'])
                            ->where('status','=',0)
                            ->orderBy('created_at','DESC')
                            ->get();

                        //$ch->activation_key=0;
                        if(!empty($request['ip_address'])){
                            $ch->ip_address=$request['ip_address'];
                            $ch->save();
                        }




                    }else{
                        $returnArray['status']=false;
                        $returnArray['status_code']=404;
                        $returnArray['msg'] ="not found";
                    }



                }else{
                    $returnArray['status']=false;
                    $returnArray['status_code']=406;
                    $returnArray['msg'] ='missing data';
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

}
