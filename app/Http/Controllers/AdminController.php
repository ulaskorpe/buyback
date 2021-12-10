<?php

namespace App\Http\Controllers;

use App\Enums\BuyBackTypes;
use App\Models\BuyBack;
use App\Models\ColorModel;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    use ApiTrait;
    public function index(){

        //return BuyBackTypes::asArray();
        //for($i=0;$i<10;$i++){
        $models = ProductModel::whereIn('id',BuyBack::distinct('model_id')->pluck('model_id')->toArray())->get();

         $model_array =[];
      //  return $models;


        $i=0;

        $array = [];
        foreach (BuyBackTypes::asArray() as $item){
            $array[$i]['status'] = $this->buyback_status_array[$i];
            $array[$i]['color'] ="#".dechex(rand(1000000,9999999));
            $array[$i]['count'] = BuyBack::where('status','=',$i)->count();
            $i++;
        }
       // return  $array;

         return view('admin.index',['array'=>$array] );
    }

    public function logout(){

        Session::put('admin_id',null);
        Session::put('name_surname',null);
        Session::put('sudo',null);
        //     Session::put('Userget',null);
        return redirect(route('index'));
    }

    public function profile(){
        return view('admin.profile',['groups'=>UserGroup::all(),'user'=>User::with('group')->find(Session::get('admin_id'))]);
    }
    public function profilePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $user = User::find(Session::get('admin_id'));
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->phone = (!empty($request['phone'])) ? $request['phone']:"";

                $user->save();

                return ['Bilgileriniz Güncellendi', 'success', route('admin.profile'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function password(){
        return view('admin.password',['user'=>User::find(Session::get('admin_id'))]);
    }
    public function passwordPost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $user = User::find(Session::get('admin_id'));
                $user->password= md5($request['password']);
                $user->save();

                return ['Şifreniz Güncellendi', 'success', route('admin.password'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function checkPassword($pw){

    }

    public function checkEmail($email){

    }

    public function settings(){
        return view('admin.settings');
    }

    public function settingsPost(Request $request){

    }
}
