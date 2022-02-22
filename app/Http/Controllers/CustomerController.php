<?php

namespace App\Http\Controllers;

use App\Enums\CartItemStatus;
use App\Enums\CustomerStatus;
use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\CartItem;
use App\Models\City;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{
    use ApiTrait;
    public function customerList(){
        return view('admin.customer.customerlist',['customers'=>Customer::all()]);
    }

    public function customerUpdate ($customer_id,$selected=0){


        //$status_array = CartItemStatus::asArray();




        return view('admin.customer.customer_update',[
            'customer'=>Customer::find($customer_id),'customer_id'=>$customer_id,
            'selected'=>$selected,
            'addresses'=>CustomerAddress::with('neighborhood','district','town','city')->where('customer_id','=',$customer_id)->get(),
            'cart_items'=>CartItem::with('product','product.model','product.brand','color','memory')
                ->where('customer_id','=',$customer_id)->orderBy('created_at')->get(),
            'status_array'=>[0=>'Giriş',1=>'Ödendi',2=>'İptal',3=>'Gönderildi',4=>'Tamamlandı']

        ]);

    }

    public function addressUpdate($customer_id,$address_id){

        return view('admin.customer.customer_address',[
            'address'=>CustomerAddress::find($address_id),
            'address_id'=>$address_id,'cities'=>City::all()]);

    }


    public function checkEmail($email,$customer_id){


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return  "geçersiz e-posta";
        }else{
            $ch= Customer::select('id')->where('id','<>',$customer_id)->where('email','=',$email)->first();
            if(empty($ch['id'])){
                    return "ok";
                }else{
                    return "email kullanımda";
            }
        }


    }


    public function customerUpdatePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $c = Customer::find($request['id']);

                $c->name = $request['name'];
                $c->surname = $request['surname'];
                $c->email = $request['email'];
                //$c->password = md5($request['password']);
                $c->status = $request['status'];



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
                return ['Müşteri Güncellendi', 'success', route('customer.customer-update',$c['id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }


    public function customerUpdatePW(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $c = Customer::find($request['id']);


                $c->password = md5($request['password']);




                $c->save();
                return ['Müşteri şifresi Güncellendi:'.$request['password'], 'success', route('customer.customer-update',$c['id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function customerUpdateAddress(Request $request){


        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {



                $address =  CustomerAddress::find($request['address_id']);

                $ch =  Customer::find($address['customer_id']);


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
                return ['Müşteri adresi  Güncellendi:', 'success', route('customer.customer-update',[$ch['id'],1]), '', ''];
            });
            return json_encode($resultArray);

        }
    }
}
