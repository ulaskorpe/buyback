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
}
