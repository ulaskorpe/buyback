<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    use ApiTrait;
    public function index(){
        return view('index');
    }

    public function login(){
        if(!empty(Session::get('admin_id'))){
            return redirect('/admin');
        }else{
            return view('login');
        }
    }
}
