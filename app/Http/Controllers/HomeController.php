<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Answer;
use App\Models\BuyBack;
use App\Models\BuyBackAnswer;
use App\Models\BuyBackUser;
use App\Models\City;
use App\Models\Color;
use App\Models\ColorModel;
use App\Models\Country;
use App\Models\Log;
use App\Models\ModelAnswer;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use App\Models\Town;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    use ApiTrait;
    public function home(){

//        Session::put('admin_id',$check['id']);
//        Session::put('name_surname',$check['name']." ".$check['surname']);
//        Session::put('sudo',$check['sudo']);


//        for($i=0;$i<100;$i++){
//            $this->makeTmp($this->randomPassword(16,1),rand(100,85555));
//        }

//return  Session::get('auth_array');
       // return view('react');
        return view('index',['brands'=>ProductBrand::all()]);
      //  return redirect(route('admin.index'));
    }
    public function react(){


         return view('react');

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
                        ,'site'=>$auth['site'],'market_place'=>$auth['market_place'],'products'=>$auth['products']]);

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
