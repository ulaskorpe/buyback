<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Color;
use App\Models\Memory;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductMemory;
use App\Models\ProductModel;
use App\Models\ProductVariantValue;
use Faker\Factory;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use phpDocumentor\Reflection\Types\Object_;

class ApiProductController extends Controller
{
     use ApiTrait;

    public function newArrivals(Request $request){

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
            $page = (!empty($request['page']))?$request['page']:0;
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

            $products = Product::with('brand','firstImage')->whereIn('brand_id',$brands)
                ->where('price','>=',$min_price)
                ->where('price','<=',$max_price)
                ->skip($page*$page_count)
                ->limit($page_count)
                ->orderBy($order,$desc)
                ->get();



            $faker = Factory::create();
            $i=0;
            $array=array();
            foreach ($products as $product){
                $details  =array();
               $variants = ProductVariantValue::with( 'value.variant')->where('product_id','=',$product['id'])->get();
               $j=0;
               foreach ($variants as $variant){

                    $details[$j]=$variant->value()->first()->variant()->first()->variant_name.":".$variant->value()->first()->value;
                    $j++;
               }


                $array[$i]['id'] = $product['id'];
                $array[$i]['filterData'] = array(['filterType'=>'brand',"value"=> $product->brand()->first()->BrandName],['filterType'=>'color','value'=>'red']);
                $array[$i]['title'] = $product['title'];
                $array[$i]['listPrice'] = $product['price'];
                $array[$i]['price'] = $product['price_ex'];
                $array[$i]['url'] = '/urun-detay/'.str_replace(" ","-",$product['title'])."/".$product['id'];
                $array[$i]['imageUrl'] = url($product->firstImage()->first()->thumb);
                $array[$i]['discount'] = $product['price']-$product['price_ex'];
                $array[$i]['details'] =  $details;
                $i++;
            }

            return $array;
        }
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

            $array[0] =['id'=>1,'title'=>'Markalar','filterName'=>'brand','items'=>$items];

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
            $array[2] =['id'=>3,'title'=>'Hafızalar','filterName'=>'memory','items'=>$items];
            return $array;

        }

    }


    public function productSeeder(Request $request){

        die();
        $products = Product::all();
        foreach ($products as $product){
         //   $colors = Color::inRandomOrder()->limit(rand(1,4))->get();

            $memories = Memory::inRandomOrder()->limit(rand(1,3))->get();

            foreach ($memories as $memory){
                $pm = new ProductMemory();
                $pm->product_id = $product['id'];
                $pm->memory_id= $memory['id'];
               // $pm->save();
            }
        }

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



}
