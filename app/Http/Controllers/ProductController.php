<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductLocation;
use App\Models\SiteLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    use ApiTrait;
    public  function productList($brand_id=0,$model_id=0){
        if($model_id>0  ){
            $products=Product::with('images')->where('model_id','=',$model_id)->get();
        }elseif($brand_id>0 && $model_id==0){
            $products=Product::with('images')->where('brand_id','=',$brand_id)->get();
        }else{
            $products=Product::with('images')->get();
        }

        return view('admin.site.product.list',['products'=>$products,'model_id'=>$model_id,'brand_id'=>$brand_id]);
    }

    public function createProduct(){


        return view('admin.site.product.create',['brands'=>ProductBrand::select('id','BrandName')->orderBy('BrandName')->get(),
            'categories'=>ProductCategory::select('id','category_name')->orderBy('category_name')->get(),'colors'=>Color::all()]);
    }

    public function createProductPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $product= new Product();
                $product->title = $request['title'];
                $product->brand_id = $request['brand_id'];
                $product->model_id = $request['model_id'];
                $product->model_id = $request['model_id'];
                $product->category_id = $request['category_id'];
                $product->description =(!empty($request['description'])) ? $request['description']:"";
                $product->price = $request['price'];
                $product->price_ex =(!empty($request['price_ex'])) ? $request['price_ex']:0;
                $product->quantity =(!empty($request['quantity'])) ? $request['quantity']:0;
                $product->status =(!empty($request['status'])) ? 1:0;
                $product->save();

                if(!empty($request['color_list'])){
                    $color_array=explode('@c',$request['color_list']);
                    foreach ($color_array as $c){
                        if(!empty($c)){
                            $pc = new ProductColor();
                            $pc->color_id=$c;
                            $pc->product_id = $product['id'];
                            $pc->save();
                        }
                    }
                }



                return ['yeni ÜRÜN eklendi', 'success', route('site.update-product',[$product['id']]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateProductPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $product= Product::find($request['id']);
                $product->title = $request['title'];
                $product->brand_id = $request['brand_id'];
                $product->model_id = $request['model_id'];
                $product->model_id = $request['model_id'];
                $product->category_id = $request['category_id'];
                $product->description =(!empty($request['description'])) ? $request['description']:"";
                $product->price = $request['price'];
                $product->price_ex =(!empty($request['price_ex'])) ? $request['price_ex']:0;
                $product->quantity =(!empty($request['quantity'])) ? $request['quantity']:0;
                $product->status =(!empty($request['status'])) ? 1:0;
                $product->save();

                ProductColor::where('product_id','=',$product['id'])->delete();

                if(!empty($request['color_list'])){
                    $color_array=explode('@c',$request['color_list']);
                    foreach ($color_array as $c){
                        if(!empty($c)){
                            $pc = new ProductColor();
                            $pc->color_id=$c;
                            $pc->product_id = $product['id'];
                            $pc->save();
                        }
                    }
                }

                $route = (!empty($request['stay']))?route('site.update-product',[$product['id']]):route('site.product-list');

                return ['ÜRÜN güncellendi', 'success', $route, '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateProduct($id,$selected=0){


        $p_colors=ProductColor::where('product_id','=',$id)->pluck('color_id')->toArray();
        $c_value = "";
        foreach ($p_colors as $color){
            $c_value.="@c".$color;
        }

        $locations = ProductLocation::where('product_id','=',$id)->pluck('location_id')->toArray();

        return view('admin.site.product.update',['brands'=>ProductBrand::select('id','BrandName')->orderBy('BrandName')->get(),
            'categories'=>ProductCategory::select('id','category_name')->orderBy('category_name')->get(),'colors'=>Color::all(),'product'=>Product::with('images')->find($id)
            ,'product_colors'=>$p_colors,'product_id'=>$id,'c_value'=>$c_value,'selected'=>$selected
            ,'image_count'=>ProductImage::where('product_id','=',$id)->count()+2,'site_locations'=>SiteLocation::whereNotIn('id',$locations)->get()]);
    }

    public function getColors($model_id){
        return view('admin.site.product.colors',['colors'=>Color::all()]);
    }

    public function addImage(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];


            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                if(!empty($request['first'])){
                    $first=1;
                    ProductImage::where('product_id','=',$request['product_id'])->update(['first'=>0]);
                }else{
                    $first=0;
                }
                $p = Product::find($request['product_id']);
                $image= new ProductImage();
                $image->product_id = $request['product_id'];
                $image->first = $first;
                $image->status = (!empty($request['status']))?1:0;
                $image->order = $request['order'];

                $file = $request->file('image');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($p['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/products");
                    $th = GeneralHelper::fixName($p['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('image');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $image->image = "images/products/" . $filename;
                    $image->thumb = "images/products/" . $th;
                }

                $image->save();


                ProductImage::where('product_id','=',$request['product_id'])
                    ->where('order','>=',$request['order'])
                    ->where('id','<>',$image['id'])
                    ->increment('order');




                return ['yeni ürün resmi eklendi', 'success', route('site.update-product',[$request['product_id'],1]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function changeImageOrder($order,$image_id){
        $image = ProductImage::find($image_id);
        $old = $image['order'];
        $image->order = $order;
        $image->save();


            if($order>$old){
                ProductImage:: where('order','>=',$old)
                    ->where('product_id','=',$image['product_id'])
                    ->where('order','<=',$order)
                    ->where('id','<>',$image['id'])
                    ->decrement('order');

            }else{
                ProductImage:: where('order','<=',$old)
                    ->where('product_id','=',$image['product_id'])
                    ->where('order','>=',$order)
                    ->where('id','<>',$image['id'])
                    ->increment('order');
            }

            return "Resim sırası güncellendi";

    }

    public function changeFirst($image_id){

        $image = ProductImage::find($image_id);
        ProductImage::where('product_id','=',$image['product_id'])->update(['first'=>0]);
        $image->first = 1;
        $image->save();




            return "ilk resim güncellendi";

    }

    public function deleteImage($image_id){

        $image = ProductImage::find($image_id);
        ProductImage::where('product_id','=',$image['product_id'])
            ->where('order','>=',$image['order'])
            ->decrement('order');

        if($image['first']==1){
            $img = ProductImage::where('product_id','=',$image['product_id'])->where('id','<>',$image_id)->inRandomOrder()->limit(1)->first();
          //  return $img['id'];
            $img->first=1;
            $img->save();
        }
     //   unlink(url($image['thumb']));
   //     unlink(url($image['image']));

        File::delete($image['thumb']);
        File::delete($image['image']);

        $image->delete();


            return "resim silindi";

    }

    public function getLocationOrder($product_id,$location_id){
   //     return $product_id;

        $ch = ProductLocation::where('location_id','=',$location_id)->where('product_id','=',$product_id)->first();
        if(!empty($ch['id'])){
        return  "no";
        }else{
        $count = ProductLocation::where('location_id','=',$location_id)->count();

    //    return $count;

    //    $add = ($count == 0)?2:1;
        $txt= "";
        for($i=1;$i<$count+2;$i++){
            $txt.="<option value='".$i."'>".$i."</option>";
        }

        return $txt;
        }
    }


    public function addLocation(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];


            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


             $location = new ProductLocation();
             $location->location_id = $request['location_id'];
             $location->product_id = $request['product_id'];
             $location->order = $request['location_order'];
            $location->save();

            ProductLocation::where('location_id','=',$request['location_id'])
                ->where('id','<>',$location['id'])->where('order','>=',$request['location_order'])->increment('order');

                return ['ürün yeni konuma eklendi', 'success', route('site.update-product',[$request['product_id'],2]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function getLocations($product_id){
        $site_locations= SiteLocation::all();
        $count_array = [];
        foreach ($site_locations as $location){
            $count_array[$location['id']] = ProductLocation::where('location_id','=',$location['id'])->count();
        }


        $locations = ProductLocation::with('location')->where('product_id','=',$product_id)->get();
        return view('admin.site.product.locations',['locations'=>$locations,'count_locations'=>$count_array]);
    }

    public function deleteLocation($location_id){
        $pl = ProductLocation::find($location_id);

        ProductLocation::where('location_id','=',$pl['location_id'])
            ->where('id','<>',$pl['id'])->where('order','>=',$pl['order'])->decrement('order');
            $pl->delete();
        return "Ürün konumdan silindi";

    }

    public function changeLocationOrder($location_id,$new_order){
        $location = ProductLocation::find($location_id);
        $old = $location['order'];
        $location->order = $new_order;
        $location->save();


        if($new_order>$old){
            ProductLocation:: where('order','>=',$old)
                ->where('location_id','=',$location['location_id'])
                ->where('order','<=',$new_order)
                ->where('id','<>',$location['id'])
                ->decrement('order');

        }else{
            ProductLocation:: where('order','<=',$old)
                ->where('location_id','=',$location['location_id'])
                ->where('order','>=',$new_order)
                ->where('id','<>',$location['id'])
                ->increment('order');
        }

        return "Konum sırası güncellendi";
    }
}
