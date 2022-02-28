<?php

namespace App\Http\Controllers;

use App\Enums\MenuLocations;
use App\Models\Area;
use App\Models\Article;
use App\Models\Banner;
use App\Models\City;
use App\Models\District;
use App\Models\MenuItem;
use App\Models\MenuSubItem;
use App\Models\MenuSubItemLink;
use App\Models\Neighborhood;
use App\Models\ProductLocation;
use App\Models\ProductStockMovement;
use App\Models\SiteLocation;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\Town;
use Faker\Factory;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;

class ApiController extends Controller
{

    use ApiTrait;


    public function settingList(Request $request, $code = 0)
    {


        if ($request->header('x-api-key') == $this->generateKey()) {
            $status_code = 200;

            if ($code > 0) {
                $resultArray = SiteSetting::where('code', '=', $code)->first();
            } else {
                $resultArray = SiteSetting::all();
            }

        } else {
            $resultArray['status'] = false;
           // $resultArray['status_code'] = 406;
            $status_code=406;
            $resultArray['errors'] =['msg'=>'hatalı anahtar'] ;
        }


        return response()->json($resultArray,$status_code);
        // return json_encode($resultArray);
    }

    public function sliderList(Request $request)
    {
        /***
         *   sliders = [
         * {
         * id: 1,
         * title: "Bluetooth Kulaklıklarda 50%'ye varan indirimler kaçırma!",
         * subTitle: "Apple Airpods 2 kulaklık modellerinde büyük indirim fırsatı...",
         * buttonTitle: "Hemen Satın Al!",
         * bottomTitle: "50 TL üzerine kargo bedava!",
         * imageUrl: "assets/images/slider/home-v1-img-2.png",
         * backgroundImageUrl: "assets/images/slider/home-v1-background.png"
         * },
         * {
         * id: 2,
         * title: "iPad Mini tabletlerde efsane indirimleri kaçırma!",
         * subTitle: "iPad Mini tabletler 7.599 TL'den başlayan fiyatlarla...",
         * buttonTitle: "Hemen Satın Al!",
         * bottomTitle: "50 TL üzerine kargo bedava!",
         * imageUrl: "assets/images/slider/home-v1-img-2.png",
         * backgroundImageUrl: "assets/images/slider/home-v1-background.png"
         * }
         * ]
         *
         */

        if ($request->header('x-api-key') == $this->generateKey()) {
            //  if(true){
            $status_code = 200;
            $sliders = Slider::where('status', '=', 1)->orderBy('count')->get();
            $array = [];
            $i = 0;
            foreach ($sliders as $slider) {
                $array[$i]['id'] = $slider['id'];
                $array[$i]['title'] = $slider['title'];
                $array[$i]['subTitle'] = $slider['subtitle'];
                $array[$i]['buttonTitle'] = $slider['btn_1'];
                $array[$i]['bottomTitle'] = $slider['btn_2'];
                $array[$i]['imageUrl'] = url($slider['image']);
                $array[$i]['backgroundImageUrl'] = url($slider['bgimg']);
                $i++;
            }


            $resultArray = $array;
        } else {
            $resultArray['status'] = false;
         //   $resultArray['status_code'] = 406;
            $status_code = 406;
            $resultArray['msg'] = 'hatalı anahtar';
        }


        return response()->json($resultArray, $status_code);
        // return json_encode($resultArray);
    }

    public function brandsList(Request $request)
    {


    //    if ($request->header('x-api-key') == $this->generateKey()) {
            if(true){

            $banners = Banner::where('status', '=', 1)->orderBy('order')->get();
            $array = [];
            $i = 0;
            foreach ($banners as $banner) {
                $array[$i]['id'] = $banner['id'];
                $array[$i]['externalUrl'] = (!empty($banner['link'])) ? $banner['link'] : "#";
                $array[$i]['title'] = $banner['title'];
                $array[$i]['src'] = url($banner['image']);
                $i++;
            }


            $resultArray = $array;
        } else {
            $resultArray['status'] = false;
            $resultArray['status_code'] = 406;
            $resultArray['msg'] = 'hatalı anahtar';
        }


        return response()->json($resultArray);
        // return json_encode($resultArray);
    }

    public function menuList(Request $request, $location = "")
    {
        if ($request->header('x-api-key') == $this->generateKey()) {
            if (!empty($location)) {

                $menuLocations = MenuLocations::asArray();

                if (empty($menuLocations[$location])) {
                    $resultArray['status'] = false;
                    $resultArray['status_code'] = 404;
                    $resultArray['msg'] = 'not_found';
                } else {

                    $resultArray = [$location . "-menu" => MenuItem::with('sub_items')
                        ->where('location', '=', $menuLocations[$location])
                        ->where('status', '=', 1)->orderBy('order')->get()];
                }
            } else {
                $top = MenuItem::with('sub_items')
                    ->where('location', '=', 1)
                    ->where('status', '=', 1)->orderBy('order')->get();
                $header = MenuItem::with('sub_items')
                    ->where('location', '=', 2)
                    ->where('status', '=', 1)->orderBy('order')->get();

                $left = MenuItem::with('sub_items')
                    ->where('location', '=', 3)
                    ->where('status', '=', 1)->orderBy('order')->get();

                $resultArray = ['top' => $top, 'header' => $header, 'left' => $left];


            }


        } else {
            $resultArray['status'] = false;
            $resultArray['status_code'] = 406;
            $resultArray['msg'] = 'hatalı anahtar';
        }


        return response()->json($resultArray,200);
        // return json_encode($resultArray);
    }


    public function getArticle(Request $request, $code = "")
    {
        if ($request->header('x-api-key') == $this->generateKey()) {
            if (!empty($code)) {

                $resultArray = ["article" => Article::with('parts')->where('code', '=', $code)
                    ->where('status', '=', 1)->first()];

            } else {
                $resultArray = ["articles" => Article::with('parts')
                    ->where('status', '=', 1)->get()];

            }


        } else {
            $resultArray['status'] = false;
            $resultArray['status_code'] = 406;
            $resultArray['msg'] = 'hatalı anahtar';
        }


        return response()->json($resultArray);
        // return json_encode($resultArray);
    }

    public function getArea(Request $request, $code = "")
    {
        if ($request->header('x-api-key') == $this->generateKey()) {
            if (!empty($code)) {
                $resultArray = ["area" => Area::where('code', '=', $code)
                    ->where('status', '=', 1)->first()];
            } else {
                $resultArray = ["areas" => Area:: where('status', '=', 1)->get()];


            }


        } else {
            $resultArray['status'] = false;
            $resultArray['status_code'] = 406;
            $resultArray['msg'] = 'hatalı anahtar';
        }


        return response()->json($resultArray);
        // return json_encode($resultArray);
    }

    public function locationList(Request $request, $keyword = 'super-offer')
    {
        if ($request->header('x-api-key') == $this->generateKey()) {
            //  if(true){

            $location = SiteLocation::where('keyword', '=', $keyword)->first();
            $location_id = (!empty($location['id'])) ? $location['id'] : 1;
            $pl = ProductLocation::with('product', 'product.firstImage')->where('location_id', '=', $location_id)->orderBy('order')->get();
            //return $pl;
            $products = array();
            $i = 0;
            foreach ($pl as $p) {
                $pp = $p->product()->first();
                $img = $p->product()->first()->firstImage()->first();
                $stockIn = ProductStockMovement::where('status', '=', 'in')->where('product_id', '=', $pp->id)->sum('quantity');
                $stockOut = ProductStockMovement::where('status', '=', 'out')->where('product_id', '=', $pp->id)->sum('quantity');

                $products[$i]['id'] = $pp->id;
                $products[$i]['title'] = $pp->title;
                $products[$i]['listprice'] = $pp->price . "\u20ba";
                $products[$i]['win'] = ($pp->price_ex - $pp->price) . "\u20ba";
                $products[$i]['imageUrl'] = url($img->thumb);
                $products[$i]['stockItem'] = $stockIn - $stockOut;
                $products[$i]['soldItem'] = $stockOut;
                $products[$i]['seconds'] = 28800;
                $i++;
            }
            return $products;
        } else {
            $resultArray['status'] = false;
            $resultArray['status_code'] = 406;
            $resultArray['msg'] = 'hatalı anahtar';
        }


        return response()->json($resultArray);
        // return json_encode($resultArray);
    }

    //////AREA
    public function banners(Request $request)
    {
        //  if ($request->header('x-api-key') == $this->generateKey()) {
        if (true) {
            $banners = Area::whereIn('code', ['A2', 'A3', 'A4'])->where('status', '=', 1)->orderBy('id')->get();
            $array = [];
            $i = 0;
            foreach ($banners as $banner) {
                $array[$i]['id'] = $banner['id'];
                $array[$i]['title'] = $banner['txt_1'];
                $array[$i]['url'] = (!empty($banner['url'])) ? $banner['url'] : '#';
                $array[$i]['imageUrl'] = url($banner['image']);
                $array[$i]['type'] = $banner['type'];
                $array[$i]['textStyle'] = $banner['textStyle'];
                $i++;

            }

            return $array;

        }
    }

    public function midBanner(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {
            //    if(true){
            $banner = Area::whereIn('code', ['A1'])->where('status', '=', 1)->first();
            /*    $array = [];
                $i=0;
                foreach ($banners as $banner){
                    $array[$i]['id']=$banner['id'];
                    $array[$i]['title']=$banner['txt_1'];
                    $array[$i]['url']=(!empty($banner['url']))?$banner['url']:'#';
                    $array[$i]['imageUrl']=url($banner['image']);
                    $array[$i]['type']=$banner['type'];
                    $array[$i]['textStyle']=$banner['textStyle'];
                    $i++;

                }*/

            return response()->json(['banner' => $banner['txt_1']]);

        }
    }

    public function shoppageHeader(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {
            //    if(true){
            $banner = Area::whereIn('code', ['A5'])->where('status', '=', 1)->first();
            /*    title: 'Sanal Gerçdfgdseklik Gözlükleri',
            content: 'Nullam dignissim elit ut urna rutrum, a fermentum<a href="#">İncele <i className="tm tm-long-arrow-right"></i></a>',
            imageUrl: '/assets/images/products/jumbo.jpg'

                }*/

            $array= array();
            $array['title'] = $banner['txt_1'];
            $array['content'] = $banner['txt_2'];
            $array['imageUrl'] = url($banner['image']);

            return response()->json($array);

        }
    }

    public function superOffer(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {
            if (true) {

                $faker = Factory::create();
                /***
                 * dealItem = [
                 * { id: 1, title: 'Ta555blet Red EliteBook Revolve',
                 * listPrice: '₺304,00', price: '₺543,00', win: '₺120', imageUrl: 'assets/images/products/1.jpg', stockItem: 1000, soldItem: 300, seconds: 28800 },
                 * { id: 2, title: 'Tablet Red EliteBook Revolve', listPrice: '₺424,00', price: '₺543,00', win: '₺120', imageUrl: 'assets/images/products/2.jpg', stockItem: 1000, soldItem: 300, seconds: 28800 },
                 * ]
                 */

                $array = [];
                //    foreach ($banners as $banner){
                for ($i = 0; $i < 2; $i++) {

                    $price = rand(2, 10) * 100;
                    $dis = rand(1, 3) * 30;
                    $array[$i]['id'] = $i + 1;
                    $array[$i]['title'] = $faker->sentence;
                    $array[$i]['listPrice'] = $price;
                    $array[$i]['price'] = $price + $dis;

                    $array[$i]['imageUrl'] = url('images/products/' . rand(1, 16) . ".jpg");
                    $array[$i]['win'] = $dis;
                    $array[$i]['stockItem'] = rand(10, 1000);
                    $array[$i]['soldItem'] = rand(10, 1000);
                    $array[$i]['seconds'] = 28800;;

                }

                return $array;

            }

        }
    }

    public function leftMenu(Request $request)
    {
        $i=1;
       // $array = ['Süper Teklif','Haftanın Fırsatları','Çok Satanlar','Yeni Ürünler','En Yüksek Puanlı'];
    /**    $array = ['Telefonlar'];
        foreach($array as $item){
            $m = new MenuItem();
            $m->title = $item;
            $m->link = '#';
            $m->location = 3;
            $m->order = $i;
            $m->status =1;
            $m->save();
            $i++;

        }

        $sub = new MenuSubItem();
        $sub->menu_id=$m['id'];
        $sub->title ="Telefonlar";
        $sub->link = "#";
        $sub->thumb = "images/menu/megamenu-1.jpg";
        $sub->image = "images/menu/megamenu-1.jpg";
        $sub->order = 1;
        $sub->status = 1;
        $sub->save();



          $subs = ['iPhone 11','iPhone 12','iPhone 13','Galaxy S Serisi','Galaxy Note Serisi'
              ,'Galaxy A Serisi','Huawei P Serisi','Huawei Mate Serisi','Huawei Nova Serisi',
              'Huawei Y Serisi','Xiaomi RedMi 9C','Xiaomi Redmi Note 10','Xiaom POCO X3 Pro','Xiaomi 11T 10','Xiaomi 11T Lite'
          ];
          $i=1;
          foreach ($subs as $s){
              $item = new MenuSubItemLink();
              $item->menu_sub_item_id = $sub['id'];
              $item->title = $s;
              $item->link='#';
              $item->order= $i;
              $item->status =1;
              $item->save();
              $i++;
          }**/

        $array = array();
        $i=0;
        $menus = MenuItem::with('sub_items','sub_items.menu_groups')
            ->where('location','=',3) //->where('id','<',17)
            ->orderBy('order')->get();
        foreach ($menus as $menu){
            $array[$i]['id'] = $menu['id'];
            $array[$i]['title'] = $menu['title'];
            $array[$i]['url'] = (!empty($menu['link']))? $menu['link']:'#';
            if($menu->sub_items()->count()>0){
                $j=0;
                $array_sub =new Object_();

                foreach ($menu->sub_items()->get() as $sub_item) {
                    $array_sub->title = $sub_item['title'];
                    $array_sub->imageUrl=url($sub_item['image']);
                    $group_array=array();
                    if($sub_item->menu_groups()->count()>0){
                        $k=0;
                        foreach ($sub_item->menu_groups()->get() as $group) {
                            $group_array[$k]['title'] = $group['title'];
                            $group_array[$k]['imageUrl'] = (!empty($group['link']))?$group['link']:'#';
                            $link_array=array();

                            if($group->menu_links()->count()>0){
                                $m=0;
                                foreach ($group->menu_links()->get() as $link){
                                    $link_array[$m]['title'] = $link['title'];
                                    $link_array[$m]['url'] = (!empty($link['link']))?$link['link']:'#';
                                    $m++;
                                }
                            }


                            $group_array[$k]['items'] =$link_array;
                            $k++;
                        }
                    }

                    $array_sub->items = $group_array;
                }




               //  $array[$i]['subMenu']=(object)$array_sub;
             //   $array[$i]['subMenu'] = array();
                $array[$i]['subMenu'] =  $array_sub;
            }else{
                $array[$i]['subMenu'] =  array();
            }

            $i++;
        }
        return $array;
    }

    public function topMenu(Request $request)
    {

        /***
         *
         *   menuItems = [
        {
        id: 2, isDropdown: true, title: 'TELEFONLAR', url: '#', subItems: [
        { id: 11, isDropdown: false, 'title': 'Apple', url: '/urunler/telefonlar-2?brand=apple' },
        { id: 12, isDropdown: false, 'title': 'Saxxmsung', url: '/urunler/telefonlar-2?brand=samsung' },
        { id: 13, isDropdown: false, 'title': 'Xiaomi', url: '/urunler/telefonlar-2?brand=xiaomi' },
        { id: 14, isDropdown: false, 'title': 'Huawei', url: '/urunler/telefonlar-2?brand=huawei' }
        ]
        },
        {
        id: 3, isDropdown: true, title: 'TABLETLER', url: '#', subItems: [
        { id: 21, isDropdown: false, 'title': 'Apple', url: '/urunler/tabletler-3?brand=apple' },
        { id: 22, isDropdown: false, 'title': 'Samsung', url: '/urunler/telefonlar-3?brand=samsung' },
        { id: 23, isDropdown: false, 'title': 'Xiaomi', url: '/urunler/telefonlar-3?brand=xiaomi' },
        { id: 24, isDropdown: false, 'title': 'Huawei', url: '/urunler/telefonlar-3?brand=huawei' }
        ]
        },
        { id: 3, isDropdown: false, title: 'TELEFON SAT', url: '/sell-phone', subItems: [] },
        { id: 4, isDropdown: false, title: 'TELEFON ONAR / YENİLE', url: '/repair-phone', subItems: [] },
        { id: 5, isDropdown: false, title: 'GARANTİ SORGULA', url: '/warrany', subItems: [] },
        ];

         *
         */

   $array = array();
        $i=0;
        $menus = MenuItem::with('sub_items','sub_items.menu_groups')
            ->where('location','=',1)->where('status','=',1)
            ->orderBy('order')->get();
        foreach ($menus as $menu){
            $array[$i]['id'] = $menu['id'];

            if($menu->sub_items()->count()>0){
                $array[$i]['isDropdown'] = true;
                $array[$i]['title'] = $menu['title'];
                $array[$i]['url'] = (!empty($menu['link']))? $menu['link']:'#';

                $sub_menu = array();
                $j=0;
                foreach ($menu->sub_items()->get() as $sub){
                    $sub_menu[$j]['id'] = $sub['id'];
                    $sub_menu[$j]['isDropdown'] =false;
                    $sub_menu[$j]['title'] =$sub['title'];
                    $sub_menu[$j]['url'] =(!empty($sub['link']))? $sub['link']:'#';
                    $j++;
                }
                $array[$i]['subItems'] = $sub_menu;

            }else{
                $array[$i]['isDropdown'] = false;
                $array[$i]['title'] = $menu['title'];
                $array[$i]['url'] = (!empty($menu['link']))? $menu['link']:'#';
                $array[$i]['subItems'] =  array();
            }

            $i++;
        }
        return $array;
    }

    public function footerMenu(Request $request)
    {

        /***
        data = {
        menuData: [
        {
        id: 3,
        title: 'KURUMSAL',
        items: [
        { title: 'Hakkımızda', url: '' },
        { title: 'Hizmetlerimiz', url: '' },
        { title: 'Bizden Haberler', url: '' },
        { title: 'İnsan Kaynakları', url: '' },
        { title: 'Kullanım Kılavuzu', url: '' },
        ]
        },
        {
        id: 3,
        title: '',
        items: [

        { title: 'Çerez Politikası', url: '' },
        { title: 'Satış Sözleşmesi', url: '' },
        { title: 'Bize Ulaşın', url: '' },
        { title: 'S.S.S.', url: '' }
        ]
        },


        {
        id: 1,
        title: 'HIZLI ERİŞİM',
        items: [
        { title: 'Süper Teklif', url: '' },
        { title: 'Telefonlar', url: '' },
        { title: 'Tabletler', url: '' },
        { title: 'Aksesuarlar', url: '' },
        { title: 'Apple', url: '' },
        { title: 'Samsung', url: '' },
        ]
        }, {
        id: 2,
        title: 'İŞLEMLER',
        items: [
        { title: 'Telefon Sat', url: '' },
        { title: 'Telefon Onar / Yenile', url: '' },
        { title: 'IMEI Sorgula', url: '' },
        { title: 'Üye Ol', url: '' },
        { title: 'Üye Grişi', url: '' },
        { title: 'Sipariş Takibi', url: '' },
        { title: 'İade Formu', url: '' },
        ]
        },

        ]
        }
         *
         */




        $i=0;
        $data =   new Object_();
        $menuData= array();
        $menus = MenuItem:: where('location','=',5)->where('status','=',1)
            ->orderBy('order')->get();


        foreach ($menus as $menu){
            $menuData[$i]['id']=$menu['id'];
            $menuData[$i]['title']=$menu['title'];
            $items=array();
            $j=0;
            $subItems = MenuSubItem::where('menu_id','=',$menu['id'])->orderBy('order')->get();
            foreach ($subItems as $subItem){
           //foreach ($menu->sub_items()->orderBy('order')->get() as $item){
                $items[$j]=['title'=>(!empty($subItem['title']))?$subItem['title']:'x','url'=>(!empty($subItem['link']))?$subItem['link']:''];
                $j++;
            }
            $menuData[$i]['items']=$items;
            $i++;
        }
        $data->menuData=$menuData;
        //$data['menuData'] =$menuData;
        //return response()->json(['data'=>$data]);
        return  response()->json($data);
    }

    public function mobileMenu(Request $request)
    {

        /***
        mobileNav = [
        { id: 1, title: "Süper Teklif", url: "#" },
        { id: 2, title: "Telefonlar", url: "#" },
        { id: 3, title: "Tabletler", url: "#" },
        { id: 4, title: "Aksesuarlar", url: "#" },
        { id: 5, title: "Telefon Sat", url: "#" },
        { id: 6, title: "Telefon Onar / Yenile", url: "#" },
        {
        id: 7, title: "Kurumsal", url: "#",
        subs: [
        { id: 71, title: 'Hakkımızda', url: '#' },
        { id: 72, title: 'Hizmetlerimiz', url: '#' },
        { id: 73, title: 'Bizden Haberler', url: '#' },
        { id: 74, title: 'İnsan Kaynakları', url: '#' },
        ]
        },
        { id: 8, title: "Garantili Sorgula", url: "#" },
        { id: 9, title: "IMEI Sorgula", url: "#" },
        { id: 10, title: "İade Formu", url: "#" },
        { id: 11, title: "S.S.S.", url: "#" },
        { id: 11, title: "Bize Ulaşın", url: "#" },
        ]

         *
         */

        $i=0;
        $mobileNav= array();
        $menus = MenuItem:: where('location','=',4)->where('status','=',1)
            ->orderBy('order')->get();
        foreach ($menus as $menu){
            $mobileNav[$i]['id']=$menu['id'];
            $mobileNav[$i]['title']=$menu['title'];
            $mobileNav[$i]['url']=(!empty($menu['link']))?$menu['link']:'#';
            if(MenuSubItem::where('menu_id','=',$menu['id'])->orderBy('order')->count()>0){
            $items=array();
            $j=0;
            $subItems = MenuSubItem::where('menu_id','=',$menu['id'])->orderBy('order')->get();
            foreach ($subItems as $subItem){

                $items[$j]=['title'=>(!empty($subItem['title']))?$subItem['title']:'','url'=>(!empty($subItem['link']))?$subItem['link']:''];
                $j++;
            }
            $mobileNav[$i]['items']=$items;
                }
            $i++;
        }

      return  response()->json( $mobileNav);

    }

    public function shoppageMenu(Request $request)
    {



        $i=0;
        $mobileNav= array();
        $menus = MenuItem:: where('location','=',6)->where('status','=',1)
            ->orderBy('order')->get();
        foreach ($menus as $menu){
            $mobileNav[$i]['id']=$menu['id'];
            $mobileNav[$i]['title']=$menu['title'];
            $mobileNav[$i]['url']=(!empty($menu['link']))?$menu['link']:'#';
            if(MenuSubItem::where('menu_id','=',$menu['id'])->orderBy('order')->count()>0){
            $items=array();
            $j=0;
            $subItems = MenuSubItem::where('menu_id','=',$menu['id'])->orderBy('order')->get();
            foreach ($subItems as $subItem){

                $items[$j]=['title'=>(!empty($subItem['title']))?$subItem['title']:'','url'=>(!empty($subItem['link']))?$subItem['link']:''];
                $j++;
            }
            $mobileNav[$i]['items']=$items;
                }
            $i++;
        }

      return  response()->json( $mobileNav);

    }

    public function socialIcons(Request $request){


        $return_array = [
             ['id'=>1,'url'=>'https://facebook.com','icon'=>'fa fa-facebook'],
             ['id'=>2,'url'=>'https://twitter.com','icon'=>'fa fa-twitter'],
             ['id'=>3,'url'=>'https://instagram.com','icon'=>'fa fa-instagram'],
             ['id'=>4,'url'=>'https://linkedin.com','icon'=>'fa fa-linkedin'],
             ['id'=>4,'url'=>'https://youtube.com','icon'=>'fa fa-youtube-play']
            ];

        return $return_array;
    }


    /////////////ADDRESS
    public function getCities(Request $request)
    {
        if ($request->header('x-api-key') == $this->generateKey()) {

            $cities = City::select('id','name')->orderBy('name')->get();

            return  response()->json( $cities);

        }
    }

    public function getTowns(Request $request,$city_id=0)
    {
        $city = City::find($city_id);

        if ($request->header('x-api-key') == $this->generateKey() && (!empty($city['id']))) {

            $towns = Town::select('id','name')->where('city_id','=',$city_id)->orderBy('name')->get();
            return  response()->json( $towns);
        }
    }

    public function getDistricts(Request $request,$town_id=0)
    {
        $town = Town::find($town_id);

        if ($request->header('x-api-key') == $this->generateKey() && (!empty($town['id']))) {

            $districts = District::select('id','name')->where('town_id','=',$town_id)->orderBy('name')->get();
            return  response()->json( $districts);
        }
    }

    public function getNeighborhoods(Request $request,$district_id=0)
    {
        $district = District::find($district_id);

        if ($request->header('x-api-key') == $this->generateKey() && (!empty($district['id']))) {

            $neighborhoods = Neighborhood::select('id','name')->where('district_id','=',$district_id)->orderBy('name')->get();
            return  response()->json( $neighborhoods);
        }
    }
}
