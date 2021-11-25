<?php

namespace App\Http\Controllers;

use App\Models\ColorModel;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){

        return view('admin.index',['brands'=>ProductBrand::all()]);
    }
}
