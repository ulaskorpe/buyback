<?php

namespace App\Http\Controllers;

use App\Enums\CartItemStatus;
use App\Enums\CustomerStatus;
use App\Enums\OrderReturnStatus;
use App\Http\Controllers\Helpers\GeneralHelper;
use App\Mail\ForgetPassword;
use App\Mail\Register;

use App\Models\Bank;
use App\Models\CargoCompany;
use App\Models\CartItem;
use App\Models\Color;
use App\Models\ColorModel;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Customer;

use App\Models\CustomerAddress;
use App\Models\CustomerFavorite;
use App\Models\Guest;

use App\Models\GuestCartItem;
use App\Models\Neighborhood;
use App\Models\NewsLetter;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderReturn;
use App\Models\Product;
use App\Models\ProductMemory;
use App\Models\Tmp;
use Carbon\Carbon;
use Faker\Factory;
use PHPUnit\Exception;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
//use Intervention\Image\Facades\Image;


class ApiCustomerController extends Controller
{


    use ApiTrait;
    use CCPayment;

    private function guestWelcome($guid, $c_id)
    {
        $guest = Guest::where('guid', '=', $guid)->first();
        if (!empty($guest['id'])) {

            $items = GuestCartItem::where('guid', '=', $guest['id'])->get();
            foreach ($items as $item) {
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

            GuestCartItem::where('guid', '=', $guest['id'])->delete();
            Guest::where('guid', '=', $guid)->delete();
        }
    }

    public function create(Request $request, Mailer $mailer)
    {
        if ($request->isMethod('post')) {
            $status_code = 201;
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
                        $c->gender = (!empty($request['gender'])) ? $request['gender'] : '';
                        $c->email = $request['email'];
                        $c->password = md5($request['password']);
                        $c->status = 0;
                        $ch = true;

                        while ($ch) {
                            $key = rand(100000, 999999);

                            $ch = $this->checkUnique('activation_key', 'customers', $key);
                        }
                        $c->activation_key = $key;
                        $c->ip_address = $request->ip;

                        $file = $request->file('avatar');
                        if (!empty($file)) {

                            $filename = GeneralHelper::fixName($request['name'] . $request['surname']) . "_"
                                . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                            $path = "images/customers";
                            $th = GeneralHelper::fixName($request['name'] . $request['surname']) . "TH_"
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


                        if (!empty($request['guid'])) {
                            //      $this->guestWelcome($request['guid'],$c['id']);
                        }//////guest


                        //return  response()->json( $c);
                        $returnArray['status'] = true;
                        // $returnArray['status_code'] = 201;

                        $returnArray['data'] = ['customer' => $c];
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
                        $status_code = 409;
                        $returnArray['errors'] = ['msg' => 'conflict/invalid'];


                    }

                } else {
                    $returnArray['status'] = false;
                    //$returnArray['status_code'] = 406;
                    $status_code = 406;
                    //$returnArray['msg'] = 'missing data';
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }


            } else {
                $returnArray['status'] = false;
                //  $returnArray['status_code'] = 498;
                $status_code = 498;
                //   $returnArray['msg'] = 'invalid key';
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }


        } else {
            $returnArray['status'] = false;
            //$returnArray['status_code'] = 405;
            $status_code = 405;

            //      $returnArray['msg'] ='method_not_allowed';
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }

        return response()->json($returnArray, $status_code);
    }

    public function updateProfile(Request $request)
    {


        if ($request->isMethod('post')) {

            if ($request->header('x-api-key') == $this->generateKey()) {
                $status_code = 200;

                if (!empty($request['name']) && !empty($request['surname']) && !empty($request['customer_id'])) {

                    $c = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    $c->name = $request['name'];
                    $c->surname = $request['surname'];

                    if (!empty($request['gender']) && in_array($request['gender'], ['male', 'female'])) {


                        $c->gender = $request['gender'];
                    }
                    $c->tckn = (!empty($request['tckn']))?$request['tckn']:$c->tckn;
                    $c->vergi_no = (!empty($request['vergi_no']))?$request['vergi_no']:$c->vergi_no;

                    $c->ip_address = (!empty($request['ip_address'])) ? $request['ip_address'] : '';
                    $file = $request->file('avatar');
                    if (!empty($file)) {
                        $filename = GeneralHelper::fixName($request['name'] . $request['surname']) . "_"
                            . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                        $path = "images/customers";
                        $th = GeneralHelper::fixName($request['name'] . $request['surname']) . "TH_"
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
                    $returnArray['data'] = ['customer' => $c];


                } else {
                    $returnArray['status'] = false;
                    // $returnArray['status_code'] = 406;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }


            } else {
                $returnArray['status'] = false;
                //$returnArray['status_code'] = 498;
                $status_code = 498;
                //$returnArray['msg'] = 'invalid key';
                $returnArray['errors'] = ['msg' => 'missing data'];
            }


        } else {
            $returnArray['status'] = false;
            //$returnArray['status_code'] = 405;
            $status_code = 405;
            //$returnArray['msg'] ='method_not_allowed';
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }

        return response()->json($returnArray, $status_code);
    }

    public function updatePassword(Request $request)
    {


        if ($request->isMethod('post')) {

            if ($request->header('x-api-key') == $this->generateKey()) {

                $status_code = 200;
                if (!empty($request['customer_id']) && !empty($request['password'])) {

                    $c = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    $c->password = md5($request['password']);
                    $c->ip_address = (!empty($request['ip_address'])) ? $request['ip_address'] : '';


                    $c->save();
                    //return  response()->json( $c);
                    $returnArray['status'] = true;
                    //     $returnArray['status_code'] = 200;
                    $returnArray['data'] = ['customer' => $c];


                } else {
                    $returnArray['status'] = false;
//                    $returnArray['status_code'] = 406;
//                    $returnArray['msg'] = 'missing data';
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }


            } else {
                $returnArray['status'] = false;
                //$returnArray['status_code'] = 498;
                //  $returnArray['msg'] = 'invalid key';
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid_key'];

            }


        } else {
            $returnArray['status'] = false;
            // $returnArray['status_code'] = 405;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }

        return response()->json($returnArray, $status_code);
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
                if (!empty($request['email']) && !empty($request['password'])) {


                    $ch = Customer::select('id', 'customer_id', 'name', 'surname', 'email', 'status')->where('email', '=', $request['email'])
                        ->where('password', '=', md5($request['password']))
                        //    ->where('status','=',1)
                        ->first();

                    if (empty($ch['customer_id'])) {
                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => 'kullanıcı bulunamadı'];
                        return response()->json($returnArray, $status_code);
                    }

                    if ($ch['status'] == 1) {
                        $item_array = array();
                        $cart_items = CartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                            ->where('status', '=', 0)
                            ->where('customer_id', '=', $ch['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get();
                        $i = 0;
                        foreach ($cart_items as $cart_item) {
                            $item_array[$i] = $this->cartItemDetail($cart_item);
                            $i++;
                        }


                        $returnArray['status'] = true;
                        $returnArray['data'] = ['customer' => $ch, 'cart_items' => $item_array];//['customer'=>$ch];


                        if (!empty($request['guid'])) {


                            //   $this->guestWelcome($request['guid'],$ch['id']);
                        }//////guest


                    } elseif ($ch['status'] == 0) {
                        $returnArray['status'] = false;
                        $status_code = 403;
                        $returnArray['data'] = $ch['email'];
                        $returnArray['errors'] = ['msg' => '', 'code' => 'not_verified'];
                    } elseif ($ch['status'] == 2) {
                        $returnArray['status'] = false;
                        $status_code = 403;
                        $returnArray['errors'] = ['msg' => 'banned user'];
                    }


                } else {
                    $returnArray['status'] = false;

                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];

                }


            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];

            }

        } else {
            $returnArray['status'] = false;
            //$returnArray['status_code'] = 405;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function templateFx(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['email']) && !empty($request['password'])) {

                    $ch = Customer::where('email', '=', $request['email'])
                        ->where('password', '=', md5($request['password']))
                        ->first();

                    if ($ch['status'] == 1) {

                        $returnArray['status'] = true;
                        $status_code = 200;
                        $returnArray['data'] = $ch;


                    } elseif ($ch['status'] == 0) {
                        $returnArray['status'] = false;
                        $returnArray['status_code'] = 403;
                        $returnArray['msg'] = 'inactive user';
                    } elseif ($ch['status'] == 2) {
                        $returnArray['status'] = false;
                        $returnArray['status_code'] = 403;
                        $returnArray['msg'] = 'banned user';
                    }


                } else {
                    $returnArray['status'] = false;
                    $returnArray['status_code'] = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }


            } else {
                $returnArray['status'] = false;
                $returnArray['status_code'] = 498;
                $returnArray['msg'] = 'invalid key';
            }

        } else {
            $returnArray['status'] = false;
            $returnArray['status_code'] = 405;

            $returnArray['msg'] = 'method_not_allowed';
        }
        return response()->json($returnArray);
    }

    public function activate(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['email']) && !empty($request['activation_key'])) {


                    $ch = Customer::where('email', '=', $request['email'])
                        ->where('activation_key', '=', $request['activation_key'])
                        ->first();

                    if (!empty($ch['id'])) {

                        if ($ch['status'] == 0) {
                            $ch->status = 1;
                            $ch->activation_key = 0;
                            if (!empty($request['ip_address'])) {
                                $ch->ip_address = $request['ip_address'];
                            }

                            $ch->save();
                            $returnArray['status'] = true;
                            $status_code = 200;
                            $returnArray['data'] = ['customer' => $ch];

                        } else {
                            $returnArray['status'] = false;
                            $status_code = 304;
                            $returnArray['errors'] = ['msg' => "not modified"];

                        }

                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => "not found"];

                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => "missing data"];

                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => "invalid key"];

            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => "method_not_allowed"];
        }
        return response()->json($returnArray, $status_code);
    }

    public function newsletterPost(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['email']) && filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {

                    $n = NewsLetter::where('email', '=', $request['email'])->first();
                    if (empty($n['id'])) {
                        $n = new NewsLetter();
                        $n->customer_id = (!empty($request['customer_id'])) ? $request['customer_id'] : 0;
                        $n->guid = (!empty($request['guid'])) ? $request['guid'] : '';
                        $n->email = $request['email'];
                        $n->status = 1;
                    } else {
                        $n->customer_id = (!empty($request['customer_id'])) ? $request['customer_id'] : 0;
                        $n->guid = (!empty($request['guid'])) ? $request['guid'] : '';

                    }
                    $n->save();

                    $returnArray['status'] = true;
                    $status_code = 201;
                    $returnArray['data'] = $n;


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => "missing data"];

                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => "invalid key"];

            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => "method_not_allowed"];
        }
        return response()->json($returnArray, $status_code);
    }

    public function contactPost(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                // if(!empty($request['email']) && filter_var($request['email'], FILTER_VALIDATE_EMAIL)  && !empty($request['message'])){
                if (!empty($request['message'])) {

                    if (!empty($request['customer_id'])) {
                        $customer = Customer::where('customer_id', '=', $request['customer_id'])->first();
                        $name = $customer['name'];
                        $surname = $customer['surname'];
                        $email = $customer['email'];
                    } else {
                        $name = '';
                        $surname = '';
                        $email = '';

                    }

                    $c = new Contact();
                    $c->customer_id = (!empty($request['customer_id'])) ? $request['customer_id'] : 0;
                    $c->guid = (!empty($request['guid'])) ? $request['guid'] : '';
                    $c->name = (!empty($request['name'])) ? $request['name'] : $name;
                    $c->surname = (!empty($request['surname'])) ? $request['surname'] : $surname;
                    $c->email = (!empty($request['surname'])) ? $request['surname'] : $email;
                    $c->phone_number = (!empty($request['phone_number'])) ? $request['phone_number'] : '';
                    $c->subject = (!empty($request['subject'])) ? $request['subject'] : '';
                    $c->message = (!empty($request['message'])) ? $request['message'] : '';
                    $c->save();
                    if (!empty($request['email'])) {
                        $n = NewsLetter::where('email', '=', $request['email'])->first();

                        if (empty($n['id'])) {
                            $n = new NewsLetter();

                            $n->email = $request['email'];
                            $n->status = 1;
                        } else {
                            $n->customer_id = (!empty($request['customer_id'])) ? $request['customer_id'] : 0;
                            $n->guid = (!empty($request['guid'])) ? $request['guid'] : '';

                        }
                        $n->save();
                    }
                    $returnArray['status'] = true;
                    $status_code = 201;
                    $returnArray['data'] = $c;


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => "missing data"];

                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => "invalid key"];

            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => "method_not_allowed"];
        }
        return response()->json($returnArray, $status_code);
    }

    public function resendActivate(Request $request, Mailer $mailer)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['email'])) {


                    $ch = Customer::where('email', '=', $request['email'])
                        //     ->where('activation_key','=',$request['activation_key'])
                        ->first();

                    if (!empty($ch['id'])) {

                        if ($ch['status'] == 0) {


                            if (!empty($request['ip_address'])) {
                                $ch->ip_address = $request['ip_address'];
                                $ch->save();
                            }

                            $mailer->to($request['email'])->send(new Register($ch['activation_key']));
                            $returnArray['status'] = true;
                            $status_code = 200;
                            $returnArray['data'] = $ch['email'];//['customer'=>$ch];

                        } else {
                            $returnArray['status'] = false;
                            $status_code = 304;
                            $returnArray['errors'] = ['msg' => "zaten aktif"];

                        }

                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => "not found"];

                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => "missing data"];

                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => "invalid key"];

            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => "method_not_allowed"];
        }
        return response()->json($returnArray, $status_code);
    }


    public function forgetPassword(Request $request, Mailer $mailer)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['email'])) {
                    $ch = Customer::select('customer_id', 'email', 'status')
                        ->where('email', '=', $request['email'])->first();

                    if (!empty($ch['customer_id'])) {
                        if ($ch['status'] == 1) {
                            ///////////SEND EMAIL///////////////
                            $pw = GeneralHelper::randomPassword(8, 1);
                            $mailer->to($request['email'])->send(new ForgetPassword($pw));
                            ///////////SEND EMAIL///////////////
                            $ch->password = md5($pw);
                            //$ch->activation_key=0;

                            $ch->ip_address = $request->ip();


                            $ch->save();
                            $returnArray['status'] = true;
                            $returnArray['data'] = $ch['email'];//['customer'=>$ch];
                            $status_code = 200;

///                        $returnArray['status']=true;


                        } elseif ($ch['status'] == 0) {
                            $returnArray['status'] = false;
                            $status_code = 403;
                            $returnArray['data'] = $ch['email'];
                            $returnArray['errors'] = ['msg' => '', 'code' => 'not_verified'];
                        } elseif ($ch['status'] == 2) {
                            $returnArray['status'] = false;
                            $status_code = 403;
                            $returnArray['errors'] = ['msg' => 'banned user'];
                        }


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;

                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;

                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;

                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

///////////////////////////////ADDRESS////////////////////////////////////////////////////////

    public function getAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    if (!empty($ch['id'])) {
                        $address = CustomerAddress::where('customer_id', '=', $ch['id'])->where('id', '=', $request['address_id'])->first();
                        $ch->ip_address = $request->ip();
                        $ch->save();
                        $returnArray['status'] = true;
                        //  $status_code=200;
                        $status_code = 200;
                        $returnArray['data'] = ['address' => $this->makeAddress($address)];


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function showAddresses(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    if (!empty($ch['id'])) {

                        $page = (!empty($request['page'])) ? ($request['page'] - 1) : 0;
                        $page_count = (!empty($request['page_count'])) ? ($request['page_count']) : 5;
                        $addresses = CustomerAddress::where('customer_id', '=', $ch['id'])->skip($page * $page_count)
                            ->limit($page_count)
                            ->orderBy('first', 'DESC')
                            ->orderBy('updated_at', 'DESC')
                            ->get();

                        $array = array();
                        $i = 0;
                        foreach ($addresses as $address) {

                            $array[$i] = $this->makeAddress($address);
                            $i++;
                        }

                        $address_count = CustomerAddress::select('id')->where('customer_id', '=', $ch['id'])->count();
                        $ch->ip_address = $request->ip();
                        $ch->save();


                        $returnArray['status'] = true;
                        //  $status_code=200;
                        $status_code = 200;
                        $returnArray['data'] = ['addresses' => $array, 'item_count' => $address_count];


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function addAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id']) && !empty($request['title']) && !empty($request['city_id']) && !empty($request['address'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    if (!empty($ch['id'])) {


                        $address = new CustomerAddress();
                        $address->customer_id = $ch['id'];
                        $address->title = $request['title'];

                        if (!empty($request['name_surname'])) {
                            $address->name_surname = $request['name_surname'];
                        } else {
                            $address->name_surname = $ch['name'] . " " . $ch['surname'];
                        }
                        $address->address = $request['address'];
                        $address->city_id = $request['city_id'];
                        if (!empty($request['town_id'])) {
                            $address->town_id = $request['town_id'];
                            if (!empty($request['district_id'])) {
                                $address->district_id = $request['district_id'];
                                if (!empty($request['neighborhood_id'])) {
                                    $address->neighborhood_id = $request['neighborhood_id'];
                                } else {
                                    $address->neighborhood_id = 0;
                                }
                            } else {
                                $address->district_id = 0;
                                $address->neighborhood_id = 0;
                            }

                        } else {
                            $address->town_id = 0;
                            $address->district_id = 0;
                            $address->neighborhood_id = 0;
                        }

                        $address->phone_number = (!empty($request['phone_number'])) ? $request['phone_number'] : '';
                        $address->phone_number_2 = (!empty($request['phone_number_2'])) ? $request['phone_number_2'] : '';

                        $ach = CustomerAddress::where('customer_id', '=', $ch['id'])->where('first', '=', 1)->first();
                        if (empty($ach['id'])) {
                            $address->first = 1;
                        } else {

                            if (($request['first'] == 1)) {
                                $address->first = 1;
                                CustomerAddress::where('customer_id', '=', $ch['id'])->update(['first' => 0]);
                            } else {
                                $address->first = 0;
                            }

                        }
                        $address->save();

                        //$ch->activation_key=0;
                        if (!empty($request['ip_address'])) {
                            $ch->ip_address = $request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status'] = true;
                        $status_code = 201;
                        $returnArray['data'] = ['address' => $address];


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;

                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function updateAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id']) && !empty($request['title']) && !empty($request['city_id']) && !empty($request['address_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    $address = CustomerAddress::where('customer_id', '=', $ch['id'])
                        ->where('id', '=', $request['address_id'])->first();

                    if (!empty($ch['id']) && !empty($address['id'])) {


                        $address->title = $request['title'];

                        if (!empty($request['name_surname'])) {
                            $address->name_surname = $request['name_surname'];
                        } else {
                            $address->name_surname = $ch['name'] . " " . $ch['surname'];
                        }
                        $address->address = $request['address'];
                        $address->city_id = $request['city_id'];
                        if (!empty($request['town_id'])) {
                            $address->town_id = $request['town_id'];
                            if (!empty($request['district_id'])) {
                                $address->district_id = $request['district_id'];
                                if (!empty($request['neighborhood_id'])) {
                                    $address->neighborhood_id = $request['neighborhood_id'];
                                } else {
                                    $address->neighborhood_id = 0;
                                }
                            } else {
                                $address->district_id = 0;
                                $address->neighborhood_id = 0;
                            }

                        } else {
                            $address->town_id = 0;
                            $address->district_id = 0;
                            $address->neighborhood_id = 0;
                        }

                        $address->phone_number = (!empty($request['phone_number'])) ? $request['phone_number'] : '';
                        $address->phone_number_2 = (!empty($request['phone_number_2'])) ? $request['phone_number_2'] : '';

                        $ach = CustomerAddress::where('customer_id', '=', $ch['id'])->where('first', '=', 1)->first();
                        if (empty($ach['id'])) {
                            $address->first = 1;
                        } else {

                            if (($request['first'] == 1)) {
                                $address->first = 1;
                                CustomerAddress::where('customer_id', '=', $ch['id'])->update(['first' => 0]);
                            } else {
                                $address->first = 0;
                            }

                        }
                        $address->save();

                        //$ch->activation_key=0;
                        if (!empty($request['ip_address'])) {
                            $ch->ip_address = $request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status'] = true;
                        $status_code = 200;
                        $returnArray['data'] = ['address' => $address];


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;

                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function deleteAddress(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id']) && !empty($request['address_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    $address = CustomerAddress::where('customer_id', '=', $ch['id'])
                        ->where('id', '=', $request['address_id'])->first();

                    if (!empty($ch['id']) && !empty($address['id'])) {


                        if ($address['first'] == 1) {
                            $a = CustomerAddress::where('customer_id', '=', $ch['id'])
                                ->where('id', '<>', $request['address_id'])->first();

                            if (!empty($a['id'])) {
                                $a->first = 1;
                                $a->save();
                            }
                        }
                        $address->delete();

                        //$ch->activation_key=0;
                        if (!empty($request['ip_address'])) {
                            $ch->ip_address = $request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status'] = true;
                        $status_code = 200;
                        //$returnArray['msg'] ='address deleted';


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;

                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;

                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;

                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }

        return response()->json($returnArray, $status_code);
    }

///////////////////////////////ADDRESS////////////////////////////////////////////////////////

/// /////////////////////////// FAVORITES ////////////////////////////////////////////////

    public function addToFavorites(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id']) && !empty($request['product_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();
                    $product = Product::find($request['product_id']);

                    if (!empty($ch['id']) && !empty($product['id'])) {

                        $fav = CustomerFavorite::where('customer_id', '=', $ch['id'])
                            ->where('product_id', '=', $request['product_id'])->first();
                        if (empty($fav['id'])) {
                            $fav = new CustomerFavorite();
                            $fav->customer_id = $ch['id'];
                            $fav->product_id = $request['product_id'];
                        }


                        $fav->memory_id = (!empty($request['memory_id'])) ? $request['memory_id'] : 0;
                        $fav->color_id = (!empty($request['color_id'])) ? $request['color_id'] : 0;
                        $fav->save();

                        //$ch->activation_key=0;
                        if (!empty($request['ip_address'])) {
                            $ch->ip_address = $request['ip_address'];
                            $ch->save();
                        }

                        $returnArray['status'] = true;
                        $status_code = 201;


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;

                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;

                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;

                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function removeFromFavorites(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id']) && !empty($request['product_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();
                    $product = Product::find($request['product_id']);

                    if (!empty($ch['id']) && !empty($product['id'])) {

                        $fav = CustomerFavorite::where('customer_id', '=', $ch['id'])
                            ->where('product_id', '=', $request['product_id'])->first();
                        if (!empty($fav['id'])) {
                            $fav->delete();

                            $returnArray['status'] = true;
                            $status_code = 200;

                        } else {

                            $returnArray['status'] = false;
                            $status_code = 404;

                            $returnArray['errors'] = ['msg' => 'not found'];
                        }

                        //$ch->activation_key=0;
                        if (!empty($request['ip_address'])) {
                            $ch->ip_address = $request['ip_address'];
                            $ch->save();
                        }


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;

                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;

                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;

                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function showFavorites(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    if (!empty($ch['id'])) {


                        $returnArray['status'] = true;
                        $status_code = 200;
                        $returnArray['data'] = CustomerFavorite::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                            ->where('customer_id', '=', $ch['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get();

                        //$ch->activation_key=0;
                        if (!empty($request['ip_address'])) {
                            $ch->ip_address = $request['ip_address'];
                            $ch->save();
                        }


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;

                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;

                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;

                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }


/// /////////////////////////// FAVORITES ////////////////////////////////////////////////
///addToCart


    private function generateItemCode($product_id)
    {
        $ok = true;
        $p = Product::find($product_id);
        while ($ok) {
            $code = "ITEM" . "-" . $p['micro_id'] . "-" . rand(10000, 99999);
            $ch = CartItem::where('item_code', '=', $code)->first();
            $ok = (empty($ch['id'])) ? false : true;
        }

        return $code;
    }

    private function generateOrderCode($customer_id, $guid)
    {

        $order = Order::where('customer_id', '=', $customer_id)->where('guid', '=', $guid)->where('status', '=', CartItemStatus::init)->first();
        if (empty($order['id'])) {
            $order_code = "GRNTL" . date("YmdHis") . rand(1, 9);
            $order = new Order();
            $order->order_code = $order_code;
            $order->name_surname = "";
            $order->cargo_company_id = 0;
            $order->customer_id = $customer_id;
            $order->guid = $guid;
            $order->customer_address_id = 0;
            $order->invoice_address_id = 0;
            $order->order_method = 0;
            $order->status = CartItemStatus::init;
            $order->amount = 0;
            $order->shipping_price = 0;
            $order->receipt = '';
            $order->message = '';
            $order->tckn = '';
            $order->vergi_no = '';
            $order->return_problem_id = 0;
            $order->save();
        } else {
            $order_code = $order['order_code'];
        }
        return $order_code;
    }


    private function createCartItem($customer_id, $guest_id, $product_id, $price)
    {


        if ($customer_id > 0) {

            $cart = CartItem::where('customer_id', '=', $customer_id)
                ->where('product_id', '=', $product_id)
                //  ->where('memory_id','=',$memory_id)
                //->where('color_id','=',$color_id)
                ->where('status', '=', CartItemStatus::init)
                ->first();
            if (empty($cart['id'])) {
                $item_code = $this->generateItemCode($product_id);
                $cart = new CartItem();
                $cart->item_code = $item_code;
                $cart->customer_id = $customer_id;
                $cart->product_id = $product_id;
                $cart->memory_id = 0;
                $cart->color_id = 0;
                $cart->quantity = 1;
                $cart->price = ((float)$price);
                $cart->save();
                return $item_code;
            } else {
                return $cart['item_code'];
            }
        } else {///guest


            $cart = GuestCartItem::where('guid', '=', $guest_id)
                ->where('product_id', '=', $product_id)
                ->first();

            if (empty($cart['id'])) {
                $item_code = $this->generateItemCode($product_id);
                $cart = new GuestCartItem();
                $cart->item_code = $item_code;
                $cart->guid = $guest_id;
                $cart->product_id = $product_id;
                $cart->memory_id = 0;
                $cart->color_id = 0;
                $cart->quantity = 1;
                $cart->price = ((float)$price);
                $cart->save();
                return $item_code;
            } else {
                return $cart['item_code'];
            }


        }


    }

    private function buyTogether($buy_together, $customer_id, $guest_id)
    {
        $together_array = explode(",", trim($buy_together));
        foreach ($together_array as $together) {
            $product_together = Product::select('price', 'price_ex')->find($together);
            $price_together = ($product_together['price_ex'] > $product_together['price']) ? $product_together['price'] : $product_together['price_ex'];
            if ($customer_id > 0) {
                $this->createCartItem($customer_id, 0, $together, $price_together);
            } else {
                $this->createCartItem(0, $guest_id, $together, $price_together);
            }
        }/////////////
    }

    private function updateCartItems($customer_id = 0, $guid = 0)
    {

        if ($customer_id > 0) {
            $items = CartItem::where('customer_id', '=', $customer_id)->where('status', '=', CartItemStatus::init)->get();
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product['status'] == 0 || empty($product['id'])) {
                    $item->delete();
                } else {
                    $price = ($product['price'] > $product['price_ex']) ? $product['price_ex'] : $product['price'];
                    $item->price = $price;
                    $item->save();

                }
            }

        } else {

            $items = GuestCartItem::where('guid', '=', $guid)->where('status', '=', CartItemStatus::init)->get();
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                if ($product['status'] == 0 || empty($product['id'])) {
                    $item->delete();
                } else {
                    $price = ($product['price'] > $product['price_ex']) ? $product['price_ex'] : $product['price'];
                    $item->price = $price;
                    $item->save();

                }
            }
        }


    }


    public function showCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

//                    $customer_id = (!empty($request['customer_id']))?$request['customer_id']:0;
//                    $guid = (!empty($request['guid']))?$request['guid']:0;
                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->where('status','=',CustomerStatus::active)->first();
                    $guest = Guest::where('guid', '=', $request['guid'])->first();

                    if(empty($request['customer_id']) && !empty($request['guid'])){
                    $guest = Guest::where('guid', '=', $request['guid'])->first();
                    if (empty($guest['id'])) {
                        $guest = new Guest();
                        $guest->ip = $request->ip();
                        $guest->guid = $request['guid'];
                        $guest->expires_at = Carbon::now()->addYear(1)->format('Y-m-d');
                        $guest->save();
                    }

                    }///guest
                    $customer_id = (!empty($ch['id']))?$ch['id']:0;
                    $guid = (!empty($guest['id']))?$guest['id']:0;


                    if($guid+$customer_id>0){
                        $this->updateCartItems($customer_id, $guid);
                         $item_array = $this->getCartItems($customer_id,$guid,CartItemStatus::init);
                        $order= Order::with('coupon')->where('customer_id','=',$customer_id)->where('guid','=',$guid)->where('status','=',CartItemStatus::init)->first();
                        if(empty($order['id'])){
                            $order_code = $this->generateOrderCode($customer_id, $guid);
                            $order = Order::with('coupon')->where('order_code', '=', $order_code)->first();
                        }

                            if(!empty($order['coupon_id'])){
                                $discount = $this->calculateDiscount($order->coupon()->first(),$item_array['price']);
                                $coupon_code=$order->coupon()->first()->code;
                            }else{

                                    $discount=0;
                                    $coupon_code = '';
                            }
                            $order->amount=$item_array['price'];
                            $order->discount=$discount;
                            $order->save();
                        $cart_items = array();
                        $i=0;
                        foreach ($item_array['items'] as $cart_item) {
                            $cart_items[$i] = $this->cartItemDetail($cart_item);
                            $i++;
                        }


                        $returnArray['status'] = true;
                        $status_code = 201;
                        $returnArray['data'] = ['cart_items' => $cart_items,'amount' => $item_array['price'],'discount'=>$discount,'coupon_code'=>$coupon_code
                        ,'total'=>$item_array['price']-$discount
                        ];


                    }else{//// notfound

                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => 'not found'];
                    }






            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];

            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }


    private function getCartItems($customer_id=0,$guid=0,$status=0){
        if ($customer_id > 0) {
            $items = CartItem::with('product.firstImage','product.brand', 'product.model', 'product.category')
                ->where('status', '=', $status)
                ->where('customer_id', '=', $customer_id)->get();

        } else {
            $items = GuestCartItem::with('product.firstImage','product.brand', 'product.model', 'product.category')
                ->where('status', '=', $status)
                ->where('guid', '=', $guid)->get();
        }
        $price = 0;
        $item_array = array();
        foreach ($items as $item) {
            $price += $item['price'];
            $item_array[] = $item['id'];
        }
        return ['items'=>$items,'price'=>$price,'item_array'=>$item_array];

    }


    private function calculateDiscount($coupon,$amount){
        if($coupon['amount']>0){
            $discount = $amount-$coupon['amount'];
        }else{
            $discount =(($amount)* (100-$coupon['percentage']))/100;
        }

        return $amount-$discount;
    }

    public function applyCoupon(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                $coupon = Coupon::where('code','=',$request['coupon_code'])->where('is_active','=',1)
                    ->where('expires_at','>=',Carbon::now()->format('Y-m-d'))
                    ->where('usage','>',0)->first();


                if (!empty($coupon['id'])) {
/*
                    if (!empty($request['order_code'])){
                        $order_code= (!empty($request['order_code']))?$request['order_code']:'none';
                        $order= Order::where('order_code','=',$order_code)->first();
                        if(empty($order['id'])){
                            if(!empty($request['customer_id'])){
                                $ch = Customer::where('customer_id','=',$request['customer_id'])->first();
                                $customer_id=(!empty($ch['id']))?$ch['id']:0;
                                $guid=0;
                            }elseif(!empty($request['guid'])){
                                $guest= Guest::where('guid','=',$request['guid'])->first();
                                $guid=(!empt($guest['id']))?$guest['id']:0;
                                $customer_id=0;

                            }else{
                                $customer_id=0;
                                $guid=0;
                            }


                        }


                    }else{

                        if(!empty($request['customer_id'])){
                            $ch = Customer::where('customer_id','=',$request['customer_id'])->first();
                            $customer_id=(!empty($ch['id']))?$ch['id']:0;
                            $guid=0;
                        }else{
                            $guest= Guest::where('guid','=',$request['guid'])->first();
                            $guid=(!empt($guest['id']))?$guest['id']:0;
                            $customer_id=0;

                        }
                    }


*/
                    if(!empty($request['customer_id'])){
                        $ch = Customer::where('customer_id','=',$request['customer_id'])->first();
                        $customer_id=(!empty($ch['id']))?$ch['id']:0;
                        $guid=0;
                    }else{
                        $guest= Guest::where('guid','=',$request['guid'])->first();
                        $guid=(!empt($guest['id']))?$guest['id']:0;
                        $customer_id=0;

                    }


                    if($customer_id+$guid >0){//

//                        $order = Order::where('customer_id','=',$customer_id)->where('guid','=',$guid)->where('status','=',CartItemStatus::init)->first();
//                        return $customer_id.":".$guid;

                    $order_code = $this->generateOrderCode($customer_id, $guid);
                    $order = Order::where('order_code', '=', $order_code)->first();
                    $item_array = $this->getCartItems($customer_id,$guid,CartItemStatus::init);
                        if($order['coupon_id']==0){
                            $coupon->decrement('usage',1);
                        }

                    $order->amount = $item_array['price'];
                    $order->discount = $this->calculateDiscount($coupon,$item_array['price']);
                    $order->coupon_id=$coupon['id'];
                    $order->save();



                        if ($customer_id > 0) {
                            CartItem::whereIn('id', $item_array['item_array'])->update(['order_id' => $order['id']]);
                        } else {
                            GuestCartItem::whereIn('id', $item_array['item_array'])->update(['order_id' => $order['id']]);
                        }

                    $returnArray['status'] = true;
                    $status_code = 200;
                    $returnArray['data'] =['amount'=>$order['amount'],'discount'=>$order['discount'],'total'=>$order['amount']-$order['discount']]; //[$this->calculateDiscount($coupon,$item_array['price'])];


                    }else{////guid+customerid
                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => 'not found'];


                    }

                } else {

                    $returnArray['status'] = false;
                    $status_code = 404;
                    $returnArray['errors'] = ['msg' => 'not found'];


                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];

            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function removeFromCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['item_code']) && (!empty($request['customer_id']) || !empty($request['guid']))) {

                    if (!empty($request['customer_id'])) {


                        $ch = Customer::where('customer_id', '=', $request['customer_id'])->where('status','=',CustomerStatus::active)->first();
                        $item = CartItem::where('item_code', '=', $request['item_code'])->where('customer_id', '=', $ch['id'])->first(); //Product::find($request['product_id']);

                        if (!empty($ch['id']) && !empty($item['id'])) {
                            $item->delete();
                            $returnArray['status'] = true;
                            $status_code = 200;
                            $this->updateCartItems($ch['id'], 0);

                            $ch->ip_address = $request->ip();
                            $ch->save();

                            $item_array = array();
                            $cart_items = CartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                                ->where('status', '=', 0)
                                ->where('customer_id', '=', $ch['id'])
                                ->orderBy('created_at', 'DESC')
                                ->get();
                            $i = 0;

                            foreach ($cart_items as $cart_item) {
                                $item_array[$i] = $this->cartItemDetail($cart_item);
                                $i++;
                            }

                            $returnArray['data'] = ['cart_items' => $item_array];

                        } else {
                            $returnArray['status'] = false;
                            $status_code = 404;
                            $returnArray['errors'] = ['msg' => 'not found'];
                        }

                    } else {////guest

                        $guest = Guest::where('guid', '=', $request['guid'])->first();
                        $item = GuestCartItem::where('item_code', '=', $request['item_code'])->where('guid', '=', (!empty($guest['id']) ? $guest['id'] : 0))->first();

                        if (!empty($guest['id']) && !empty($item['id'])) {
                            $item->delete();

                            $this->updateCartItems(0, $guest['id']);
                            $item_array = array();
                            $cart_items = GuestCartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                                ->where('guid', '=', $guest['id'])
                                ->orderBy('created_at', 'DESC')
                                ->get();
                            $i = 0;

                            foreach ($cart_items as $cart_item) {
                                $item_array[$i] = $this->cartItemDetail($cart_item);
                                $i++;
                            }
                            ///////////////////////////////////////////////////////////////////////

                            $returnArray['status'] = true;
                            $status_code = 200;
                            $returnArray['data'] = ['cart_items' => $item_array];


                        } else {
                            $returnArray['status'] = false;
                            $status_code = 404;
                            $returnArray['errors'] = ['msg' => 'not found'];
                        }


                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    public function updateCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['item_code']) && (!empty($request['customer_id']) || !empty($request['guid']))) {
                    $qty = (!empty($request['quantity'])) ? $request['quantity'] : 1;


                    if (!empty($request['customer_id'])) {


                        $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();


                        $item = CartItem::where('item_code', '=', $request['item_code'])->where('customer_id', '=', $ch['id'])->first(); //Product::find($request['product_id']);

                        if (!empty($ch['id']) && !empty($item['id'])) {

                            $product = Product::find($item['product_id']);

                            if ($request['price'] > 0) {
                                $item->price = ((float)$request['price']) * $qty;
                            } else {
                                $price = ($product['price_ex'] > $product['price']) ? $product['price'] : $product['price_ex'];

                                $item->price = ((float)$price) * $qty;
                            }

                            $item->quantity = $qty;

                            $item->save();

                            $returnArray['status'] = true;
                            $status_code = 200;


                            $ch->ip_address = $request->ip();
                            $ch->save();
                            $this->updateCartItems($ch['id'], 0);
                            $item_array = array();
                            $cart_items = CartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                                ->where('status', '=', 0)
                                ->where('customer_id', '=', $ch['id'])
                                ->where('order_id', '=', 0)
                                ->orderBy('created_at', 'DESC')
                                ->get();
                            $i = 0;
                            foreach ($cart_items as $cart_item) {
                                $item_array[$i] = $this->cartItemDetail($cart_item);
                                $i++;
                            }

                            $returnArray['data'] = ['cart_items' => $item_array];

                        } else {
                            $returnArray['status'] = false;
                            $status_code = 404;
                            $returnArray['errors'] = ['msg' => 'not found'];
                        }

                    } else {////guest

                        $guest = Guest::where('guid', '=', $request['guid'])->first();
                        $item = GuestCartItem::where('item_code', '=', $request['item_code'])->where('guid', '=', (!empty($guest['id']) ? $guest['id'] : 0))->first();

                        if (!empty($guest['id']) && !empty($item['id'])) {


                            if ($request['price'] > 0) {
                                $item->price = ((float)$request['price']) * $qty;
                            } else {
                                $product = Product::find($item['product_id']);
                                $price = ($product['price_ex'] > $product['price']) ? $product['price'] : $product['price_ex'];
                                $item->price = ((float)$price) * $qty;
                            }

                            $item->quantity = $qty;

                            $item->save();
                            $this->updateCartItems(0, $guest['id']);

                            $item_array = array();
                            $cart_items = GuestCartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                                ->where('guid', '=', $guest['id'])
                                ->where('order_id', '=', 0)
                                ->orderBy('created_at', 'DESC')
                                ->get();
                            $i = 0;
                            foreach ($cart_items as $cart_item) {
                                $item_array[$i] = $this->cartItemDetail($cart_item);
                                $i++;
                            }
                            ///////////////////////////////////////////////////////////////////////

                            $returnArray['status'] = true;
                            $status_code = 200;
                            $returnArray['data'] = ['cart_items' => $item_array];


                        } else {
                            $returnArray['status'] = false;
                            $status_code = 404;
                            $returnArray['errors'] = ['msg' => 'not found'];
                        }


                    }


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }

    private function cartItemDetail($cart_item)
    {
        $item_array = array();
        $item_array['item_code'] = $cart_item['item_code'];
        $item_array['price'] = $cart_item['price'];
        //   $item_array['quantity']=$cart_item['quantity'];
        $item_array['created_at'] = $cart_item['created_at'];
        $item_array['product'] = $cart_item->product()->first()->title;
        $item_array['description'] = $cart_item->product()->first()->description;
        $item_array['brand'] = $cart_item->product()->first()->brand()->first()->BrandName;
        $item_array['model'] = $cart_item->product()->first()->model()->first()->Modelname;
        $item_array['category'] = $cart_item->product()->first()->category()->first()->category_name;
        $item_array['imageUrl'] = url($cart_item->product()->first()->firstImage()->first()->image);
        $item_array['thumb'] = url($cart_item->product()->first()->firstImage()->first()->thumb);
//        if($cart_item['color_id']>0){
//            $item_array['color']=$cart_item->color()->first()->color_name;
//        }
//        if($cart_item['memory_id']>0){
//            $item_array['memory']=$cart_item->memory()->first()->memory_value." GB";
//        }
        return $item_array;
    }

    public function addToCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                $product = Product::find($request['product_id']);
                if (!empty($product['id'])) {
                    $price = ($product['price_ex'] > $product['price']) ? $product['price'] : $product['price_ex'];
                    $customer_id = (!empty($request['customer_id']))?$request['customer_id']:0;
                    $guid = (!empty($request['guid']))?$request['guid']:0;
                    $ch = Customer::where('customer_id', '=', $customer_id)->where('status','=',CustomerStatus::active)->first();
                    $guest = Guest::where('guid', '=', $guid)->first();


                    if(empty($request['customer_id']) && !empty($request['guid'])){
                        $guest = Guest::where('guid', '=', $request['guid'])->first();
                        if (empty($guest['id'])) {
                            $guest = new Guest();
                            $guest->ip = $request->ip();
                            $guest->guid = $request['guid'];
                            $guest->expires_at = Carbon::now()->addYear(1)->format('Y-m-d');
                            $guest->save();
                        }

                    }///guest
                    $customer_id = (!empty($ch['id']))?$ch['id']:0;
                    $guid = (!empty($guest['id']))?$guest['id']:0;


                    if($guid+$customer_id>0){


                        $this->updateCartItems($customer_id, $guid);
                        $item_code = $this->createCartItem($customer_id, $guid, $request['product_id'], $price);

                        ///////buy TOGETHER//////////////////////////////////////////
                        if (!empty($request['buy_together'])) {
                            $this->buyTogether($request['buy_together'],$customer_id, $guid);
                        }
                        ///////buy TOGETHER//////////////////////////////////////////



                        $item_array = $this->getCartItems($customer_id,$guid,CartItemStatus::init);

                        $cart_items = array();

                        $i=0;
                        foreach ($item_array['items'] as $cart_item) {
                            $cart_items[$i] = $this->cartItemDetail($cart_item);
                            $i++;
                        }


                        $returnArray['status'] = true;
                        $status_code = 201;
                        $returnArray['data'] = ['item_code' => $item_code, 'cart_items' => $cart_items];


                    }else{//// notfound

                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => 'not found'];
                    }




                } else {

                    $returnArray['status'] = false;
                    $status_code = 404;
                    $returnArray['errors'] = ['msg' => 'not found'];


                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];

            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }


    private function calculateShipping($item_array, $cargo_company_id)
    {
        return 0;//rand(5,10)*10;
    }

    private function addressCheck($customer_id, $address_id = 0)
    {
        if ($address_id > 0) {
            $address = CustomerAddress::find($address_id);
            if ($customer_id != $address['customer_id']) {
                $address = CustomerAddress::where('customer_id', '=', $customer_id)->orderBy('first', 'DESC')->first();
                $address_id = (!empty($address['id'])) ? $address['id'] : 0;
            }
        } else {
            $address = CustomerAddress::where('customer_id', '=', $customer_id)->orderBy('first', 'DESC')->first();
            $address_id = (!empty($address['id'])) ? $address['id'] : 0;
        }
        return $address_id;
    }


    public function getOrderCode(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id'])) {
                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();
                    $cart_items = CartItem::where('status', '=', CartItemStatus::init)
                        ->where('customer_id', '=', $ch['id'])
                        ->count();
                    $customer_id = $ch['id'];
                    $guid = 0;
                    $order = Order::with('coupon')->where('status', '=', CartItemStatus::init)->where('customer_id', '=', $ch['id'])->first();
                } else {//// GUEST
                    $guest = Guest::where('guid', '=', $request['guid'])->first();
                    $cart_items = GuestCartItem::where('guid', '=', $guest['id'])
                        ->where('status', '=', CartItemStatus::init)
                        ->count();
                    $customer_id = 0;
                    $guid = $guest['id'];
                    $order = Order::with('coupon')->where('status', '=', CartItemStatus::init)->where('guid', '=', $guid)->first();
                }


                if ($cart_items > 0) {


                    $returnArray['status'] = true;
                    $status_code = 200;


                    if (empty($order['id'])) {

                        $order_code = $this->generateOrderCode($customer_id, $guid);
                        $order = Order::with('coupon')->where('order_code','=',$order_code)->first();
                    } else {
                        $order_code = $order['order_code'];
                    }

                    $returnArray['data'] = ['order_code' => $order_code,'amount'=>$order['amount'],'discount'=>$order['discount']
                    ,'total'=>$order['amount']-$order['discount'],'coupon_code'=>(!empty($order->coupon()->first())) ? $order->coupon()->first()->code:''
                    ];

                } else {
                    $returnArray['status'] = false;
                    $status_code = 404;
                    $returnArray['errors'] = ['msg' => 'not found'];
                }
            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);
    }


    public function placeOrder(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                $ch = Customer::where('status', '=', CustomerStatus::active)->where('customer_id', '=', $request['customer_id'])->first();

                if (!empty($request['customer_id'])) {

                    $customer_id = (!empty($ch['id'])) ? $ch['id'] : 0;
                    $guid = 0;

                    if ($customer_id == 0) {
                        $returnArray['status'] = false;
                        $status_code = 406;
                        $returnArray['errors'] = ['msg' => 'customer not found'];
                        return response()->json($returnArray, $status_code);
                    }


                    $address_id = $this->addressCheck($ch['id'], (!empty($request['customer_address_id']) ? $request['customer_address_id'] : 0));

                    if ($address_id == 0) {
                        $returnArray['status'] = false;
                        $status_code = 406;
                        $returnArray['errors'] = ['msg' => 'address not found'];
                        return response()->json($returnArray, $status_code);
                    } else {
                        $invoice_address_id = $this->addressCheck($ch['id'], (!empty($request['invoice_address_id']) ? $request['invoice_address_id'] : 0));
                        $invoice_address_id = ($invoice_address_id == 0) ? $address_id : $invoice_address_id;

                    }
                    $name_surname = (!empty($request['name_surname'])) ? $request['name_surname'] : $ch['name'] . " " . $ch['surname'];
                    $ch->ip_address = $request->ip();

                    if(!empty($request['tckn'])){
                        echo $request['tckn'];
                        $ch->tckn = $request['tckn'];
                    }

                    if(!empty($request['vergi_no'])){
                        $ch->tckn = $request['vergi_no'];
                    }
                    $ch->save();


                    return $ch;

                } else {
                    $address_id = 0;
                    $invoice_address_id = 0;
                    $guest = Guest::where('guid', '=', $request['guid'])->first();
                    if (empty($guest['id'])) {
                        $guest = new Guest();
                        $guest->ip = $request->ip();
                        $guest->guid = $request['guid'];
                        $guest->expires_at = Carbon::now()->addYear(1)->format('Y-m-d');
                        $guest->save();
                    }
                    $customer_id = 0;
                    $guid = $guest['id'];
                    $name_surname = (!empty($request['name_surname'])) ? $request['name_surname'] : $guest['guid'];
                }


                if (!empty($request['order_code'])) {
                    $order = Order::where('customer_id', '=', $customer_id)->where('guid', '=', $guid)
                        ->where('order_code', '=', $request['order_code'])->first();


                } else {
                    $order_code = $this->generateOrderCode($customer_id, $guid);
                    $order = Order::where('order_code', '=', $order_code)->first();
                }


                if ($customer_id + $guid == 0) {
                    $returnArray['status'] = false;
                    $status_code = 404;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                    return response()->json($returnArray, $status_code);
                }

/////////////////////////////PAYMENT

                $fileName = "";
                if ($request['payment_method'] > 0) {///havale
                    $bank_id = 0;
                    $taksit = 1;
                    $file = $request->file('receipt');
                    if (!empty($file)) {
                        $fileName = 'receipt/ornek_dekont.pdf';
//                    try{
//                        $fileName = $order['order_code']. "_". date('YmdHis').'.'.$request->file('receipt')->extension();
//                        $request->file('receipt')->move('receipt', $fileName);
//                        $fileName= 'receipt/'.$fileName;
//                    }catch (Exception $e){
//                        $fileName="";
//                    }

                    }


                } else {////kk ödemesi

                    $bank_id = (!empty($request['bank_id'])) ? $request['bank_id'] : 0;
                    $taksit = (!empty($request['taksit'])) ? $request['taksit'] : 1;


//                    $this->makeTmp(date("YmdHis")." Place-Order-CC",
//                        $request['name_surname'].":".$request['cc_no'].",YY:".$request['expiryYY']." MM".$request['expiryMM'].",CVC ".$request['cvc']);

                }////// HAVALE/KK - ÖDEMESİ

/////////////////////////////PAYMENT

                $items_array = $this->getCartItems($customer_id,$guid,CartItemStatus::init);

                $items = $items_array['items'];

                ////////////////////////////////BABA SEPET YOK!!!////////////////////////////

                if ($items->count() == 0) {
                    $returnArray['status'] = false;
                    $status_code = 404;
                    $returnArray['errors'] = ['msg' => 'no item found'];
                    return response()->json($returnArray, $status_code);
                }

                $price = $items_array['price'];
                $delete_item_array = $items_array['item_array'];


                $price = (!empty($request['amount'])) ? $request['amount'] : $price;

                ////////////////////////////////BABA SEPET YOK!!!////////////////////////////

                $cargo_company_id = (!empty($request['cargo_company_id'])) ? $request['cargo_company_id'] : 1;
                $shipping_price = $this->calculateShipping($delete_item_array, $cargo_company_id);


                $order->name_surname = $name_surname;
                $order->cargo_company_id = $request['cargo_company_id'];
                $order->customer_id = $customer_id;
                $order->guid = $guid;
                $order->customer_address_id = $address_id;
                $order->invoice_address_id = $invoice_address_id;
                $order->order_method = (!empty($request['payment_method'])) ? $request['payment_method'] : 0;
                $order->status = CartItemStatus::init;
                $order->cargo_company_id = $cargo_company_id;
                $order->amount = $price;
                $order->banka_id = $bank_id;
                $order->taksit = $taksit;
                $order->shipping_price = $shipping_price;
                $order->receipt = $fileName;
                $order->tckn = (!empty($request['tckn']))?$request['tckn']:'';
                $order->vergi_no = (!empty($request['vergi_no']))?$request['vergi_no']:'';
                $order->save();

/////////////addressss

                if ($guid > 0) {
                    $city_id = (!empty($request['delivery_city_id'])) ? $request['delivery_city_id'] : 0;
                    $phone = (!empty($request['delivery_phone'])) ? $request['delivery_phone'] : '';
                    if (!empty($request['delivery_address'])) {

                        $orderAddress = OrderAddress::where('order_id', '=', $order['id'])->where('invoice', '=', 0)->first();

                        if (empty($orderAddress['id'])) {
                            $orderAddress = new OrderAddress();
                        }

                        $orderAddress->order_id = $order['id'];
                        $orderAddress->name_surname = (!empty($request['delivery_full_name'])) ? $request['delivery_full_name'] : $name_surname;
                        $orderAddress->address = $request['delivery_address'];
                        $orderAddress->city_id = $city_id;
                        $orderAddress->phone = $phone;
                        $orderAddress->invoice = 0;
                        $orderAddress->tckn = (!empty($request['tckn']))?$request['tckn']:'';
                        $orderAddress->vergi_no = (!empty($request['vergi_no']))?$request['vergi_no']:'';
                        $orderAddress->save();
                        if (!empty($request['invoice_address'])) {

                            $invoiceAddress = OrderAddress::where('order_id', '=', $order['id'])->where('invoice', '=', 1)->first();

                            if (empty($invoiceAddress['id'])) {
                                $invoiceAddress = new OrderAddress();
                            }


                            $invoiceAddress->order_id = $order['id'];
                            $invoiceAddress->name_surname = (!empty($request['invoice_full_name'])) ? $request['invoice_full_name'] : $name_surname;
                            $invoiceAddress->address = $request['invoice_address'];
                            $invoiceAddress->city_id = (!empty($request['invoice_city_id'])) ? $request['invoice_city_id'] : $city_id;
                            $invoiceAddress->phone = (!empty($request['invoice_phone'])) ? $request['invoice_phone'] : $phone;
                            $invoiceAddress->invoice = 1;
                            $invoiceAddress->tckn = (!empty($request['tckn']))?$request['tckn']:'';
                            $invoiceAddress->vergi_no = (!empty($request['vergi_no']))?$request['vergi_no']:'';
                            $invoiceAddress->save();


                        }

                    } else {///empty address
                        if (!empty($request['invoice_address'])) {
                            $invoiceAddress = OrderAddress::where('order_id', '=', $order['id'])->where('invoice', '=', 1)->first();

                            if (empty($orderAddress['id'])) {
                                $invoiceAddress = new OrderAddress();
                            }
                            $invoiceAddress->order_id = $order['id'];
                            $invoiceAddress->name_surname = (!empty($request['invoice_name_surname'])) ? $request['invoice_name_surname'] : $name_surname;
                            $invoiceAddress->address = $request['invoice_address'];
                            $invoiceAddress->city_id = (!empty($request['invoice_city_id'])) ? $request['invoice_city_id'] : $city_id;
                            $invoiceAddress->phone = (!empty($request['invoice_phone'])) ? $request['invoice_phone'] : $phone;
                            $invoiceAddress->invoice = 1;
                            $invoiceAddress->tckn = (!empty($request['tckn']))?$request['tckn']:'';
                            $invoiceAddress->vergi_no = (!empty($request['vergi_no']))?$request['vergi_no']:'';
                            $invoiceAddress->save();
                        }


                    }
                }///guid>0
/////////////addressss

                $result = array();
                $i = 0;
                foreach ($items as $item) {
                    $result[$i] = $this->cartItemDetail($item);
                    $i++;
                }

                if ($customer_id > 0) {
                    CartItem::whereIn('id', $delete_item_array)->update(['order_id' => $order['id']]);
                } else {
                    GuestCartItem::whereIn('id', $delete_item_array)->update(['order_id' => $order['id']]);
                }

                ///////////////////////CURL GÖNDER
                /// /**
                /// https://garantili.com.tr/eticaret/api.php

                //?uyeno=2033&storekey=0b763e9cf94fc77819dc408b81c1be93&call=siparis-olustur&ft_telefon=53373749&eticaret_siparis_id=11231
                //&urunler=200-5000-600&
                /// fiyatlar=1000-2500-500.99&takipno=GRNT1212121212&
                /// indirim_kodu=0&indirim_kodundan_tutar=300&toplam_tutar=3700.9&
                /// dekont=www.garantili.com.tr/image/1.jpg&mst_adsoyad=Buse Tokur&
                /// mst_telefon=5337374948&mst_eposta=buse.tokur@ekspar.com.tr&mst_tc=1111111111111&
                /// mst_adres=başakşehir/istanbul&mst_il=34&mst_ilce=80&ft_adsoyad=48
                /// &ft_eposta=buse.tokur@ekspar.com.tr&ft_tc=1111111111111&ft_adres=başakşehir/istanbul&ft_il=34
                /// &ft_ilce=80&ft_vd=Başakşehir&ft_vno=VERGINO232323
                ///
                ///////////////////////CURL GÖNDER

                $returnArray['status'] = true;
                $status_code = 200;
                $returnArray['data'] = ['items' => $result, 'amount' => $price, 'shipping_price' => $shipping_price];
                $returnArray['order_code'] = $order['order_code'];

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);

    }





    public function paymentConfirm(Request $request, Mailer $mailer)
    {

        if ($request->isMethod('post')) {

//            $returnData = json_encode($request);
//
//            $this->makeTmp($request->ip(), $returnData);
            if ($request->header('x-api-key') == $this->generateKey()) {
                //    return response()->json(['order_code'=>$request['order_code'],'result'=>$request['result'] ]);

                $order = Order::with('customer')
                    ->where('status', '=', CartItemStatus::init)
                    ->where('order_code', '=', $request['order_code'])->first();
                if (!empty($order['id'])) {


                    if ($request['result'] == 1) {
                        $this->makeTmp($request->ip(), $request['Order_code'] . ":" . $request['Result'] . ":" . $request['Msg'] . ':' . date('YmdHi'));

                        if ($order['id'] != 52) {

                            if ($order['customer_id'] > 0) {
                                $items = CartItem::where('status', '=', CartItemStatus::init)
                                    ->where('customer_id', '=', $order['customer_id'])
                                    ->where('order_id', '=', $order['id'])->get();
                                //       ->update(['status' => CartItemStatus::paid]);


                                //      $mailer->to($order->customer()->first()->email)->send(new OrderEmail($order['order_code'].' kodlu siparişiniz alınmıştır'));


                            } else {
                                $items = GuestCartItem:: where('status', '=', CartItemStatus::init)
                                    ->where('guid', '=', $order['guid'])
                                    ->where('order_id', '=', $order['id'])->get();
                                //->update(['status' => CartItemStatus::paid]);
                            }

                            foreach ($items as $item) {
                                $item->status = CartItemStatus::paid;
                                $item->save();
                                $product = Product::find($item['product_id']);
                                $product->status = 0;
                                $product->save();

                            }

                            Order::where('id', '=', $order['id'])->update(['status' => CartItemStatus::paid]);
                        }
                        $returnArray['status'] = true;
                        $status_code = 200;

                    } else {////payment error
                        $returnArray['status'] = false;
                        $status_code = 402;


                    }/////payment status


                    $order->message = (!empty($request['msg'])) ? $request['msg'] : '';
                    $order->save();


                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }
        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }


        return response()->json($returnArray, $status_code);

    }

    public function addOrderAddress(Request $request)
    {

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if (!empty($request['order_code'])) {
                    //   $ch =  Customer::where('customer_id','=',$request['customer_id'])->where('status','=',1)->first();

                    $order = Order::where('order_code', '=', $request['order_code'])->first();


                    if (!empty($order['id'])) {
                        $invoice = (!empty($request['invoice'])) ? 1 : 0;
                        $not = ($invoice == 1) ? 0 : 1;
                        $orderAddress = OrderAddress::where('order_id', '=', $order['id'])->where('invoice', '=', $invoice)->first();

                        if (empty($orderAddress['id'])) {
                            $orderAddress = new OrderAddress();
                        }

                        $orderAddress->order_id = $order['id'];
                        $orderAddress->name_surname = (!empty($request['name_surname'])) ? $request['name_surname'] : $order['name_surname'];
                        $orderAddress->address = $request['address'];
                        $orderAddress->city_id = (!empty($request['city_id'])) ? $request['city_id'] : 0;
                        $orderAddress->phone = (!empty($request['phone'])) ? $request['phone'] : '';
                        $orderAddress->invoice = $invoice;
                        $orderAddress->save();


                        OrderAddress::where('order_id', '=', $order['id'])
                            ->where('id', '<>', $orderAddress['id'])
                            ->where('invoice', '=', $invoice)->update(['invoice' => $not]);
                        $returnArray['status'] = true;
                        $status_code = 200;
                        $returnArray['data'] = $orderAddress;
                    } else {
                        $returnArray['status'] = false;
                        $status_code = 406;
                        $returnArray['errors'] = ['msg' => 'missing data'];
                    }
                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }
            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);

    }

    public function cancelOrder(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                //   $ch =  Customer::where('customer_id','=',$request['customer_id'])->where('status','=',CustomerStatus::active)->first();

                $order = Order::where('order_code', '=', $request['order_code'])->first();

                if (!empty($order['id'])) {

                    if ($order['customer_id'] > 0) {
                        $items = CartItem::where('order_id', '=', $order['id'])->get();
                        //->update(['status'=>CartItemStatus::canceled]);

                    } else {
                        $items = GuestCartItem::where('order_id', '=', $order['id'])->get();
                        //->update(['status'=>CartItemStatus::canceled]);

                    }

                    $problem_id = (!empty($request['problem_id'])) ? $request['problem_id'] : 0;
                    $return = OrderReturn::where('order_id', '=', $order['id'])->first();
                    if (empty($return['id'])) {

                        $return = new OrderReturn();
                        $return->order_id = $order['id'];
                        $return->return_code = 'RTN' . date('YmdHis') . '-' . rand(100, 999);
                    }/////return ???
                    $return->name_surname = (!empty($request['name_surname'])) ? $request['name_surname'] : $order['name_surname'];
                    if ($order['status'] == CartItemStatus::sent) {////gönderilmiş ise

                        $return->service_address_id = (!empty($request['service_address_id'])) ? $request['service_address_id'] : 0;
                        $return->cargo_company_id = (!empty($request['cargo_company_id'])) ? $request['cargo_company_id'] : 0;
                        $return->cargo_code = (!empty($request['cargo_code'])) ? $request['cargo_code'] : '';
                        $return->requires_cargo = 1;
                    } elseif ($order['status'] == CartItemStatus::paid) { // sadece ödenmiş
                        $return->service_address_id = 0;
                        $return->cargo_company_id = 0;
                        $return->cargo_code = '';

                        $return->requires_cargo = 0;
                    }

                    $return->iban = (!empty($request['iban'])) ? $request['iban'] : '';
                    $return->iban_name = (!empty($request['iban_name'])) ? $request['iban_name'] : '';
                    $return->bank_name = (!empty($request['bank_name'])) ? $request['bank_name'] : '';
                    $return->status = (!empty($request['cargo_code'])) ? OrderReturnStatus::on_cargo : OrderReturnStatus::init;
                    $return->save();

                    foreach ($items as $item) {
                        $product = Product::find($item['product_id']);
                        $product->status = 1;
                        $product->save();
                        $item->status = CartItemStatus::canceled;
                        $item->save();

                    }

                    Order::where('id', '=', $order['id'])->update(['status' => CartItemStatus::canceled, 'return_problem_id' => $problem_id]);
                    $returnArray['status'] = true;
                    $status_code = 200;
                    $returnArray['data'] = ['order' => $order, 'return' => $return];

                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);

    }

    //private $orderStatus =['Open','Paid','Sent','Completed','Cancelled'];


    private function orderDetail($order)
    {
        $result['order_id'] = $order['order_code'];
        $items = array();
        $i = 0;
        foreach ($order->cart_items()->get() as $item) {
            $items[$i]['id'] = $item->product()->first()->id;
            $items[$i]['micro_id'] = $item->product()->first()->micro_id;
            $items[$i]['title'] = $item->product()->first()->title;
            $items[$i]['model'] = $item->product()->first()->brand()->first()->BrandName . " " . $item->product()->first()->model()->first()->Modelname;
            $items[$i]['url'] = '/urun-detay/' . $item->product()->first()->id;
            $items[$i]['imageUrl'] = url($item->product()->first()->firstImage()->first()->image);
            //    $items[$i]['color']=$item->color()->first()->color_name;
            // $items[$i]['memory']=$item->memory()->first()->memory_value."GB";
            //$items[$i]['quantity']=$item['quantity'];
            $items[$i]['price'] = $item['price'];
            //    $items[$i]['datetime']=$item['price'];
            $i++;
        }
        $result['items'] = $items;
        $result['date_time'] = (int)Carbon::parse($order['created_at'])->format('U');

        $result['amount'] = $order['amount'];
        $result['shipping_price'] = $order['shipping_price'];
        $result['cargo_company'] = $order->cargo_company()->first()->name;
        if ($order['cargo_company_branch_id'] > 0) {
            $result['cargo_company_branch'] = $order->cargo_company_branch()->first()->title;
        }
        $shipping_address = $order->customer_address()->first()->title . ", "
            . $order->customer_address()->first()->address;
        $shipping_address .= ($order->customer_address()->first()->neighborhood_id > 0) ? ", " . $order->customer_address()->first()->neighborhood()->first()->name : "";
        $shipping_address .= ($order->customer_address()->first()->district_id > 0) ? ", " . $order->customer_address()->first()->district()->first()->name : "";
        $shipping_address .= ($order->customer_address()->first()->town_id > 0) ? ", " . $order->customer_address()->first()->town()->first()->name : "";
        $shipping_address .= ($order->customer_address()->first()->city_id > 0) ? ", " . $order->customer_address()->first()->city()->first()->name : "";

        $result['shipping_address']['name_surname'] = $order->customer_address()->first()->name_surname;
        $result['shipping_address']['address'] = $shipping_address;
        $result['shipping_address']['phone'] = $order->customer_address()->first()->phone_number;
        $result['shipping_address']['phone'] .= (!empty($order->customer_address()->first()->phone_number_2)) ? " (" . $order->customer_address()->first()->phone_number_2 . ")" : "";
        $result['invoice_address'] = $result['shipping_address'];

        $result['shipping_price'] = $order['shipping_price'];
        if ($order['order_method'] == 0) {
            $result['order_method'] = 'Credit Card';
        } else {
            $result['order_method'] = $order->order_method()->first()->bank_name . " - " . $order->order_method()->first()->iban;
        }


        $result['status'] = $order['status'];

        return $result;
    }

    private function guestOrderDetail($order)
    {
        $result['order_id'] = $order['order_code'];
        $items = array();
        $i = 0;
        foreach ($order->guest_cart_items()->get() as $item) {
            $items[$i]['id'] = $item->product()->first()->id;
            $items[$i]['micro_id'] = $item->product()->first()->micro_id;
            $items[$i]['title'] = $item->product()->first()->title;
            $items[$i]['model'] = $item->product()->first()->brand()->first()->BrandName . " " . $item->product()->first()->model()->first()->Modelname;
            $items[$i]['url'] = '/urun-detay/' . $item->product()->first()->id;
            $items[$i]['imageUrl'] = url($item->product()->first()->firstImage()->first()->image);
            //    $items[$i]['color']=$item->color()->first()->color_name;
            // $items[$i]['memory']=$item->memory()->first()->memory_value."GB";
            //$items[$i]['quantity']=$item['quantity'];
            $items[$i]['price'] = $item['price'];
            //     $items[$i]['datetime']=$item['price'];
            $i++;
        }
        $result['items'] = $items;
        $result['date_time'] = (int)Carbon::parse($order['created_at'])->format('U');

        $orderAddress = OrderAddress::where('order_id', '=', $order['id'])->where('invoice', '=', 0)->first();
        $result['address'] = $orderAddress['name_surname'] . " ," . $orderAddress['address'] . "," . $orderAddress->city()->first()->name . " " . $orderAddress['phone'];

        $result['amount'] = $order['amount'];
        $result['shipping_price'] = $order['shipping_price'];
        $result['cargo_company'] = $order->cargo_company()->first()->name;
        if ($order['cargo_company_branch_id'] > 0) {
            $result['cargo_company_branch'] = $order->cargo_company_branch()->first()->title;
        }

        $result['shipping_price'] = $order['shipping_price'];
        if ($order['order_method'] == 0) {
            $result['order_method'] = 'Credit Card';
        } else {
            $result['order_method'] = $order->order_method()->first()->bank_name . " - " . $order->order_method()->first()->iban;
        }


        //$result['status']=$this->orderStatus[$order['status']];

        return $result;
    }

    private function paymentInfo($order)
    {
        $id = rand(100, 200);


        $items = array();
        $items[] = ['id' => $id, 'name' => 'Ara Toplam', 'value' => ($this->makeFloat($order['amount'] * 0.82))];
        $items[] = ['id' => $id + 1, 'name' => 'KDV/Vergi', 'value' => ($this->makeFloat($order['amount'] * 0.18))];
        $items[] = ['id' => $id + 2, 'name' => 'Kargo', 'value' => $this->makeFloat($order['shipping_price'])];
        return ['details' => $items, 'total' => $this->makeFloat($order['amount'] + $order['shipping_price'])];

    }


    public function orderSummary(Request $request)
    {

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if (!empty($request['customer_id'])) {


                    $customer = Customer::where('customer_id', '=', ((!empty($request['customer_id'])) ? $request['customer_id'] : 0))->where('status', '=', 1)->first();

                    $order = Order::with('cart_items.product')->where('customer_id', '=', $customer['id'])
                        ->where('order_code', '=', ((!empty($request['order_id'])) ? $request['order_id'] : 0))->first();

                    if (!empty($customer['id']) && !empty($order['id']) && $order->cart_items()->count() > 0) {

                        $returnArray['status'] = true;
                        $status_code = 200;
                        $returnArray['data'] = array_merge(['order' => $this->orderDetail($order)], ['payment_informations' => $this->paymentInfo($order)]);

                    } else {
                        $returnArray['status'] = false;
                        $status_code = 406;
                        $returnArray['errors'] = ['msg' => 'missing data'];
                    }
                } else {///guest


                    $guest = Guest::where('guid', '=', ((!empty($request['guid'])) ? $request['guid'] : 0))->first();


                    $order = Order::with('guest_cart_items.product')->where('guid', '=', $guest['id'])
                        ->where('order_code', '=', ((!empty($request['order_id'])) ? $request['order_id'] : 0))->first();


                    if (!empty($guest['id']) && !empty($order['id']) && $order->guest_cart_items()->count() > 0) {

                        $returnArray['status'] = true;
                        $status_code = 200;


                        $returnArray['data'] = array_merge(['order' => $this->guestOrderDetail($order)], ['payment_informations' => $this->paymentInfo($order)]);


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 406;
                        $returnArray['errors'] = ['msg' => 'missing data'];
                    }

                }
            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);

    }

    public function orderDetay(Request $request)
    {

        if ($request->isMethod('post')) {
            //if ($request->header('x-api-key') == $this->generateKey()) {
            $data = array();

            $order = Order::with('cart_items.product', 'guest_cart_items.product', 'customer', 'guest', 'customer_address', 'invoice_address', 'cargo_company', 'order_method')
                ->where('order_code', '=', ((!empty($request['order_code'])) ? $request['order_code'] : 0))->first();

            if (empty($order['id'])) {
                $returnArray['status'] = false;
                $status_code = 406;
                $returnArray['errors'] = ['msg' => 'not-found'];
                return response()->json($returnArray, $status_code);
            }


            if (!empty($order['customer_id'])) {


                $customer = Customer::where('id', '=', ((!empty($order['customer_id'])) ? $order['customer_id'] : 0))->where('status', '=', 1)->first();
                $data['customer'] = $customer;


                if (!empty($customer['id']) && !empty($order['id']) && $order->cart_items()->count() > 0) {

                    $returnArray['status'] = true;
                    $status_code = 200;
                    //     $returnArray['data'] = $data;//array_merge(['order'=>$this->orderDetail($order) ],['payment_informations'=>$this->paymentInfo($order)]);
                    $data['order'] = $this->orderDetail($order);

                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missssing data'];
                    return response()->json($returnArray, $status_code);
                }
            } else {///guest


                $guest = Guest::where('id', '=', ((!empty($order['guid'])) ? $order['guid'] : 0))->first();


                $data['guest'] = $guest;

                if (!empty($guest['id']) && !empty($order['id']) && $order->guest_cart_items()->count() > 0) {

                    $returnArray['status'] = true;
                    $status_code = 200;


                    //   $returnArray['data'] = array_merge(['order'=>$this->guestOrderDetail($order) ],['payment_informations'=>$this->paymentInfo($order)]);
                    $data['order'] = $this->guestOrderDetail($order);

                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }

            }
            $data['payment_informations'] = $this->paymentInfo($order);


        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($data, $status_code);

    }

    public function orderHistory(Request $request)
    {

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if (!empty($request['customer_id'])) {
                    $customer = Customer::where('customer_id', '=', $request['customer_id'])
                        ->where('status', '=', CustomerStatus::active)->first();

                    $page = (!empty($request['page'])) ? ($request['page'] - 1) : 0;
                    $page = ($page < 0) ? 0 : $page;
                    $page_count = (!empty($request['page_count'])) ? $request['page_count'] : 20;


                    $orders = Order::with('cart_items.product')
                        ->where('customer_id', '=', $customer['id'])
                        ->skip($page * $page_count)
                        ->limit($page_count)
                        ->orderBy('created_at', 'DESC')->get();
                    $order_count = Order::select('id')->where('customer_id', '=', $customer['id'])->count();

                    if (!empty($customer['id'])) {

                        if ($orders->count() > 0) {
                            $result = array();
                            $i = 0;
                            foreach ($orders as $order) {
                                $result[$i] = $this->orderDetail($order);
                                $i++;
                            }
                            $returnArray['status'] = true;
                            $status_code = 200;
                            $returnArray['data'] = ['orders' => $result, 'item_count' => $order_count];
                            //$ch->activation_key=0;
                        } else {
                            $returnArray['status'] = true;
                            $status_code = 200;
                            $returnArray['data'] = ['orders' => [], 'item_count' => $order_count];
                        }
                        $customer->ip_address = $request->ip();
                        $customer->save();

                    } else {
                        $returnArray['status'] = false;
                        $status_code = 406;
                        $returnArray['errors'] = ['msg' => 'missing data'];
                    }
                } else {
                    $returnArray['status'] = false;
                    $status_code = 406;
                    $returnArray['errors'] = ['msg' => 'missing data'];
                }
            } else {
                $returnArray['status'] = false;
                $status_code = 498;
                $returnArray['errors'] = ['msg' => 'invalid key'];
            }

        } else {
            $returnArray['status'] = false;
            $status_code = 405;
            $returnArray['errors'] = ['msg' => 'method_not_allowed'];
        }
        return response()->json($returnArray, $status_code);

    }

    private function ccPayment($ccno, $expires_at, $cvc, $amount, $name_surname = null, $purchase = 1, $order_id)
    {
        $date = (int)$expires_at;

        if ($date > (int)date('ym') && strlen($ccno) == 16 && strlen($cvc) == 3 && $amount > 0) {


            $params = ['name_surname' => $name_surname, 'cc_no' => $ccno,
                'expiryYY' => substr($date, 0, 2), 'expiryMM' => substr($date, 2, 2),
                'amount' => $amount, 'purchase' => $purchase, 'cvc' => $cvc, 'order_id' => $order_id
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://garantili.com.tr/eticaretodeme/odeme");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);
            curl_close($ch);

            return response()->json('ok');
            //   return  ($server_output == "ok")? true:false;

        } else {
            return false;
        }
    }

}
