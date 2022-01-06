<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Answer;
use App\Models\Color;
use App\Models\ColorModel;
use App\Models\Memory;
use App\Models\ModelAnswer;
use App\Models\ModelQuestion;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductLocation;
use App\Models\ProductMemory;
use App\Models\ProductModel;
use App\Models\ProductStockMovement;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\Question;
use App\Models\SiteLocation;
use App\Models\VariantGroup;
use App\Models\VariantValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    use ApiTrait;
    public  function productList($brand_id=0,$model_id=0){


        if($model_id>0  ){
            $products=Product::with('images','firstImage')->where('model_id','=',$model_id)->get();
        }elseif($brand_id>0 && $model_id==0){
            $products=Product::with('images','firstImage')->where('brand_id','=',$brand_id)->get();
        }else{
            $products=Product::with('images','firstImage')->get();
        }

        return view('admin.products.list',['products'=>$products,'model_id'=>$model_id,'brand_id'=>$brand_id]);
    }

    public function createProduct($brand_id=0,$model_id=0){


        return view('admin.products.create',['brands'=>ProductBrand::select('id','BrandName')->orderBy('BrandName')->get(),
            'categories'=>ProductCategory::select('id','category_name')->orderBy('category_name')->get()
            ,'p_brand_id'=>$brand_id,'p_model_id'=>$model_id]);
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
                $product->micro_id = (!empty($request['micro_id']))?$request['micro_id']:'';
                $product->brand_id = $request['brand_id'];
                $product->model_id = $request['model_id'];

                $product->category_id = $request['category_id'];
                $product->description =(!empty($request['description'])) ? $request['description']:"";
                $product->price = $request['price'];
                $product->price_ex =(!empty($request['price_ex'])) ? $request['price_ex']:0;
                //$product->quantity =(!empty($request['quantity'])) ? $request['quantity']:0;
                $product->status =(!empty($request['status'])) ? 1:0;
                $product->save();
/*
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

*/

                return ['yeni ÜRÜN eklendi', 'success', route('products.product-update',[$product['id']]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function productUpdatePost(Request $request){
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
                $product->category_id = $request['category_id'];
                $product->micro_id =(!empty($request['micro_id'])) ? $request['micro_id']:"";
                $product->description =(!empty($request['description'])) ? $request['description']:"";
                $product->price = $request['price'];
                $product->price_ex =(!empty($request['price_ex'])) ? $request['price_ex']:0;
                $product->status =(!empty($request['status'])) ? 1:0;
                $product->save();


                $route = (!empty($request['stay']))?route('products.product-update',[$product['id']]):route('products.product-list');

                return ['ÜRÜN güncellendi', 'success', $route, '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function productUpdate($id,$selected=0,$brand_id=0,$model_id=0){



        $p_colors=ProductColor::where('product_id','=',$id)->pluck('color_id')->toArray();
        $c_value = "";////color value
        foreach ($p_colors as $color){
            $c_value.="@c".$color;
        }



        $p_memories=ProductMemory::where('product_id','=',$id)->pluck('memory_id')->toArray();
        $m_value = "";
        foreach ($p_memories as $memory){
            $m_value.="@m".$memory;
        }


        return view('admin.products.update',[
            'brands'=>ProductBrand::select('id','BrandName')->orderBy('BrandName')->get(),
            'categories'=>ProductCategory::select('id','category_name')->orderBy('category_name')->get(),
            'colors'=>Color::all(),'memories'=>Memory::all()
            ,'product'=>Product::with('images')->find($id)
            ,'product_colors'=>$p_colors
            ,'product_memories'=>$p_memories,'m_value'=>$m_value
            ,'stock_colors'=>Color::whereIn('id',$p_colors)->get()
            ,'stock_memories'=>Memory::whereIn('id',$p_memories)->orderBy('memory_value')->get()

            ,'product_id'=>$id,'c_value'=>$c_value,'selected'=>$selected
            ,'image_count'=>ProductImage::where('product_id','=',$id)->count()+2
            ,'variant_value_array'=>ProductVariantValue::where('product_id','=',$id)->pluck('variant_value_id')->toArray()
            ,'p_brand_id'=>$brand_id,'p_model_id'=>$model_id,'variant_groups'=>VariantGroup::with('variants','variants.values')->orderBy('order')->get()
            ,'stock_movements'=>ProductStockMovement::with('color','memory')
                ->where('product_id','=',$id)->orderBy('created_at','DESC')->get()
        ]);
    }

    public function getColors($model_id){
        return view('admin.site.product.colors',['colors'=>Color::all()]);
    }

    public function getCategory($model_id){
        $model = ProductModel::find($model_id);
        return $model['Catid'];
      //  return view('admin.site.product.colors',['colors'=>Color::all()]);
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




                return ['yeni ürün resmi eklendi', 'success', route('products.product-update',[$request['product_id'],1]), '', ''];
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

    /////////////////BRAND-MODEL //////////////////////////////////
    public function addBrand($product_id=0){
        return view('admin.products.brandadd',['product_id'=>$product_id]);
    }

    public function addBrandPost(Request $request)
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
                $brand->Status = (!empty($request['status1'])) ? 1 : 0;

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

                 if (!empty($request['product_id'])){
                     return ['yeni marka eklendi', 'success', route('products.product-update',[$request['product_id'],0,$brand['id']]), '', ''];
                 }else{
                     return ['yeni marka eklendi', 'success', route('products.create-product',[$brand['id']]), '', ''];
                 }


            });
            return json_encode($resultArray);

        }


    }

    public function addModel($brand_id,$product_id=0){

        return view('admin.products.modeladd',['product_id'=>$product_id,'brand_id'=>$brand_id,'brand'=>ProductBrand::find($brand_id)
            ,'categories'=>ProductCategory::all(),'memories'=>Memory::all()]);
    }

    public function addModelPost(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $brand = ProductBrand::select('BrandName')->find($request['brand_id']);
                $ch = true ;
                while($ch){
                    $code = substr(strtolower($brand['BrandName']),0,4).rand(100000,999999);
                    $ch = $this->checkUnique('code','product_models',$code);

                }

                $model = new ProductModel();
                $model->Modelname = $request['modelname'];
                $model->Brandid = $request['brand_id'];
                $model->Catid = $request['cat_id'];
                $model->memory_id = $request['memory_id'];
                $model->Seotitle = (!empty($request['seotitle'])) ? $request['seotitle'] : '';
                $model->SeoDesc = (!empty($request['seodesc'])) ? $request['seodesc'] : '';
                $model->Status = (!empty($request['status2'])) ? 1 : 0;
                $model->code = $code;
                $model->min_price = $request['min_price'];
                $model->max_price = $request['max_price'];
                $file = $request->file('model_img');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['modelname']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/models");
                    $th = GeneralHelper::fixName($request['modelname']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('model_img');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $model->ImageLarge = "images/models/" . $filename;
                    $model->Imagethumb = "images/models/" . $th;
                }

                $model->save();

                $questions = Question::select('id')->get();
                $i=1;
                foreach ($questions as $question){
                    $mq = new ModelQuestion();
                    $mq->question_id = $question['id'];
                    $mq->model_id = $model['id'];
                    $mq->count = $i;
                    $mq->save();
                    $i++;
                    $answers = Answer::where('question_id','=',$question['id'])->orderBy('count')->get();
                    foreach ($answers as  $answer){
                        $ma = new ModelAnswer();
                        $ma->model_id =$model['id'];
                        $ma->answer_id =  $answer['id'];
                        $ma->value =0.00;
                        $ma->save();
                    }

                }


                if (!empty($request['product_id'])){
                    return ['yeni model eklendi', 'success', route('products.product-update',[$request['product_id'],0,$request['brand_id'],$model['id']]), '', ''];
                }else{
                    return ['yeni model eklendi', 'success', route('products.create-product',[$request['brand_id'],$model['id']]), '', ''];
                }
               // return ['yeni model eklendi', 'success', route('products.product-update'), '', ''];
            });
            return json_encode($resultArray);

        }


    }

    public function colorProduct(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


                ProductColor::where('product_id','=',$request['product_id'])->delete();

                if(!empty($request['color_list'])){
                    $color_array=explode('@c',$request['color_list']);
                    foreach ($color_array as $c){
                        if(!empty($c)){
                            $pc = new ProductColor();
                            $pc->color_id=$c;
                            $pc->product_id = $request['product_id'];
                            $pc->save();
                        }
                    }
                }


                return ['Ürün renkleri güncellendi', 'success', route('products.product-update',[$request['product_id'],2]), '', ''];


            });
            return json_encode($resultArray);

        }


    }

    public function memoryProduct(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


                ProductMemory::where('product_id','=',$request['product_id'])->delete();

                if(!empty($request['memory_list'])){
                    $memory_array=explode('@m',$request['memory_list']);
                    foreach ($memory_array as $m){
                        if(!empty($m)){
                            $pm = new ProductMemory();
                            $pm->memory_id=$m;
                            $pm->product_id = $request['product_id'];
                            $pm->save();
                        }
                    }
                }


                return ['Ürün hafızaları güncellendi güncellendi', 'success', route('products.product-update',[$request['product_id'],2]), '', ''];


            });
            return json_encode($resultArray);

        }


    }

    public function addVariant($group_id,$product_id){

        return view('admin.products.variantadd',['product_id'=>$product_id,'group'=>VariantGroup::find($group_id)
            ,'count'=>(ProductVariant::where('group_id','=',$group_id)->count())+2]);
    }

    public function addVariantPost(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                    $variant = new ProductVariant();
                    $variant->group_id=$request['group_id'];
                    $variant->variant_name=$request['variant_name'];
                    $variant->binary=(!empty($request['binary'])) ? 1:0;
                    $variant->order=$request['order'];
                    $variant->save();

                    if(!empty($request['binary'])){
                        $vv = new VariantValue();
                        $vv->value = 'VAR';
                        $vv->variant_id=$variant['id'];
                        $vv->save();

                        $vv = new VariantValue();
                        $vv->value = 'YOK';
                        $vv->variant_id=$variant['id'];
                        $vv->save();

                    }



                ProductVariant::where('group_id','=',$request['group_id'])
                    ->where('order','>=',$request['order'])
                    ->where('id','<>',$variant['id'])
                    ->increment('order');




                return ['yeni varyant eklendi', 'success', route('products.product-update',[$request['product_id'],2]), '', ''];


            });
            return json_encode($resultArray);

        }


    }


    public function addVariantValue($variant_id,$product_id){

        return view('admin.products.variantvalueadd',['product_id'=>$product_id,'variant'=>ProductVariant::find($variant_id)
            ,'count'=>(VariantValue::where('variant_id','=',$variant_id)->count())+2]);
    }

    public function addVariantValuePost(Request $request)
    {



        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $value = new VariantValue();
                $value->variant_id=$request['variant_id'];
                $value->value=$request['value'];
                $value->order=$request['order'];
                $value->save();

                VariantValue::where('variant_id','=',$request['variant_id'])
                    ->where('order','>=',$request['order'])
                    ->where('id','<>',$value['id'])
                    ->increment('order');




                return ['yeni varyant değeri eklendi', 'success', route('products.product-update',[$request['product_id'],2]), '', ''];


            });
            return json_encode($resultArray);

        }


    }


    public function productVariantValuePost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


                    ProductVariantValue::where('product_id','=',$request['product_id'])->delete();
                    $variants = ProductVariant::all();
                    foreach ($variants as $variant){
                        $val = 'variant_value_'.$variant['id'];
                        if(!empty($request[$val])){
                            $pvv = new ProductVariantValue();
                            $pvv->product_id=$request['product_id'];
                            $pvv->variant_value_id= $request[$val];
                            if($variant['binary']==1){
                                if(!empty($request[$val])){
                                   $pvv->save();
                                }
                            }else{
                                $pvv->save();
                            }

                        }
                    }



                return ['Ürün özellikleri Güncellendi', 'success', route('products.product-update',[$request['product_id'],2]), '', ''];


            });
            return json_encode($resultArray);

        }
    }

    public function productStockMovement(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $stock = new ProductStockMovement();
                $stock->product_id = $request['product_id'];
                    $stock->color_id = $request['color_id'];
                    $stock->memory_id = $request['memory_id'];
                    $stock->quantity = $request['quantity'];
                    $stock->status = $request['status'];
                    $stock->save();
                return ['Stok hareketi eklendi', 'success', route('products.product-update',[$request['product_id'],3]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function productStockMovementUpdate(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $stock =  ProductStockMovement::find($request['stock_id']);
            //    $stock->product_id = $request['product_id'];
                    $stock->color_id = $request['color_id_update'];
                    $stock->memory_id = $request['memory_id_update'];
                    $stock->quantity = $request['quantity_update'];
                    $stock->status = $request['status_update'];
                    $stock->save();
                return ['Stok hareketi güncellendi', 'success', route('products.product-update',[$request['product_id'],3]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function productStockCheck($product_id,$color_id,$memory_id,$qty,$stock_id=0)
    {
 //           $sum = ProductStockMovement::where('product_id','=',$product_id)->where('color_id','=',$color_id)->where('memory_id','=',$memory_id)->where('status','=','in')
//                ->sum('quantity')->get();

        $in = DB::table('product_stock_movements')->where('product_id','=',$product_id)
            ->where('color_id','=',$color_id)
            ->where('memory_id','=',$memory_id)
            ->where('id','<>',$stock_id)
            ->where('status','=','in')
                ->sum('quantity');

        $out = DB::table('product_stock_movements')->where('product_id','=',$product_id)
            ->where('color_id','=',$color_id)
            ->where('memory_id','=',$memory_id)
            ->where('status','=','out')
            ->where('id','<>',$stock_id)
            ->sum('quantity');



        $sum = $in-$out;
         $sum = ($sum<0)?0:$sum;
        if($qty>$sum){
            return "Bu ürünün stoktaki miktarı ".$sum." adettir";
        }else{
            return "ok";
        }


    }

    public function updateStock($movement_id){
        $movement =ProductStockMovement::find($movement_id);
        $p_colors=ProductColor::where('product_id','=',$movement['product_id'])->pluck('color_id')->toArray();
        $p_memories=ProductMemory::where('product_id','=',$movement['product_id'])->pluck('memory_id')->toArray();

        return view('admin.products.update_stock',[
            'movement'=>$movement
            ,'stock_colors'=>Color::whereIn('id',$p_colors)->get()
            ,'stock_memories'=>Memory::whereIn('id',$p_memories)->orderBy('memory_value')->get()]);
    }


}
