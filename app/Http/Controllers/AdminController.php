<?php

namespace App\Http\Controllers;

use App\Models\ColorModel;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function index(){

        return view('admin.index',['brands'=>ProductBrand::all()]);
    }

    public function logout(){

        Session::put('admin_id',null);
        Session::put('name_surname',null);
        Session::put('sudo',null);
        //     Session::put('Userget',null);
        return redirect(route('index'));
    }

    public function profile(){
        return view('admin.profile');
    }
    public function profilePost(Request $request){

    }

    public function password(){
        return view('admin.password');
    }
    public function passwordPost(Request $request){

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
