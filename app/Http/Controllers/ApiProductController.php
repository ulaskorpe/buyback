<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Color;
use App\Models\ColorModel;
use App\Models\Customer;
use App\Models\CustomerOffer;
use App\Models\CustomerProductVote;
use App\Models\ImeiQuery;
use App\Models\Memory;
use App\Models\ModelAnswer;
use App\Models\ModelQuestion;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductLocation;
use App\Models\ProductMemory;
use App\Models\ProductModel;
use App\Models\ProductVariantValue;
use App\Models\VariantGroup;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\DB;

class ApiProductController extends Controller
{
     use ApiTrait;

    public function newProducts_x(Request $request){

        $products = new Object_();
        $products->title ='Son Eklenenler';

        $faker = Factory::create();
        $headers = [
            ['id'=>1 ,'title'=>'Telefonlar','tabUrl'=>'#phones'],
            ['id'=>2 ,'title'=>'Tabletler','tabUrl'=>'#tablets'],
            ['id'=>3 ,'title'=>'Aksesuarlar','tabUrl'=>'#accessories'],


        ];
        $tabs = array();
        $i=0;
        $k = rand(1000,20000);
        foreach ($headers as $header){


            $tabs[$i]['id'] = $i+1;
            $tabs[$i]['tabId']=str_replace('#','',$header['tabUrl']);
            $products_array = array();
            for($j=0;$j<rand(5,15);$j++){


                $price = rand(2, 10) * 100;
                $dis = rand(1, 3) * 30;
                $products_array[$j]['id'] = $k;
                $products_array[$j]['title'] = $faker->sentence;
                $products_array[$j]['listPrice'] = $price.".00";
                $products_array[$j]['price'] = ($price + $dis).".00";

                $products_array[$j]['imageUrl'] = url('images/products/' . rand(1, 16) . ".jpg");
                $products_array[$j]['discount'] = $dis.".00";

                $k++;
            }

            $tabs[$i]['products']=$products_array;
            $i++;
        }


        $products->headers = $headers;
        $products->tabs =$tabs;
        return    response()->json(  $products );
        //return ['products'=>$products];//response()->json(['products' => $products ]);
    }

    public function middleProducts(Request $request){
        /**
        products: {
        headers: [
        { id: 1, title: "Haftanın Fırsatları", tabUrl: "#weeklydeals" },
        { id: 2, title: "Çok Satanlar", tabUrl: "#topsales" },
        { id: 3, title: "Yeni Ürünler", tabUrl: "#newproducts" },
        { id: 4, title: "En Yüksek Puanlı", tabUrl: "#highrated" }
        ],
        tabs: [
        {
        id: 1, tabId: "weeklydeals", products: [
        { id: 21, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 22, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 23, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 24, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 25, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 26, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 27, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 28, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 29, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 30, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 31, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 22, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        { id: 33, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
        ]
        }
        ]
        }
        */
        $products = new Object_();


        $faker = Factory::create();
        $headers = [
            ['id'=>1 ,'title'=>'Haftanın Fırsatları','tabUrl'=>'#weeklydeals'],
            ['id'=>2 ,'title'=>'Çok Satanlar','tabUrl'=>'#topsales'],
            ['id'=>3 ,'title'=>'Yeni Ürünler','tabUrl'=>'#newproducts'],
            ['id'=>4 ,'title'=>'En Yüksek Puanlı','tabUrl'=>'#highrated']
        ];
        $tabs = array();
        $i=0;
        $k=rand(1000,8000);
        foreach ($headers as $header){


            $tabs[$i]['id'] = $i+1;
            $tabs[$i]['tabId']=str_replace('#','',$header['tabUrl']);
            $products_array = array();
            for($j=0;$j<rand(5,15);$j++){


                $price = rand(2, 10) * 100;
                $dis = rand(1, 3) * 30;
                $products_array[$j]['id'] = $k;
                $products_array[$j]['title'] = $faker->sentence;
                $products_array[$j]['listPrice'] = $price.".00";
                $products_array[$j]['price'] = ($price + $dis).".00";

                $products_array[$j]['imageUrl'] = url('images/products/' . rand(1, 16) . ".jpg");
                $products_array[$j]['discount'] = $dis.".00";
                $k++;
            }

            $tabs[$i]['products']=$products_array;
            $i++;
        }


        $products->headers = $headers;
        $products->tabs =$tabs;
        return    response()->json(  $products );
        //return ['products'=>$products];//response()->json(['products' => $products ]);
    }

    public function allProducts(Request $request)
    {

        if ($request->header('x-api-key') == $this->generateKey()) {
            $array = [];
            $min_price = (!empty($request['min_price']))?$request['min_price']:0;
            $max_price = (!empty($request['max_price']))?$request['max_price']:1000000;
            $order = (!empty($request['order']))?$request['order']:'created_at';
            $desc = (!empty($request['desc']))?$request['desc']:'DESC';
            if(!empty($request['brands'])){
                $brands = explode(",",trim($request['brands']));
            }else{
                $brands = ProductBrand::pluck('id')->toArray();

            }
            $page = (!empty($request['page']))?($request['page']-1):0;
            $page = ($page<0)?0:$page;
            $page_count = (!empty($request['page_count']))?$request['page_count']:20;

            if(!empty($request['colors'])){
                $colors = explode(",",trim($request['colors']));
            }else{
                $colors = Color::pluck('id')->toArray();
            }

            if(!empty($request['memories'])){
                $memories = explode(",",trim($request['memories']));
            }else{
                $memories = Memory::pluck('id')->toArray();

            }
            $a1 = ProductColor::whereIn('color_id',$colors)->pluck('product_id')->toArray();
            $a2 =  ProductMemory::whereIn('memory_id',$memories)->pluck('product_id')->toArray();
            //return $memories;
            // $products=Product::with('colors')->orderBy('id')->limit(30)->get();
            //return $products;

            //  return ProductColor::whereIn('color_id',$colors)->where('product_id','=',4)->count();

            $products = Product::with('brand','firstImage','colors','memories')->whereIn('brand_id',$brands)
                ->where('price','>=',$min_price)
                ->whereIn('id',array_intersect($a1,$a2))
                ->where('price','<=',$max_price)
                ->skip($page*$page_count)
                ->limit($page_count)
                ->orderBy($order,$desc) ;
            $product_count = Product::whereIn('brand_id',$brands)
                ->where('price','>=',$min_price)
                ->whereIn('id',array_intersect($a1,$a2))
                ->where('price','<=',$max_price)->count();


                //$products->count();
            //   return $product_count;

            $products = $products->get();



            //$faker = Factory::create();
            $i=0;
            $array=array();





            foreach ($products as $product){
                $show = true;
                /*    if(!empty($request['colors'])) {
                        $show = false;
                        $color_array = ProductColor::where('product_id', '=', $product['id'])->pluck('color_id')->toArray();
                        foreach ($color_array as $item){
                            if(in_array($item,$colors)){
                                $show = true;
                                break;
                            }
                        }
                    }///colors

                    if(!empty($request['memories'])) {
                        $show = false;
                        $memory_array = ProductMemory::where('product_id', '=', $product['id'])->pluck('')->toArray();
                        foreach ($memory_array as $item){
                            if(in_array($item,$memories)){
                                $show = true;
                                break;
                            }
                        }
                    }///*/

                if($show){
                    $details  =array();
                    $variants = ProductVariantValue::with( 'value.variant')->where('product_id','=',$product['id'])->get();
                    $j=0;
                    foreach ($variants as $variant){

                        $details[$j]=$variant->value()->first()->variant()->first()->variant_name.":".$variant->value()->first()->value;
                        $j++;
                    }


                    $array[$i]['id'] = $product['id'];
                    //      $array[$i]['filterData'] = array(['filterType'=>'brand',"value"=> $product->brand()->first()->BrandName],['filterType'=>'color','value'=>'red']);
                    $array[$i]['title'] = $product['title'];
                    $array[$i]['listPrice'] = $product['price'];
                    $array[$i]['price'] = $product['price_ex'];
                    $array[$i]['brand'] = $product->brand()->first()->BrandName;
                    $array[$i]['model'] = $product->model()->first()->Modelname;

                    //$array[$i]['url'] = '/urun-detay/'.$product['category_id'].'/'.str_replace(" ","-",$product['title'])."/".$product['id'];
                    $array[$i]['url'] = '/urun-detay/'.GeneralHelper::fixName($product['title'])."/".$product['id'];
                    $array[$i]['imageUrl'] = url($product->firstImage()->first()->image);
                    $array[$i]['thumb'] = url($product->firstImage()->first()->thumb);
                    $array[$i]['discount'] = $product['price']-$product['price_ex'];
                    $array[$i]['details'] =  $details;
                    $i++;
                }///show ??
            }

            $count = (count($array)>0)?true:false;
            $resultArray['status'] = ($count)?true:false;
            $resultArray['data'] = $array;//['products'=>$array];
            $resultArray['errors'] = ['msg'=>($count)?'':'Not Found'];
            $resultArray['item_count'] =$product_count;

            $status_code  = ($count)?200:404;

            return response()->json($resultArray,$status_code);
            // return $array;
        }
    }


    private function getProductDetail($product_id){
        $product = Product::with('firstImage','brand','model')->find($product_id);
        $result=array();
        $result['id']=$product['id'];
        $result['title']=$product->brand()->first()->BrandName." ".$product->model()->first()->Modelname;
        $result['listPrice']=$product['price'];
        $result['price']=$product['price_ex'];
        $result['url']='/urun-detay/'.$product['id'];
        $result['imageUrl']=$product->firstImage()->first()->image;
        $result['discount']=$product['price']-$product['price_ex'];
        return $result;
    }

    public function bestSellers(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {

            $product_id_array = ProductLocation::where('location_id','=',3)->orderBy('order')->pluck('product_id')->toArray();

            // $products = Product::select('id')->whereIn('id',ProductLocation::where('location_id','=',3)->orderBy('order')->pluck('product_id')->toArray())->get();
///   { id: 21, title: "Bluetooth on-ear PureBass Headphones", listPrice: "300.00", price: "500.00", url: "", imageUrl: "assets/images/products/7.jpg", discount: "150" },
            $products = array();
            $i=0;
            foreach ($product_id_array as $p_id){

                $products[$i]=$this->getProductDetail($p_id);
                $i++;
            }
            $returnArray['status'] = true;
            $status_code=200;
            $returnArray['data'] =$products;

        } else {
            $returnArray['status'] = false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'] ;
        }

        return response()->json($returnArray,$status_code);
    }

    public function weeklyDeals(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {

          $product_id_array = ProductLocation::where('location_id','=',2)->orderBy('order')->pluck('product_id')->toArray();
            $products = array();
            $i=0;
            foreach ($product_id_array as $p_id){

                $products[$i]=$this->getProductDetail($p_id);
                $i++;
            }
            $returnArray['status'] = true;
            $status_code=200;
            $returnArray['data'] =$products;

        } else {
            $returnArray['status'] = false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'] ;
        }

        return response()->json($returnArray,$status_code);
    }

    public function newProducts(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {

          $product_id_array = ProductLocation::where('location_id','=',4)->orderBy('order')->pluck('product_id')->toArray();
            $products = array();
            $i=0;
            foreach ($product_id_array as $p_id){

                $products[$i]=$this->getProductDetail($p_id);
                $i++;
            }
            $returnArray['status'] = true;
            $status_code=200;
            $returnArray['data'] =$products;

        } else {
            $returnArray['status'] = false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'] ;
        }

        return response()->json($returnArray,$status_code);
    }

    public function highestRated(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {


          $product_id_array = Product::select('id','calculated_vote')->orderBy('calculated_vote','DESC')->limit((!empty($request['count'])?$request['count']:15))->get()->toArray();

            $products = array();
            $i=0;
            foreach ($product_id_array as $p_id){

                $products[$i]=$this->getProductDetail($p_id['id']);

                $products[$i]=array_merge($products[$i],['rate'=> $p_id['calculated_vote']]);
                $i++;
            }
            $returnArray['status'] = true;
            $status_code=200;
            $returnArray['data'] =$products;

        } else {
            $returnArray['status'] = false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'] ;
        }

        return response()->json($returnArray,$status_code);
    }



    public function productDetail(Request $request,$product_id){
//return response()->json('ok',200);

        if ($request->header('x-api-key') == $this->generateKey()) {

            $product = Product::with('brand','model','firstImage','category','images')->where('id','=',$product_id)->first();

            if(!empty($product['id'])) {

                    $faker= Factory::create();
                $details  =array();
                $j=0;


if(true){ ///real details
                $variants = ProductVariantValue::with( 'value.variant')->where('product_id','=',$product['id'])->get();


                foreach ($variants as $variant){

                    //$details[$j]=$variant->value()->first()->variant()->first()->variant_name.":".$variant->value()->first()->value;
                    $details[$j]=['name'=>$variant->value()->first()->variant()->first()->variant_name,'value'=>$variant->value()->first()->value];
                    $j++;
                }

}else{ ////random details



                $vg = VariantGroup::all();
                foreach ($vg as $g){
                    $values = array();
                    for($h=0;$h<rand(2,5);$h++){
                        $values[$h]['id']=$h+2;
                        $values[$h]['name']=$faker->word;
                        $values[$h]['value']=$faker->words(2,3);
                    }

                    $details[$j]['id']=$g['id'];
                    $details[$j]['title']=$g['group_name'];
                    $details[$j]['items']=$values;

                    $j++;

                }
}
                $imageGallery=array();
                $i=0;
                foreach ($product->images()->get() as $img){
                    $imageGallery[$i]['id']=$img['id'];
                    $imageGallery[$i]['imageUrl']=$img['thumb'];
                    $i++;
                }

                $content="";
                for($i=0;$i<5;$i++) {
                $content.="<p>".$faker->sentence."</p>";
                }
               $reviews=array();
                for($i=0;$i<5;$i++){
                  $reviews[$i] = ['name'=>$faker->name,'comment'=>$faker->sentence,'date'=>'22/02/2022','rating'=>rand(1,10)];

                }

                $together =  Product::with('firstImage','brand','model')->where('id','<>',$product['id'])->inRandomOrder()->limit(5)->get();
                $item_array=array();
                $i=0;
                foreach ($together as $t){
                    $item_array[$i] = ['id'=>$t['id'],'title'=>$t->brand()->first()->BrandName." ".$t->model()->first()->Modelname
                        ,'imageUrl'=>$t->firstImage()->first()->thumb,'listPrice'=>$t['price'],'price'=>$t['price_ex']];
                    $i++;
                }

                $tabs=array();
                $tabs[0] = ['id'=>2,'title'=>'Ürün Bilgisi','name'=>'tab-description','type'=>'html','content'=>$content];
                $tabs[1] = ['id'=>3,'title'=>'Teknik Özellikler','name'=>'tab-specification','type'=>'technique','content'=>$details];
                $tabs[2] = ['id'=>4,'title'=>'Yorumlar','name'=>'tab-reviews','type'=>'review' ,'content'=>$reviews];
                $tabs[3] = ['id'=>5,'title'=>'Birlikte Al','type'=>'accessory','name'=>'tab-accessories' ,'content'=>$item_array];

                $array['id'] = $product['id'];
                $array['title'] = $product['title'];

                $array['brand']['id'] = $product->brand()->first()->id ;
                $array['brand']['title'] = $product->brand()->first()->BrandName ;
                $array['brand']['imageUrl'] = $product->brand()->first()->ImageLarge ;
                $array['category']['id']=$product->category()->first()->id;
                $array['category']['title']=$product->category()->first()->category_name;

                $array['features']=$product['description'];
                $array['listPrice'] = $product['price'];
                $array['price'] = $product['price_ex'];
                $array['discount'] = $product['price']-$product['price_ex'];
                $array['stockCode'] ='HKS'.rand(100000,999999);
                $array['rating'] =1;
                $array['imageGallery'] =$imageGallery;
                $array['colors']=Color::select('id','color_name')->inRandomOrder()->limit(rand(2,4))->get()->toArray();
                $array['memories']=Memory::select('id','memory_value')->inRandomOrder()->limit(rand(2,4))->get()->toArray();
                $array['tabs'] =$tabs;

                $array['crumbs'] =[
                    ['url'=>'/cat/phones','title'=>'Telefonlar'],
                    ['url'=>'#','title'=>$product->brand()->first()->BrandName." ".$product->model()->first()->Modelname],

                ];

/*

                $array['model'] = $product->model()->first()->Modelname;
                $array['url'] = '/urun-detay/'.str_replace(" ","-",$product['title'])."/".$product['id'];
                $array['imageUrl'] = url($product->firstImage()->first()->thumb);

                $array['details'] =  $details;
*/
                $returnArray['status'] = true;
                $status_code=200;
                $returnArray['data'] =$array;//['product'=>$array] ;
                $returnArray['errors'] =['msg'=>''] ;
            } else {
                $returnArray['status'] = false;
                $status_code=404;
                $returnArray['errors'] =['msg'=>'not found'] ;
            }


        } else {
            $returnArray['status'] = false;

            $status_code=498;

            $returnArray['errors'] =['msg'=>'invalid key'] ;
        }

        return response()->json($returnArray,$status_code);
    }

    public function allProducts_ex(Request $request)
    {
        /***
         * const data = [
        { id: 1, filterData: [{ filterType: 'brand', value: 'samsung' }, { filterType: 'color', value: 'black' }],
         title: "Samsung Galaxy M52 5G 128 GB (Samsung Türkiye Garantili) ",
         * listPrice: "5799.00", price: "5299.00",
         * url: "/urun-detay/samsung-m2-1", imageUrl: "/assets/images/products/L1.jpg",
         * discount: "300.000",
         * details: ['128 GB Depolama', '8 GB RAM', '6.7" Retina Ekran', '5000mAh'] },
        { id: 2, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'red' }], title: "iPhone 11 64 GB", listPrice: "10.525,00", price: "9837,77", url: "/urun-detay/iphone-11-64-gb-2", imageUrl: "/assets/images/products/L2.jpg", discount: "150", details: ['64 GB Depolama', '4 GB RAM', '6.1 Ekran Boyutu" pil', '12 MP Ön Kamera'] },
        { id: 3, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'teal' }], title: "iPhone 12 Mini 64 GB", listPrice: "13.300,00", price: "12.480,00", url: "/urun-detay/iphone-12-mini-64-gb-3", imageUrl: "/assets/images/products/L3.jpg", discount: "150", details: ['64 GB Depolama', '4 GB RAM', '5.4 Ekran Boyutu" ', '12 MP Ön Kamera'] },
        { id: 4, filterData: [{ filterType: 'brand', value: 'oppo' }, { filterType: 'color', value: 'green' }], title: "Oppo Reno 5 Lite 128 GB (Oppo Türkiye Garantili)", listPrice: "4.699,00", price: "4.523,30", url: "/urun-detay/oppo-reno-5-1", imageUrl: "/assets/images/products/L4.jpg", discount: "150", details: ['128 GB Depolama', '8 GB RAM', '6.4" Ekran Boyutu', '32 MP Ön Kamera'] },
        { id: 5, filterData: [{ filterType: 'brand', value: 'poco' }, { filterType: 'color', value: 'black' }], title: "Poco X3 Pro 8 GB Ram 256 GB (Poco Türkiye Garantili) ", listPrice: "6.499,00", price: "5.719,00", url: "/urun-detay/poco-x3-pro-8-ram-5", imageUrl: "/assets/images/products/L5.jpg", discount: "150", details: ['256 GB Depolama', '8 GB RAM', '6.67" Ekran Boyutu', '20MP Ön Kamera'] },
        { id: 6, filterData: [{ filterType: 'brand', value: 'samsung' }, { filterType: 'color', value: 'black' }], title: "Samsung Galaxy M12 128 GB (Samsung Türkiye Garantili)", price: "2.999.00", url: "/urun-detay/samsung-galaxy-m12-6", imageUrl: "/assets/images/products/L6.jpg", details: ['128 GB Depolama', '4 GB RAM', '6.5" Ekran Boyutu', '8MP Ön Kamera'] },
        { id: 7, filterData: [{ filterType: 'brand', value: 'honor' }, { filterType: 'color', value: 'green' }], title: "Honor 50 128 GB 8 GB Ram 5G (Honor Türkiye Garantili)", price: "9.999,00", url: "/urun-detay/honor-50-128GB-7", imageUrl: "/assets/images/products/L7.jpg", details: ['128 GB Dahili Hafıza', '8 GB RAM', '4300mAh', '32MP Ön Kamera'] },
        { id: 8, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'black' }], title: "iPhone SE 64 GB", listPrice: "6.985,00", price: "6.705,60", url: "/urun-detay/iphone-se-64-gb-8", imageUrl: "/assets/images/products/L8.jpg", discount: "150", details: ['256 GB Depolama', '8 GB RAM', '6.67" Ekran Boyutu', '20MP Ön Kamera'] },
        { id: 9, filterData: [{ filterType: 'brand', value: 'xiaomi' }, { filterType: 'color', value: 'blue' }], title: "Xiaomi Redmi 9c 64 GB (Xiaomi Türkiye Garantili) ", listPrice: "3.099,00", price: "2.578,75", url: "/urun-detay/xiaomi-red-mi-9c-64-gb-9", imageUrl: "/assets/images/products/L9.jpg", discount: "150", details: ['256 GB Depolama', '8 GB RAM', '6.67" Ekran Boyutu', '20MP Ön Kamera'] },
        { id: 10, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'spacegray' }], title: "iPhone 13 Pro 128 GB", price: "21.499.00", url: "/urun-detay/iphone-13-pro-128-10", imageUrl: "/assets/images/products/L10.jpg", details: ['128 GB Depolama', '4 GB RAM', '6.5" Ekran Boyutu', '8MP Ön Kamera'] },
        { id: 11, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'spacegray' }], title: "iPhone 13 Pro 128 GB", price: "21.499.00", url: "/urun-detay/iphone-13-pro-128-10", imageUrl: "/assets/images/products/L10.jpg", details: ['128 GB Depolama', '4 GB RAM', '6.5" Ekran Boyutu', '8MP Ön Kamera'] },
        { id: 12, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'spacegray' }], title: "iPhone 13 Pro 128 GB", price: "21.499.00", url: "/urun-detay/iphone-13-pro-128-10", imageUrl: "/assets/images/products/L10.jpg", details: ['128 GB Depolama', '4 GB RAM', '6.5" Ekran Boyutu', '8MP Ön Kamera'] },
        { id: 13, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'spacegray' }], title: "iPhone 13 Pro 128 GB", price: "21.499.00", url: "/urun-detay/iphone-13-pro-128-10", imageUrl: "/assets/images/products/L10.jpg", details: ['128 GB Depolama', '4 GB RAM', '6.5" Ekran Boyutu', '8MP Ön Kamera'] },
        { id: 14, filterData: [{ filterType: 'brand', value: 'apple' }, { filterType: 'color', value: 'spacegray' }], title: "iPhone 13 Pro 128 GB", price: "21.499.00", url: "/urun-detay/iphone-13-pro-128-10", imageUrl: "/assets/images/products/L10.jpg", details: ['128 GB Depolama', '4 GB RAM', '6.5" Ekran Boyutu', '8MP Ön Kamera'] }

        ]
         */

        if ($request->header('x-api-key') == $this->generateKey()) {

            $faker = Factory::create();
            $array = [];
            $brands = ProductBrand::select('BrandName')->pluck('BrandName')->toArray();
            $colors = Color::select('color_name')->pluck('color_name')->toArray();

            for ($i = 0; $i < 13; $i++) {
                $details  =array();
                for($j=0;$j<rand(2,8);$j++){
                    $details[$j] = $faker->word();
                }
                $price = rand(2, 10) * 100;
                $title =str_replace(".","",$faker->sentence);
                $dis = rand(1, 3) * 30;
                $array[$i]['id'] = $i + 1;
                $array[$i]['filterData'] = array(['filterType'=>'brand',"value"=> $brands[rand(0,32)] ],['filterType'=>'color','value'=>$colors[rand(0,4)]]);
                $array[$i]['title'] = $title;
                $array[$i]['listPrice'] = $price;
                $array[$i]['price'] = $price + $dis;
                $array[$i]['url'] = '/product-detail/'.str_replace(" ","-",$title)."/".$i + 1;
                $array[$i]['imageUrl'] = url('images/products/' . rand(1, 16) . ".jpg");
                $array[$i]['discount'] = $dis;
                $array[$i]['details'] =  $details;

            }

            return $array;

        }

    }

    public function productFilters(Request $request)
    {
        /***
        {
        id: 1, title: "Markalar", filterName: 'brand', items: [
        { id: 1, filterName: 'apple', title: "Apple", isChosen: false },
        { id: 2, filterName: 'samsung', title: "Samsung", isChosen: false },
        { id: 3, filterName: 'xiaomi', title: "Xiaomi", isChosen: false },
        { id: 4, filterName: 'oppo', title: "Oppo", isChosen: false },
        { id: 5, filterName: 'poco', title: "Poco", isChosen: false },
        { id: 6, filterName: 'honor', title: "Honor", isChosen: false },
        ]
        },
        {
        id: 2, title: "Renkler", filterName: 'color', items: [
        { id: 5, title: "Siyah", filterName: 'black', isChosen: false },
        { id: 6, title: "Mavi", filterName: 'blue', isChosen: false },
        { id: 7, title: "Kırmızı", filterName: 'red', isChosen: false },
        { id: 8, title: "Yeşil", filterName: 'green', isChosen: false },
        { id: 9, title: "Uzay Grisi", filterName: 'spacegray', isChosen: false },
        { id: 9, title: "Gece Mavisi", filterName: 'teal', isChosen: false },
        ]
        }
         */

        if ($request->header('x-api-key') == $this->generateKey()) {


            $array = [];
            $brands = ProductBrand::where('status','=',1)->select('id','BrandName')->get();
            $items = array();
            $i=0;
            foreach ($brands as $brand){
                $items[$i] = ['id'=>$brand['id'],'filterName'=>strtolower($brand['BrandName']),'title'=>ucfirst($brand['BrandName']),'isChosen'=>false];
                $i++;
            }

            $array[0] =['id'=>1,'title'=>'Markalar','filterName'=>'brands','items'=>$items];

          $colors = Color::select('id','color_name','filter_name')->get();
            $items = array();
            $i=0;
            foreach ($colors as $color){
                $items[$i] = ['id'=>$color['id'],'filterName'=>strtolower($color['filter_name']),'title'=>ucfirst($color['color_name']),'isChosen'=>false];
                $i++;
            }

            $array[1] =['id'=>2,'title'=>'Renkler','filterName'=>'colors','items'=>$items];

            $memories = Memory::select('id','memory_value')->get();
            $items = array();
            $i=0;
            foreach ($memories as $memory){
                $items[$i] = ['id'=>$memory['id'],'filterName'=>$memory['memory_value'] ,'title'=>$memory['memory_value']."GB",'isChosen'=>false];
                $i++;
            }
            $array[2] =['id'=>3,'title'=>'Hafızalar','filterName'=>'memories','items'=>$items];
        //    return $array;

            $count = (count($array)>0)?true:false;
            $resultArray['status'] = ($count)?true:false;
            $resultArray['data'] = $array;//['products'=>$array];
            $resultArray['errors'] = ['msg'=>($count)?'':'Not Found'];
         //   $resultArray['item_count'] = ['msg'=>($count)?'':'Not Found'];

            $status_code  = ($count)?200:404;
            return response()->json($resultArray,$status_code);
        }

    }


    public function productSeeder(Request $request){

        for($i=1;$i<5;$i++){
            $products = Product::select('id')->inRandomOrder()->limit(13)->get();

            $j=1;
            foreach ($products as $product){
                $pl = new ProductLocation();
                $pl->location_id= $i;
                $pl->product_id = $product['id'];
                $pl->order= $j;
                $pl->save();
                $j++;
            }

            echo $i;

        }


die();
//        $customers = Customer::select('id')->get();
//
//        foreach ($customers as $customer){ }

     $products = Product::all();
      //  $products = Product::inRandomOrder()->limit(rand(10,20))->get();
        foreach ($products as $product){
         //   $colors = Color::inRandomOrder()->limit(rand(1,4))->get();
           //$vote = rand(1,10);
         //   echo $product['id'].":".$customer['id'].":".$vote." \n";

//            $cpv = new CustomerProductVote();
//            $cpv->product_id = $product['id'];
//            $cpv->customer_id = $customer['id'];
//            $cpv->vote = $vote ;
//            $cpv->save();


            $count = CustomerProductVote::where('product_id','=',$product['id'])->count();

            if($count>0){
                $sum = CustomerProductVote::where('product_id','=',$product['id'])->sum('vote');
           //echo $product['id'].":".$sum.":".$count.":".number_format(($sum/$count),2)."\n";
            $product->calculated_vote = number_format(($sum/$count),2);
            $product->save();
            }

//            $memories = Memory::inRandomOrder()->limit(rand(1,3))->get();
//
//            foreach ($memories as $memory){
//                $pm = new ProductMemory();
//                $pm->product_id = $product['id'];
//                $pm->memory_id= $memory['id'];
//               // $pm->save();
//            }
        }



        die();

        die();
        $path ='images/products/';
        $products = Product::all();
        foreach ($products as $product){
            $r = rand(1,10);
            $img = new ProductImage();
            $img->product_id = $product['id'];
            $img->thumb = $path."THL".$r.".jpg";
            $img->image = $path."L".$r.".jpg";
            $img->order =1;
            $img->first =1;
            $img->status =1;
      //      $img->save();

        }


        die();
        $path = "images/products";

        for($i=1;$i<11;$i++){

            $filename="L".$i.".jpg";
            $th = "THL".$i.".jpg";

            $file = $path."/".$filename;//$request->file('image');
            $img = Image::make($file);
         //   $img->save($path . '/' . $filename);
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path . '/' . $th);

        }


        die();
        $faker = Factory::create();
        $brands = ProductBrand::select('id')->pluck('id')->toArray();
        $micro_id= 100000;
        for($i=0;$i<300;$i++){
            $m_id =0;

            while($m_id==0){
            $b_id = $brands[rand(0,32)] ;

            $model = ProductModel::where('Brandid','=',$b_id)->inRandomOrder()->first();
            $m_id=(!empty($model['id']))?$model['id']:0;
            }
            //return $model;
            $price = rand(1,10)*500;
            $price_ex = $price - (rand(1,10)*20);
        $product= new Product();
        $product->title = $faker->sentence;
        $product->micro_id =$micro_id;
        $product->brand_id = $b_id;
        $product->model_id = $model['id'];

        $product->category_id = 2;
        $product->description =$faker->sentence(30,2);
        $product->price = $price;
        $product->price_ex =$price_ex;
echo $price.":".$price_ex.":".$b_id.":".$model['id']."\n";
        $product->status =1;
      $product->save();
            $micro_id++;
        }
        return "ok";
    }



    public function imeiQuery(Request $request){

        if ($request->header('x-api-key') == $this->generateKey()) {
            $url = "https://kayit.mcks.gov.tr/refurbished-devices/oauth/token";

            $username="garantili";
            $password="4r3e2w1q?";
            $b_username="ede5e6da-fe83-11eb-af7c-3b68da59d087";
            $b_password="R3furbi53D12.";

            $payload = [
                'username'		=> $username,
                'password' 		=> $password,
                'grant_type'    => 'password'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERPWD, $b_username . ":" . $b_password);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            $output = curl_exec($ch);
            curl_close($ch);
            $arr_1 = json_decode($output, TRUE);
            $token=$arr_1['access_token'];

            if ($token && $request['imei_no']>0){
                $imei=$request['imei_no'];
                $url = "https://kayit.mcks.gov.tr/refurbished-devices/isSuitableForRefurbish/".$imei;
                $payload = [
                    'access_token'		=> $token
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
                $output = curl_exec($ch);

                curl_close($ch);

                $arr = json_decode($output, TRUE);
                $imei_btk=$arr['imei'];
                $responseCode=$arr['responseCode'];
                $responseMessage=$arr['responseMessage'];

                $imei_query= new ImeiQuery();
                $imei_query->imei = $request['imei_no'];
                $imei_query->user_id = (!empty(Session::get('admin_id')))?Session::get('admin_id'):0;
                $imei_query->model_id =(!empty($request['model_id']))?$request['model_id']:0;
                $imei_query->result =$responseCode;
                $imei_query->token = $arr_1['access_token'];
                $imei_query->token_type = $arr_1['token_type'];
                $imei_query->scope = $arr_1['scope'];
                $imei_query->ip_address = $_SERVER['REMOTE_ADDR'];
                $imei_query->save();


                //0   return ($responseCode==1)?"ok":"no";

                $status_code=200;
                $returnArray['status']=true;
                if($responseCode==1){
                   // $resultArray=['ok', $imei_query['id']];
                    $returnArray['data'] =['result'=>true,'msg'=>'geçerli IMEI'];
                }else{
                   // $resultArray=['none', $imei_query['id']];
                    $returnArray['data'] =['result'=>false,'msg'=>'geçersiz IMEI'];
                }
             //   return json_encode($returnArray,$status_code);

            }else{
                $returnArray['status']=false;
                $status_code=401;
                $returnArray['errors'] =['msg'=>'unauthorized'];
            }
        }else{
            $returnArray['status']=false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'];

        }
         return response()->json($returnArray,$status_code);

        }

    public function getBrands(Request $request){

        if ($request->header('x-api-key') == $this->generateKey()) {

                $status_code=200;
                $returnArray['status']=true;
               $returnArray['data'] =ProductBrand::select('id','BrandName')->where('status','=',1)->orderBy('BrandName')->get();


        }else{
            $returnArray['status']=false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'];

        }
         return response()->json($returnArray,$status_code);

        }

    public function getModels(Request $request,$brand_id=0){

        if ($request->header('x-api-key') == $this->generateKey()) {

            $models = ProductModel::select('id','Modelname')->where('Brandid','=',$brand_id)->where('status','=',1)->orderBy('Modelname')->get();
                $status_code=200;
                $returnArray['status']=true;
               $returnArray['data'] = $models;


        }else{
            $returnArray['status']=false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'];

        }
         return response()->json($returnArray,$status_code);

        }

    public function getQuestions(Request $request,$model_id=0){

        if ($request->header('x-api-key') == $this->generateKey()) {

            $model = ProductModel::select('id')->where('status','=',1)->find($model_id);
            if(empty($model['id'])){

                $status_code=404;
                $returnArray['status']=false;
                $returnArray['errors'] = ['msg'=>'Model bulunamadı'];

            }else{

                $questions=ModelQuestion::with('question.answers')->where('model_id','=',$model_id)->orderBy('count')->get();
                $array = array();
                $i=0;
                foreach ($questions as $question){
                    $array[$i]['question_id']=$question->question()->first()->id;
                    $array[$i]['question']=$question->question()->first()->question;
                    $answers = array();
                    $j=0;
                    foreach ($question->question()->first()->answers()->get() as $answer){
                        $answers[$j]['answer_id']=$answer['id'];
                        $answers[$j]['answer']=$answer['answer'];
                        $answers[$j]['key']=$answer['key'];
                        $j++;
                    }
                    $array[$i]['answers']=$answers;
                    $i++;
                }
                $status_code=200;
                $returnArray['status']=true;
                $returnArray['data'] = $array;

            }



        }else{
            $returnArray['status']=false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'];

        }
         return response()->json($returnArray,$status_code);

        }


        private function checkImei($imei){

                    ////////checkimei
            return true;
        }

    public function calculateAnswers(Request  $request){

        if ($request->header('x-api-key') == $this->generateKey()) {

            if(!empty($request['imei_no']) && $this->checkImei($request['imei_no'])){

            $model = ProductModel::select('id','min_price','max_price')->where('status','=',1)->find($request['model_id']);
            $customer = Customer::with('first_address')->select('id')
                ->where('status','=',1)->where('customer_id','=',$request['customer_id'])->first();

            if(empty($model['id']) || empty($customer['id'])){

                $status_code=404;
                $returnArray['status']=false;
                $returnArray['errors'] = ['msg'=>'Model/Müşteri bulunamadı'];

            }else{
                $answer_array = explode(',',trim($request['answers']));
                $questions=ModelQuestion::with('question.answers')->where('model_id','=',$model['id'])->orderBy('count')->get();
                //if($answer_array->count())

                $answered_question_count=0;
                foreach ($questions as $q){
                    $is_answered = false;
                    foreach ($q->question()->first()->answers()->get() as $answer){
                        $is_answered = (in_array($answer['id'],$answer_array)) ? true : false ;
                        if($is_answered){
                            $answered_question_count++;
                   //    echo $q->question()->first()->id." -". $answer['id'].":";

                        break;
                        }
                    }

                    }



               // if($questions->count()==count($answer_array) ){
                if($questions->count()==$answered_question_count ){
                    $discount = ModelAnswer::where('model_id','=',$model['id'])
                        ->whereIn('answer_id',$answer_array)
                        ->sum('value');
                    $price_offer = (($model['max_price']-$discount) > $model['min_price']) ? ($model['max_price']-$discount) : $model['min_price'];
                    $customer_offer= CustomerOffer::where('imei_no','=',$request['imei_no'])->where('customer_id','=',$customer['id'])->first();
//                    return  $customer_offer;
                    if(empty($customer_offer['id'])){
                        $customer_offer= new CustomerOffer();
                        $customer_offer->customer_id= $customer['id'];
                        $customer_offer->imei_no=$request['imei_no'];
                    }
                    $customer_offer->model_id=$request['model_id'];
                    $customer_offer->answers = $request['answers'];
                    $customer_offer->discount=$discount;
                    $customer_offer->price_offer=$price_offer;
                    $customer_offer->date=date('Y-m-d');
                    $customer_offer->save();





                    $status_code=200;
                    $returnArray['status']=true;
                    $returnArray['data'] =['model'=>$model,'discount'=>$discount,'price_offer'=>$price_offer];
                }else{
                    $status_code=411;
                    $returnArray['status']=true;
                    $returnArray['errors'] =['msg'=>'Tüm soruları yanıtlayınız'];
                }



            }//model found

            }else{ //// no imei / invalid imei

                $returnArray['status']=false;
                $status_code=406;
                $returnArray['errors'] =['msg'=>'invalid IMEI'];

            }

        }else{
            $returnArray['status']=false;
            $status_code=498;
            $returnArray['errors'] =['msg'=>'invalid key'];

        }
        return response()->json($returnArray,$status_code);
    }



}
