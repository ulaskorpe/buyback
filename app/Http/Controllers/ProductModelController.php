<?php

namespace App\Http\Controllers;

use App\Models\Memory;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{
    public function modelList()
    {

        return view('admin.model.modellist', ['models' => ProductModel::with('brand', 'category','memory')->get()]);
    }

    public function modelAdd()
    {
        // Giriş Session kontrolü yapılır eğer giriş yoksa logine yönlendirir.
        if (!\App\Http\Helper\UserControl::Usergetcontrol()) {
            return redirect("admin/login");
        }
        return view('admin.category.modeladd',
            ['brands' => ProductBrand::select('BrandName', 'id')->orderBy('BrandName')->get(),
                'categories' => ProductCategory::orderBy('category_name')->get(),'memories'=>Memory::orderBy('memory_value')->get()]);
    }

    public function modelUpdate($id)
    {
        // Giriş Session kontrolü yapılır eğer giriş yoksa logine yönlendirir.
        if (!\App\Http\Helper\UserControl::Usergetcontrol()) {
            return redirect("admin/login");
        }
        return view('admin.category.modelupdate',
            ['brands' => ProductBrand::select('BrandName', 'id')->orderBy('BrandName')->get(),
                'categories' => ProductCategory::orderBy('category_name')->get(),'model'=>ProductModel::find($id)
                ,'memories'=>Memory::orderBy('memory_value')->get()]);
    }
}
