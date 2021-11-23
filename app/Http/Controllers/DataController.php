<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class DataController extends Controller
{
    public function brandlist()
    {

        return view('admin.brand.brandlist', ['brands' => ProductBrand::all()]);
    }

    public function brandadd()
    {

        return view('admin.brand.brandadd');
    }

    public function brandAddPost(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $brand = new ProductBrand();
                $brand->BrandName = $request['brandname'];
                $brand->IconPath = (!empty($request['iconpath'])) ? $request['iconpath'] : '';
                $brand->description = (!empty($request['description'])) ? $request['description'] : '';
                $brand->Seotitle = (!empty($request['seotitle'])) ? $request['seotitle'] : '';
                $brand->SeoDesc = (!empty($request['seodesc'])) ? $request['seodesc'] : '';
                $brand->Status = (!empty($request['status'])) ? 1 : 0;

                $file = $request->file('brand_img');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['brandname']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/brands/");
                    $th = GeneralHelper::fixName($request['brandname']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('brand_img');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $brand->ImageLarge = "images/brands/" . $filename;
                    $brand->Imagethumb = "images/brands/" . $th;
                }

                $brand->save();


                return ['yeni marka eklendi', 'success', route('brand.brandlist'), '', ''];
            });
            return json_encode($resultArray);

        }


    }

    public function brandupdate($id)
    {


        return view('admin.brand.brandupdate', ['brand' => ProductBrand::find($id)]);
    }

    public function brandupdatePost(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $brand = ProductBrand::find($request['id']);
                $brand->BrandName = $request['brandname'];
                $brand->IconPath = (!empty($request['iconpath'])) ? $request['iconpath'] : '';
                $brand->description = (!empty($request['description'])) ? $request['description'] : '';
                $brand->Seotitle = (!empty($request['seotitle'])) ? $request['seotitle'] : '';
                $brand->SeoDesc = (!empty($request['seodesc'])) ? $request['seodesc'] : '';
                $brand->Status = (!empty($request['status'])) ? 1 : 0;

                $file = $request->file('brand_img');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['brandname']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/brands/");
                    $th = GeneralHelper::fixName($request['brandname']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('brand_img');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $brand->ImageLarge = "images/brands/" . $filename;
                    $brand->Imagethumb = "images/brands/" . $th;
                }

                $brand->save();


                return ['Marka Güncellendi', 'success', route('brand.brandlist'), '', ''];
            });
            return json_encode($resultArray);

        }


    }

}
