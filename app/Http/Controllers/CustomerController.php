<?php

namespace App\Http\Controllers;

use App\Enums\CartItemStatus;
use App\Enums\CustomerStatus;
use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\BankAccount;
use App\Models\CargoCompany;
use App\Models\CargoCompanyBranch;
use App\Models\CartItem;
use App\Models\City;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Guest;
use App\Models\GuestCartItem;
use App\Models\NewsLetter;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderReturn;
use App\Models\ServiceAddress;
use App\Models\User;
use Carbon\Carbon;
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
            'cart_items'=>CartItem::with('product','product.model','product.brand','color','memory')->where('status','=',0)
                ->where('customer_id','=',$customer_id)->orderBy('created_at')->get(),
            'status_array'=>[0=>'Giriş',1=>'Ödendi',2=>'İptal',3=>'Gönderildi',4=>'Tamamlandı'],

            'orders'=>Order::with('cart_items')->where('customer_id','=',$customer_id)->orderBy('created_at','DESC')->get()
,'order_status'=>$this->order_status_array
        ]);

    }

    public function deleteCartItem($cart_item_id){
        CartItem::where('id','=',$cart_item_id)->delete();
            return "Ürün sepetten silindi";

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

                $c->tckn = (!empty($request['tckn']))?$request['tckn']:$c->tckn;
                $c->vergi_no = (!empty($request['vergi_no']))?$request['vergi_no']:$c->vergi_no;
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

    public function orders($type='x'){

        if($type!='x'){
            $type_array =[$type];
        }else{
            $type_array= [0,1,2,3,4,5];
        }



            $orders = Order::with('cart_items','guest_cart_items','cart_items.product.firstImage','cart_items.color','cart_items.memory','customer','guest','order_method'
                ,'cargo_company','customer_address.city','customer_address.town','customer_address.district','customer_address.neighborhood')
                ->whereIn('status',$type_array)
                ->orderBy('id','DESC')
                ->get();


       return view('admin.customer.orders',['orders'=>$orders,'order_status'=>$this->order_status_array,'type'=>$type]);
    }

    public function guests(){

       return view('admin.customer.guests',['guests'=>Guest::with('cart_items.order')->orderBy('created_at','DESC')->get()]);
    }

    public function newsletter(){

        return view('admin.customer.newsletter',['newsletters'=>NewsLetter::with('customer')->orderBy('created_at','DESC')->get()]);
    }

    public function contacts(){

        return view('admin.customer.contacts',['contacts'=>Contact::with('customer')->orderBy('created_at','DESC')->get()]);
    }

    public function contactDetail($id){

        return view('admin.customer.contact_detail',['contact'=>Contact::with('customer')->find($id)]);
    }

    public function deleteGuest($guid){
        GuestCartItem::where('guid','=',$guid)->delete();
        Guest::where('id','=',$guid)->delete();

        return "Ziyaretçi silindi";
    }

    public function deleteNewsletter($id){
            NewsLetter::where('id','=',$id)->delete();
            return "Abone Silindi";
    }

    public function deleteContact($id){

        Contact::where('id','=',$id)->delete();
        return "İleti silindi";
    }

    public function orderUpdate($order_id,$selected=0){
        $order = Order::with('cart_items','guest_cart_items','guest','cart_items.product.firstImage','customer','order_method'
            ,'cargo_company','customer_address.city','customer_address.town','customer_address.district','customer_address.neighborhood','coupon')
               ->where('id','=',$order_id)
            ->orderBy('id','DESC')
            ->first();

        $return =  OrderReturn::with('service_address')->where('order_id','=',$order['id'])->first();
            if(empty($return['id'])){
                $return=null;
            }


        if($order['customer_id']==0){
            $deliver_address  = OrderAddress::where('order_id','=',$order['id'])->where('invoice','=',0)->first();
            $invoice_address  = OrderAddress::where('order_id','=',$order['id'])->where('invoice','=',1)->first();
        }else{
            $deliver_address=null;
            $invoice_address=null;
        }


            return view('admin.customer.order_update',['order'=>$order,'order_status'=>$this->order_status_array
                ,'selected'=>$selected,'order_id'=>$order_id,'cargo_companies'=>CargoCompany::with('branches')->get()
                ,'service_addresses'=>ServiceAddress::all(),'bank_accounts'=>BankAccount::all(),
                'deliver_address'=>$deliver_address,'invoice_address'=>$invoice_address,'return'=>$return]);

    }

    public function orderUpdatePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $order = Order::find($request['id']);
                    $order->cargo_company_id=$request['cargo_company_id'];
                    $order->cargo_company_branch_id=$request['cargo_branch_id'];
                    $order->service_address_id=$request['service_address_id'];
                 //   $order->order_method=$request['order_method'];
                    $order->cargo_code=$request['cargo_code'];
                    $order->status=$request['status'];
                    $order->save();

                return ['Sipariş  Güncellendi:', 'success', route('customer.order-update',[$request['id'],1]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function orderCancelUpdatePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                    $return = OrderReturn::find($request['return_id']);

                    if($return['requires_cargo']){
                        $return->cargo_company_id = $request['cargo_company_id'];
                        $return->cargo_code = $request['cargo_code_return'];
                        $return->service_address_id = $request['service_address_id_return'];
                    }else{

                        $return->cargo_company_id = 0;
                        $return->cargo_code = '';
                        $return->service_address_id = 0;
                    }


                    $return->status = $request['status_return'];
                    $return->save();

                return [ 'Sipariş İptali  Güncellendi:', 'success', route('customer.order-update',[$request['id'],2]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function cargoBranchSelect($cc_id,$selected=0){
        $cc=CargoCompany::with('branches')->find($cc_id);

        if($selected>0){
            $txt = "";
            foreach($cc->branches as $branch){
                if($branch['id']==$selected){
                    $txt.="<option value='".$branch['id']."' selected>".$branch['title']."</option>";
                }else{
                    $txt.="<option value='".$branch['id']."'>".$branch['title']."</option>";
                }

            }
        }else{
            $txt = "<option value='0'>Şube Seçiniz</option>";
            foreach($cc->branches as $branch){
                $txt.="<option value='".$branch['id']."'>".$branch['title']."</option>";
            }
        }

        $cargo = "<table><tr>";
        if(!empty($cc['logo'])){
        $cargo.= "<td><img src='".url($cc['logo'])."'></td>";
        }
        $cargo .= "<td><b>Yetkili Kişi :</b>".$cc['person']."<br>";
        $cargo .= "<b>Telefon :</b>".$cc['phone_number']."<br>";
        $cargo .= "<b>Eposta:</b>".$cc['email']."</td>";
        $cargo.="</tr></table>";
        return response()->json(['branches'=>$txt,'cargo'=>$cargo]);


    }

    public function branchDetail($branch_id){


if($branch_id>0){
        $branch=CargoCompanyBranch::with('city','town','district','neighborhood')->find($branch_id);

        $bra="<b>".$branch['title']."</b><br>";
        $bra.="".$branch['person']."<br>";
        $bra.="".$branch['address'].", ";
        $bra.="".$branch->neighborhood()->first()->name.", ";
        $bra.="".$branch->district()->first()->name."<br>";
        $bra.="".$branch->town()->first()->name." ".$branch->neighborhood()->first()->zipcode."<br>";
        $bra.="".$branch->city()->first()->name."<br>";
        $bra.="".$branch['phone_number']."/";
        $bra.="".$branch['phone_number_2'];
}else{
    $bra="";
}

        return response()->json(['branch'=>$bra]);
    }

    public function customerAddressDetail($address_id){


if($address_id>0){
        $address=CustomerAddress::with('city','town','district','neighborhood')->find($address_id);

    $add="<b>".$address['title']."</b><br>";
    $add.="".$address['name_surname']."<br>";
    $add.="".$address['address'].", ";
    $add.="".$address->neighborhood()->first()->name.", ";
    $add.="".$address->district()->first()->name."<br>";
    $add.="".$address->town()->first()->name." ".$address->neighborhood()->first()->zipcode."<br>";
    $add.="".$address->city()->first()->name."<br>";
    $add.="".$address['phone_number']."/";
    $add.="".$address['phone_number_2'];
}else{
    $add="";
}

        return response()->json(['branch'=>$add]);
    }

    public function serviceAddressDetail($address_id){


if($address_id>0){
        $address=ServiceAddress::with('city','town','district','neighborhood')->find($address_id);

    $add="<b>".$address['title']."</b><br>";
    $add.="".$address['name_surname']."<br>";
    $add.="".$address['address'].", ";
    $add.="".$address->neighborhood()->first()->name.", ";
    $add.="".$address->district()->first()->name."<br>";
    $add.="".$address->town()->first()->name." ".$address->neighborhood()->first()->zipcode."<br>";
    $add.="".$address->city()->first()->name."<br>";
    $add.="".$address['phone_number']."/";
    $add.="".$address['phone_number_2'];
}else{
    $add="";
}

        return response()->json(['branch'=>$add]);
    }

    public function bankAccountDetail($bank_id){
        $txt="";
        if($bank_id>0){
            $bank=BankAccount::find($bank_id);
            $txt="<b>".$bank['bank_name']."</b><br>";
            $txt.=$bank['name_surname']."<br>";
            $txt.=$bank['branch']." şb.<br>";
            $txt.="Hsp No:".$bank['account_number']."<br>";
            $txt.="IBAN:".$bank['branch'];
        }
        return response()->json(['bank'=>$txt]);

    }

    public function cargoCodeCheck($cargo_code,$order_id ){

         $ch = Order::select('id')->where('cargo_code','=',$cargo_code)->where('id','<>',$order_id)->first();
         return (empty($ch['id']))?"ok":"no";

    }

    public function coupons(){

        return view('admin.coupon.list',['coupons'=>Coupon::orderBy('created_at','DESC')->get()]);
    }

    public function couponAdd(){
        return view('admin.coupon.create');
    }
    public function couponUpdate($id){
        $coupon=Coupon::with('orders')->where('id','=',$id)->first();

        return view('admin.coupon.update',['coupon'=>$coupon,'coupon_id'=>$id]);
    }


    public function couponAddPost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


                $count = intval($request['last']);
                for($i=0;$i<$count;$i++) {
                    $code = 'GRNTL-' . rand(1000, 9999) . '-' . rand(1000, 9999) . '-' . rand(1000, 9999);
                    $c = Coupon::where('code', '=', $code)->first();
                    if (empty($c['id'])) {
                        $c = new Coupon();
                        $c->code = $code;
                        $c->amount =(empty($request['percentage'])) ? $request['amount']:0;
                        $c->percentage = $request['percentage'];
                        $c->is_active =(!empty($request['status']))?1:0;
                        $c->usage = $request['usage'];
                        $c->expires_at =Carbon::parse($request['expires_at'])->format('Y-m-d');
                            $c->save();
                    }
                }
                    return ['Kupon(lar) eklendi', 'success', route('customer.coupon-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function couponUpdatePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {




                    $c = Coupon::find($request['id']);
                        $c->amount =(empty($request['percentage'])) ? $request['amount']:0;
                        $c->percentage = $request['percentage'];
                        $c->is_active =(!empty($request['status']))?1:0;
                        $c->usage = $request['usage'];
                        $c->expires_at =Carbon::parse($request['expires_at'])->format('Y-m-d');
                        $c->save();


                    return ['Kupon güncellendi', 'success', route('customer.coupon-update',[$request['id']]), '', ''];
            });
            return json_encode($resultArray);

        }
    }
}
