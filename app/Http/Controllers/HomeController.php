<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    use ApiTrait;
    public function index(){
        //return view('index');
        return redirect(route('admin.index'));
    }

    public function login(){
        if(!empty(Session::get('admin_id'))){
            return redirect('/admin');
        }else{
            return view('login');
        }
    }
    public function logout(){

        Session::put('admin_id',null);

        Session::put('sudo',null);
   //     Session::put('Userget',null);
        return redirect(route('index'));
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
            //    return  md5($request['password']);
                if($check['id']){
                    Session::put('admin_id',$check['id']);


                    if(!empty($request['remember_me']))
                    {
                        Cookie::queue("email",$request['email'],120);
                        Cookie::queue("password",$request['password'],120);
                        Cookie::queue("remember",true,120);
                    }

                    return ['Giriş Başarılı', 'success', route('admin.index'), '', ''];
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
}
