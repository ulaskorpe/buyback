<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExController extends Controller
{
    public function placeOrder00(Request $request)
    {



        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if(!empty($request['customer_id']) ){
                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->where('status','=',CustomerStatus::active)->first();


                    if(!empty($ch['id']) && !empty($request['cargo_company_id'])  ){

                        $order_status=CartItemStatus::init; //// havale ?

                        $address_id = $this->addressCheck($ch['id'],(!empty($request['customer_address_id'])?$request['customer_address_id']:0));

                        if($address_id == 0){
                            $returnArray['status']=false;
                            $status_code=406;
                            $returnArray['errors'] =['msg'=>'address not found'];
                            return response()->json($returnArray,$status_code);
                        }else{
                            $billing_address_id = $this->addressCheck($ch['id'],(!empty($request['billing_address_id'])?$request['billing_address_id']:0));
                            $billing_address_id = ($billing_address_id==0)?$address_id:$billing_address_id;

                        }

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


                        $shipping_price=$this->calculateShipping($delete_item_array,$request['cargo_company_id']);
                        $order_id=$this->generateOrderCode();

                        $name_surname= (!empty($request['name_surname'])) ? $request['name_surname']:$ch['name']." ".$ch['surname'];
                        if ($request['payment_method'] == 'cc' || $request['payment_method'] == 0) {/// KK ödemesi
                            if (!empty($request['cc_no']) && !empty($request['expires_at']) && !empty($request['cvc'])) {

                                $purchase = (!empty($request['purchase']))?$request['purchase']:1;
                                if (!$this->ccPayment($request['cc_no'], $request['expires_at'], $request['cvc'], ($price + $shipping_price),$name_surname,$purchase,$order_id)) {


                                    //   return $this->ccPayment($request['cc_no'], $request['expires_at'], $request['cvc'], ($price + $shipping_price),$name_surname,$purchase,$order_id);
                                    $returnArray['status'] = false;
                                    $status_code = 402;
                                    $returnArray['errors'] = ['msg' => 'Geçersiz ödeme'];

//                $tmp = new Tmp();
//                $tmp->title = 'KK PAYMENT'.date('Ymd');
//                $tmp->data= $request['cc_no'].":".$request['expires_at'].":".$request['cvc'].":".($price + $shipping_price);
//                $tmp->save();



                                    return response()->json($returnArray, $status_code);
                                }
                                $order_status = CartItemStatus::paid;
                            } else {
                                $returnArray['status'] = false;
                                $status_code = 406;
                                $returnArray['errors'] = ['msg' => 'missing data'];
                                return response()->json($returnArray, $status_code);

                            }
                        }/////CC PAYMENT



                        $file = $request->file('receipt');
                        if (!empty($file)) {
                            $fileName = $order_id. "_". date('YmdHis').'.'.$request->file('receipt')->extension();
                            $request->file('receipt')->move('receipt', $fileName);
                        }else{
                            $fileName="";
                        }

                        $order=new Order();
                        $order->order_code = $order_id;
                        $order->name_surname =$name_surname;
                        $order->cargo_company_id = $request['cargo_company_id'];
                        $order->customer_id= $ch['id'];
                        $order->customer_address_id = $address_id;
                        $order->billing_address_id = $billing_address_id;
                        $order->order_method = (!empty($request['payment_method'])) ? $request['payment_method']:0;
                        $order->status = $order_status;
                        $order->amount = $price;
                        $order->shipping_price = $shipping_price;
                        $order->receipt = 'receipt/'.$fileName;
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

                        CartItem::whereIn('id',$delete_item_array)->update(['status'=>$order_status,'order_id'=>$order['id']]);//->delete();
                        $returnArray['status']=true;
                        $status_code=200;
                        $returnArray['data'] =['items'=>$result,'amount'=>$price,'shipping_price'=>$shipping_price];
                        $returnArray['order_id']=$order_id;
                        //$ch->activation_key=0;

                        $ch->ip_address=$request->ip();
                        $ch->save();

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

    public function placeOrder_yeni(Request $request)
    {



        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                if(!empty($request['customer_id']) ){
                    $ch =  Customer::where('customer_id','=',$request['customer_id'])->where('status','=',CustomerStatus::active)->first();
                    $cargo = CargoCompany::find($request['cargo_company_id']);


                    if(!empty($ch['id']) && !empty($cargo['id'])  ){

                        $order_status=CartItemStatus::init; //// havale ?

                        $address_id = $this->addressCheck($ch['id'],(!empty($request['customer_address_id'])?$request['customer_address_id']:0));

                        if($address_id == 0){
                            $returnArray['status']=false;
                            $status_code=406;
                            $returnArray['errors'] =['msg'=>'address not found'];
                            return response()->json($returnArray,$status_code);
                        }else{
                            $billing_address_id = $this->addressCheck($ch['id'],(!empty($request['billing_address_id'])?$request['billing_address_id']:0));
                            $billing_address_id = ($billing_address_id==0)?$address_id:$billing_address_id;

                        }



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


                        $shipping_price=$this->calculateShipping($delete_item_array,$request['cargo_company_id']);


                        if(false) {//////////////////////PAYMENT
                            if ($request['payment_method'] == 'cc' || $request['payment_method'] == 0) {
                                if (!empty($request['cc_no']) && !empty($request['expires_at']) && !empty($request['cvc'])) {
                                    if (!$this->ccPayment($request['cc_no'], $request['expires_at'], $request['cvc'], ($price + $shipping_price))) {
                                        $returnArray['status'] = false;
                                        $status_code = 402;
                                        $returnArray['errors'] = ['msg' => 'payment_required'];
                                        return response()->json($returnArray, $status_code);
                                    }
                                    $order_status = CartItemStatus::paid;
                                } else {
                                    $returnArray['status'] = false;
                                    $status_code = 406;
                                    $returnArray['errors'] = ['msg' => 'missing data'];
                                    return response()->json($returnArray, $status_code);

                                }
                            }/////CC PAYMENT
                        }//////////////////////PAYMENT



                        $order=new Order();
                        $order->order_code = $this->generateOrderCode();
                        $order->name_surname = (!empty($request['name_surname'])) ? $request['name_surname']:$ch['name']." ".$ch['surname'];
                        $order->cargo_company_id = $request['cargo_company_id'];
                        $order->customer_id= $ch['id'];
                        $order->customer_address_id = $address_id;
                        $order->billing_address_id = $billing_address_id;
                        $order->order_method = (!empty($request['payment_method'])) ? $request['payment_method']:0;
                        $order->status = $order_status;
                        $order->amount = $price;
                        //     $order->shipping_price = $shipping_price;
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

                        CartItem::whereIn('id',$delete_item_array)->update(['order_id'=>$order['id']]);//->delete();
                        $returnArray['status']=true;
                        $status_code=200;
                        $returnArray['data'] =['items'=>$result,'amount'=>$price,'shipping_price'=>$shipping_price];
                        //$ch->activation_key=0;

                        $ch->ip_address=$request->ip();
                        $ch->save();

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

    public function placeOrderKK(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {



                if(!empty($request['customer_id']) ){
                    $ch= Customer::where('status','=',CustomerStatus::active)->where('customer_id','=',$request['customer_id'])->first();
                    $customer_id=(!empty($ch['id']))? $ch['id']:0;
                    $guid=0;

                    $address_id = $this->addressCheck($ch['id'],(!empty($request['customer_address_id'])?$request['customer_address_id']:0));

                    if($address_id == 0){
                        $returnArray['status']=false;
                        $status_code=406;
                        $returnArray['errors'] =['msg'=>'address not found'];
                        return response()->json($returnArray,$status_code);
                    }else{
                        $invoice_address_id = $this->addressCheck($ch['id'],(!empty($request['invoice_address_id'])?$request['invoice_address_id']:0));
                        $invoice_address_id = ($invoice_address_id==0)?$address_id:$invoice_address_id;

                    }
                    $name_surname= (!empty($request['name_surname'])) ? $request['name_surname']:$ch['name']." ".$ch['surname'];
                    $ch->ip_address=$request->ip();
                    $ch->save();

                }else{
                    $address_id=0;
                    $invoice_address_id=0;
                    $guest = Guest::where('guid','=',$request['guid'])->first();
                    if(empty($guest['id'])){
                        $guest = new Guest();
                        $guest->ip = $request->ip();
                        $guest->guid= $request['guid'];
                        $guest->expires_at = Carbon::now()->addYear(1)->format('Y-m-d');
                        $guest->save();
                    }
                    $customer_id=0;
                    $guid=$guest['id'];
                    $name_surname= (!empty($request['name_surname'])) ? $request['name_surname']:$request['guid'];
                }



                if(!empty($request['order_code'])) {
                    $order = Order::where('customer_id','=',$customer_id)->where('guid','=',$guid)
                        ->where('order_code','=',$request['order_code'])->first();


                }else{
                    $order_code = $this->generateOrderCode($customer_id,$guid);
                    $order = Order::where('order_code','=',$order_code)->first();
                }



                if($customer_id + $guid ==0  ){
                    $returnArray['status']=false;
                    $status_code=404;
                    $returnArray['errors'] =['msg'=>'missing data'];
                    return response()->json($returnArray,$status_code);
                }

/////////////////////////////PAYMENT

                $fileName="";
                if($request['payment_method']>0){

                    $file = $request->file('receipt');
                    if (!empty($file)) {
                        $fileName= 'receipt/ornek_dekont.pdf';
//                    try{
//                        $fileName = $order['order_code']. "_". date('YmdHis').'.'.$request->file('receipt')->extension();
//                        $request->file('receipt')->move('receipt', $fileName);
//                        $fileName= 'receipt/'.$fileName;
//                    }catch (Exception $e){
//                        $fileName="";
//                    }

                    }

                }else{//////KKK ÖDEMESİ
                    $expDate = $request['expiryYY'].$request['expiryMM'];

                    $result = $this->findBank($request['bank_id'],$request['taksit'],$request['amount']);


                    if($result['bank_id']==58){
                        $array = $this->yapiKredi($request['amount'],$order['order_code']
                            ,$request['taksit'],$request['name_surname'],$request['cc_no'],$expDate,$request['cvc']);


                        if($array['approved']==0){
                            ////ödeme başarılı
                        //    echo "successs";
                        }else{

                        }

                     //   return response()->json($array);


                    }else{/////diğer bankalar
                   //     return response()->json($result);
                    }

                }////// KKÖDEMESİ

/////////////////////////////PAYMENT


                if($customer_id>0){
                    $items = CartItem::with('product.firstImage','memory','color')
                        ->where('status','=',CartItemStatus::init)
                        ->where('customer_id','=',$customer_id)->get();

                }else{
                    $items = GuestCartItem::with('product.firstImage','memory','color')
                        ->where('status','=',CartItemStatus::init)
                        ->where('guid','=',$guid)->get();
                }

                ////////////////////////////////BABA SEPET YOK!!!////////////////////////////

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

                $price = (!empty($request['amount']))?$request['amount'] : $price*1.18;

                ////////////////////////////////BABA SEPET YOK!!!////////////////////////////

                $cargo_company_id = (!empty($request['cargo_company_id']))?$request['cargo_company_id']:1;
                $shipping_price=$this->calculateShipping($delete_item_array,$cargo_company_id);



                $order->name_surname =$name_surname;
                $order->cargo_company_id = $request['cargo_company_id'];
                $order->customer_id= $customer_id;
                $order->guid=$guid;
                $order->customer_address_id = $address_id;
                $order->invoice_address_id = $invoice_address_id;
                $order->order_method = (!empty($request['payment_method'])) ? $request['payment_method']:0;
                $order->status = CartItemStatus::init;
                $order->cargo_company_id = $cargo_company_id;
                $order->amount = $price;
                $order->banka_id= (!empty($request['bank_id']))?$request['bank_id']:0;
                $order->taksit= (!empty($request['taksit']))?$request['taksit']:0;
                $order->shipping_price = $shipping_price;
                $order->receipt =$fileName;
                $order->save();

/////////////addressss
                $city_id =  (!empty($request['delivery_city_id']))? $request['delivery_city_id']:0;
                $phone=(!empty($request['delivery_phone'])) ? $request['delivery_phone']:'';
                if(!empty($request['delivery_address'])){

                    $orderAddress = OrderAddress::where('order_id','=',$order['id'])->where('invoice','=',0)->first();

                    if(empty($orderAddress['id'])){
                        $orderAddress = new OrderAddress();
                    }

                    $orderAddress->order_id=$order['id'];
                    $orderAddress->name_surname=(!empty($request['delivery_full_name']))?$request['delivery_full_name']:$name_surname;
                    $orderAddress->address = $request['delivery_address'];
                    $orderAddress->city_id =$city_id;
                    $orderAddress->phone = $phone;
                    $orderAddress->invoice = 0;
                    $orderAddress->save();
                    if(!empty($request['invoice_address'])){

                        $invoiceAddress = OrderAddress::where('order_id','=',$order['id'])->where('invoice','=',1)->first();

                        if(empty($invoiceAddress['id'])){
                            $invoiceAddress = new OrderAddress();
                        }




                        $invoiceAddress->order_id=$order['id'];
                        $invoiceAddress->name_surname=(!empty($request['invoice_name_surname']))?$request['invoice_name_surname']:$name_surname;
                        $invoiceAddress->address = $request['invoice_address'];
                        $invoiceAddress->city_id = (!empty($request['invoice_city_id']))? $request['invoice_city_id']:$city_id;
                        $invoiceAddress->phone = (!empty($request['invoice_phone'])) ? $request['invoice_phone']:$phone;
                        $invoiceAddress->invoice =1;
                        $invoiceAddress->save();


                    }

                }else{///empty address
                    if(!empty($request['invoice_address'])){
                        $invoiceAddress = OrderAddress::where('order_id','=',$order['id'])->where('invoice','=',1)->first();

                        if(empty($orderAddress['id'])){
                            $invoiceAddress = new OrderAddress();
                        }
                        $invoiceAddress->order_id=$order['id'];
                        $invoiceAddress->name_surname=(!empty($request['invoice_name_surname']))?$request['invoice_name_surname']:$name_surname;
                        $invoiceAddress->address = $request['invoice_address'];
                        $invoiceAddress->city_id = (!empty($request['invoice_city_id']))? $request['invoice_city_id']:$city_id;
                        $invoiceAddress->phone = (!empty($request['invoice_phone'])) ? $request['invoice_phone']:$phone;
                        $invoiceAddress->invoice =1;
                        $invoiceAddress->save();
                    }


                }
/////////////addressss

                $result = array();
                $i=0;
                foreach ($items as $item){
                    $result[$i]= $this->cartItemDetail($item);
                    $i++;
                }

                if($customer_id>0){
                    CartItem::whereIn('id',$delete_item_array)->update([ 'order_id'=>$order['id']]);
                }else{
                    GuestCartItem::whereIn('id',$delete_item_array)->update([ 'order_id'=>$order['id']]);
                }

                $returnArray['status']=true;
                $status_code=200;
                $returnArray['data'] =['items'=>$result,'amount'=>$price,'shipping_price'=>$shipping_price];
                $returnArray['order_code']=$order['order_code'];

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

    //             }else{//////KKK ÖDEMESİ
//                    $expDate = $request['expiryYY'].$request['expiryMM'];
//
//                    $result = $this->findBank($request['bank_id'],$request['taksit'],$request['amount']);
//
//
//                    if($result['bank_id']==58){
//                    $array = $this->yapiKredi($request['amount'],$order['order_code']
//                        ,$request['taksit'],$request['name_surname'],$request['cc_no'],$expDate,$request['cvc']);
//
//
//
//
//                    if($array['approved']==0){
//                ////ödeme başarılı
//                    //   echo "successs";
//                    }else{
//
//                    }
//
////    return response()->json($array);
//
//
//                        }else{/////diğer bankalar
//                            //return response()->json($result);
//                        }
    public function addToCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                $product = Product::find($request['product_id']);
                if (!empty($product['id'])) {
                    $price = ($product['price_ex'] > $product['price']) ? $product['price'] : $product['price_ex'];

                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();


                    if (!empty($ch['id'])) { ///// customer process
                        $this->updateCartItems($ch['id'], 0);
                        $item_code = $this->createCartItem($ch['id'], 0, $request['product_id'], $price);
                        $ch->ip_address = $request->ip();
                        $ch->save();

                        ///////buy TOGETHER//////////////////////////////////////////
                        if (!empty($request['buy_together'])) {
                            $this->buyTogether($request['buy_together'], $ch['id'], 0);
                        }
                        ///////buy TOGETHER//////////////////////////////////////////

                        $item_array = array();
                        $cart_items = CartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                            ->where('status', '=', CartItemStatus::init)
                            ->where('order_id', '=', 0)
                            ->where('customer_id', '=', $ch['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get();
                        $i = 0;

                        foreach ($cart_items as $cart_item) {
                            $item_array[$i] = $this->cartItemDetail($cart_item);
                            $i++;
                        }
                        $returnArray['status'] = true;
                        $status_code = 201;
                        $returnArray['data'] = ['item_code' => $item_code, 'cart_items' => $item_array];


                    } else {///// guest processs

                        if (!empty($request['guid'])) {
                            // return $request->ip();

                            $guest = Guest::where('guid', '=', $request['guid'])->first();
                            if (empty($guest['id'])) {
                                $guest = new Guest();
                                $guest->ip = $request->ip();
                                $guest->guid = $request['guid'];
                                $guest->expires_at = Carbon::now()->addYear(1)->format('Y-m-d');
                                $guest->save();
                            }
                            $this->updateCartItems(0, $guest['id']);
                            $item_code = $this->createCartItem(0, $guest['id'], $product['id'], $price);
                            if (!empty($request['buy_together'])) {
                                $this->buyTogether($request['buy_together'], 0, $guest['id']);
                            }
                            $item_array = array();
                            $cart_items = GuestCartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                                ->where('guid', '=', $guest['id'])
                                ->where('status', '=', CartItemStatus::init)
                                ->where('order_id', '=', 0)
                                ->orderBy('created_at', 'DESC')
                                ->get();
                            $i = 0;


                            foreach ($cart_items as $cart_item) {
                                $item_array[$i] = $this->cartItemDetail($cart_item);
                                $i++;
                            }

                            $returnArray['status'] = true;
                            $status_code = 201;
                            $returnArray['data'] = ['item_code' => $item_code, 'cart_items' => $item_array];


                        } else {  /////empty!!!!
                            $returnArray['status'] = false;
                            $status_code = 406;
                            $returnArray['errors'] = ['msg' => 'missing data'];
                        }


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


                        $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();
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



    public function showCart(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                if (!empty($request['customer_id'])) {


                    $ch = Customer::where('customer_id', '=', $request['customer_id'])->first();

                    if (!empty($ch['id'])) {


                        $returnArray['status'] = true;
                        $status_code = 200;
                        $item_array = array();

                        $this->updateCartItems($ch['id'], 0);
                        $cart_items = CartItem::with('product.brand', 'product.model', 'product.category', 'color', 'memory', 'product.firstImage')
                            ->where('status', '=', 0)
                            ->where('customer_id', '=', $ch['id'])
                            ->where('order_id', '=', 0)
                            ->orderBy('created_at', 'DESC')
                            ->get();
                        $i = 0;
                        $total = 0;
                        foreach ($cart_items as $cart_item) {
                            $item_array[$i] = $this->cartItemDetail($cart_item);
                            $total += ($cart_item->product()->first()->price > $cart_item->product()->first()->price_ex) ? $cart_item->product()->first()->price_ex : $cart_item->product()->first()->price;
                            $i++;
                        }


                        $returnArray['data'] = ['cart_items' => $item_array, 'amount' => $total];
                        //$ch->activation_key=0;

                        $ch->ip_address = $request->ip();
                        $ch->save();


                    } else {
                        $returnArray['status'] = false;
                        $status_code = 404;
                        $returnArray['errors'] = ['msg' => 'not found'];
                    }


                } else {
                    $guest = Guest::where('guid', '=', $request['guid'])->first();

                    if (!empty($guest['id'])) {
                        ///////////////////////////////////////////////////////////////////////


                        $item_array = array();
                        $this->updateCartItems(0, $guest['id']);
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


    public function applyCoupon(Request $request)
    {


        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {
                $coupon = Coupon::where('code','=',$request['coupon_code'])->where('is_active','=',1)->where('usage','>',0)->first();


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

}
