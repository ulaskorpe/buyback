<?php

namespace App\Http\Controllers;
use App\Enums\BuyBackTypes;
use App\Models\Answer;
use App\Models\BuyBack;
use App\Models\BuyBackAnswer;
use App\Models\BuyBackUser;
use App\Models\City;
use App\Models\Color;
use App\Models\ModelAnswer;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BuyBackController extends Controller
{
    use ApiTrait;

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
                $bb->imei_id = $request['imei_id'];
                $bb->imei = $request['imei'];
                $bb->model_id = $request['model_id'];
                $bb->color_id = $request['color_id_'];
                $bb->offer_price = $request['offered_price'];
                $bb->save();

                //$this->makeTmp($request['imei_id'],$bb['id']);

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
                return ['Alım Talebi Eklendi', 'success', route('buyback.buyback-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function createBuyback()
    {

        return view('admin.buyback.create',['brands'=>ProductBrand::all()]);
    }

    public function buybackList($user_id=0)
    {

        if($user_id==0){
            $bb= BuyBack::with('buybackuser','model','color')->orderBy('created_at', 'DESC')->get();
        }else{
            $bb= BuyBack::where('buyback_user_id','=',$user_id)->with('buybackuser','model','color')->orderBy('created_at', 'DESC')->get();
        }

        return view('admin.buyback.list', ['buybacks' =>$bb,'types'=> BuyBackTypes::asArray(),'bb_status_array'=>$this->buyback_status_array,'u_id'=>$user_id]);
    }

    public function buybackUpdate($bb_id){
        $bb=BuyBack::with('buybackuser','model','color')->find($bb_id);


        $answer_array = BuyBackAnswer::where('buyback_id','=',$bb_id)->pluck('answer_id')->toArray();



        return view('admin.buyback.update',['bb_id'=>$bb_id,'buyback'=>$bb,
            'types'=> BuyBackTypes::asArray(),'bb_status_array'=>$this->buyback_status_array,'colors'=>Color::all(),
            'answer_array'=> implode('x',$answer_array) ]);
    }

    public function buybackUpdateAnswersPost (Request  $request){
        if ($request->isMethod('post')) {

            //    return $request['offered_price'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


                $bb = BuyBack::find($request['buyback_id']);
//                $bb->buyback_user_id = $bbUser['id'];
//                $bb->imei = $request['imei'];
//                $bb->model_id = $request['model_id'];
//                $bb->color_id = $request['color_id_'];
                if($request['keep_offer_price']){
                $bb->offer_price = $request['offered_price'];

                    $txt=$request['offered_price'];
                }else{
                    $bb->offer_price = $request['price'];
                    $txt=$request['price'];
                }
                $bb->save();
                BuyBackAnswer::where('buyback_id','=',$request['buyback_id'])->delete();

                 $answer_array = explode('@', substr($request['calculate_result'], 0, strlen($request['calculate_result']) - 1));
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


                return [ 'Alım Talebi Güncellendi', 'success', route('buyback.buyback-update',$request['buyback_id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function buybackUpdatePost(Request  $request){
        if ($request->isMethod('post')) {
            //    return $request['offered_price'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $bb= BuyBack::find($request['id']);
                $bb->offer_price = $request['offer_price'];
                $bb->status = $request['status'];
                $bb->save();

                return ['Alım Talebi Güncellendi', 'success', route('buyback.buyback-update',$request['id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateUserPost(Request  $request){
        if ($request->isMethod('post')) {
            //    return $request['offered_price'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $bbUser = BuyBackUser::find($request['id']);
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
                $bbUser->save();
                return ['Kullanıcı Güncellendi', 'success', route('buyback.buyback-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function userInfo($user_id){

        return view('admin.buyback.user_info',['user'=>BuyBackUser::with('user','city','town','district','neighborhood')->find($user_id)
        ,'cities'=>City::select('id','name')->orderBy('name')->get()

        ]);
    }
}
