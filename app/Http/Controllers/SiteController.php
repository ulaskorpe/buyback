<?php

namespace App\Http\Controllers;

use App\Enums\MenuLocations;
use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Area;
use App\Models\Banner;
use App\Models\Color;
use App\Models\MenuItem;
use App\Models\MenuSubItem;
use App\Models\MenuSubItemLink;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductLocation;
use App\Models\SiteLocation;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\SubLinkGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class SiteController extends Controller
{
    use ApiTrait;

    public  function sliderList(){

        return view('admin.site.slider.list',['sliders'=>Slider::all()]);
    }

    public function createSlider(){
        $count = Slider::count();

        return view('admin.site.slider.create',['count'=>$count+1]);
    }

    public function createSliderPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $slider= new Slider();
                $slider->title = $request['title'];
                $slider->subtitle = (!empty($request['subtitle']))?$request['subtitle']:'';
                $slider->link = (!empty($request['link']))?$request['link']:'';
                $slider->btn_1 = (!empty($request['btn_1']))?$request['btn_1']:'';
                $slider->btn_2 = (!empty($request['btn_2']))?$request['btn_2']:'';
                $slider->note = (!empty($request['note']))?$request['note']:'';
                $slider->status = (!empty($request['status']))?1:0;
                $slider->count = $request['count'];

                $file = $request->file('slider');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                  //  $path = public_path("images/slider");
                    $path = "images/slider";
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());


                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $slider->image = "images/slider/" . $filename;
                    $slider->thumb = "images/slider/" . $th;
                }



                $file2 = $request->file('bgimg');
                if (!empty($file2)) {

                    $filenamebg =  "BG_". date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                   // $path = public_path("images/slider");
                    $thbg =  "BGTH_". date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());


                    $img = Image::make($file2->getRealPath());
                    $img->save($path . '/' . $filenamebg);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $thbg);
                    $slider->bgimg = "images/slider/" . $filenamebg;
                    $slider->bgthumb = "images/slider/" . $thbg;
                }
                $slider->save();

                Slider::where('count','>=',$request['count'])
                    ->where('id','<>',$slider['id'])
                    ->increment('count');




                return ['yeni SLIDER eklendi', 'success', route('site.slider-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateSliderPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $slider= Slider::find($request['id']);
                $old=$slider['count'];

                $slider->title = $request['title'];
                $slider->subtitle = (!empty($request['subtitle']))?$request['subtitle']:'';
                $slider->link = (!empty($request['link']))?$request['link']:'';
                $slider->btn_1 = (!empty($request['btn_1']))?$request['btn_1']:'';
                $slider->btn_2 = (!empty($request['btn_2']))?$request['btn_2']:'';
                $slider->note = (!empty($request['note']))?$request['note']:'';
                $slider->status = (!empty($request['status']))?1:0;
                $slider->count = $request['count'];

                $file = $request->file('slider');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    //  $path = public_path("images/slider");
                    $path = "images/slider";
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());


                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $slider->image = "images/slider/" . $filename;
                    $slider->thumb = "images/slider/" . $th;
                }



                $file2 = $request->file('bgimg');
                if (!empty($file2)) {

                    $filenamebg =  "BG_". date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    // $path = public_path("images/slider");
                    $thbg =  "BGTH_". date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());


                    $img = Image::make($file2->getRealPath());
                    $img->save($path . '/' . $filenamebg);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $thbg);
                    $slider->bgimg = "images/slider/" . $filenamebg;
                    $slider->bgthumb = "images/slider/" . $thbg;
                }
                $slider->save();

                if($request['count']!=$old){
                if($request['count']>$old){
                    Slider:: where('count','>=',$old)
                        ->where('count','<=',$request['count'])
                        ->where('id','<>',$slider['id'])
                        ->decrement('count');

                }else{
                    Slider:: where('count','<=',$old)
                        ->where('count','>=',$request['count'])
                        ->where('id','<>',$slider['id'])
                        ->increment('count');
                }
                }

                return ['SLIDER güncellendi', 'success', route('site.slider-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateSlider($id){
        $count = Slider::count();
        return view('admin.site.slider.update',['slider'=>Slider::find($id),'count'=>$count,'slider_id'=>$id]);
    }

    public function siteSettings(){
        return view('admin.site.setting.list',['settings'=>SiteSetting::all()]);
    }

    public function createSetting(){


        $last = SiteSetting::orderBy('id','desc')->first();
        if(empty($last['id'])){
            $code = 'S00001';
        }else{
            $code='S';
            for($i=strlen($last['id']);$i<5;$i++){
                $code.="0";
            }
            $code.= ($last['id']+1);


        }

         return view('admin.site.setting.create',['code'=>$code] );
    }

    public function createSettingPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];



            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $setting = new SiteSetting();
                $setting->setting_name=$request['setting_name'];
                $setting->description =(!empty($request['description']))?$request['description']:'';
                $setting->value=$request['value'];
                $setting->code = $request['code'];
                $setting->save();



                return ['yeni ayar eklendi', 'success', route('site.site-settings'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateSetting($id){

        return view('admin.site.setting.update',['setting'=>SiteSetting::find($id)] );
    }

    public function updateSettingPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];



            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $setting = SiteSetting::find($request['id']);
                $setting->setting_name=$request['setting_name'];
                $setting->description =(!empty($request['description']))?$request['description']:'';
                $setting->value=$request['value'];
                $setting->save();



                return ['ayar güncellendi', 'success', route('site.site-settings'), '', ''];
            });
            return json_encode($resultArray);

        }
    }


    public  function menuList(){



       return view('admin.site.menu.list',['menus'=>MenuItem::all(), 'menu_locations'=>$this->menu_locations]);
    }

    public function createMenu(){

        return view('admin.site.menu.create',['locations'=>MenuLocations::asArray(),'menu_locations'=>$this->menu_locations]);
    }

    public function createMenuPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $menu = new MenuItem();
                $menu->title = $request['title'];

                $menu->link = (!empty($request['link']))?$request['link']:'';

                $menu->status = (!empty($request['status']))?1:0;
                $menu->order = $request['order'];
                $menu->location = $request['location'];

       /*         $file = $request->file('image');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/menu");
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('image');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $menu->image = "images/menu/" . $filename;
                    $menu->thumb = "images/menu/" . $th;
                }
*/
                $menu->save();

                MenuItem::where('order','>=',$request['order'])
                    ->where('location','=',$request['location'])
                    ->where('id','<>',$menu['id'])
                    ->increment('order');




                return ['yeni menü unsuru eklendi', 'success', route('site.menu-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateMenuPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $menu = MenuItem::find($request['id']);



                $old=$menu['order'];
                $old_location = $menu['location'];
                $menu->title = $request['title'];

                $menu->link = (!empty($request['link']))?$request['link']:'';

                $menu->status = (!empty($request['status']))?1:0;
                $menu->order = $request['order'];
                $menu->location = $request['location'];

           /**     $file = $request->file('image');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/menu");
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('image');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $menu->image = "images/menu/" . $filename;
                    $menu->thumb = "images/menu/" . $th;
                }
                    */
                $menu->save();


                if($old_location == $request['location']){
                    if($request['order']!=$old){
                        if($request['order']>$old){
                            MenuItem:: where('order','>=',$old)
                                ->where('location','=',$old_location)
                                ->where('order','<=',$request['order'])
                                ->where('id','<>',$menu['id'])
                                ->decrement('order');

                        }else{
                            MenuItem:: where('order','<=',$old)
                                ->where('order','>=',$request['order'])
                                ->where('id','<>',$menu['id'])
                                ->increment('order');
                        }
                    }
                }else{

                    MenuItem::where('order','>=',$request['order'])
                        ->where('location','=',$request['location'])
                        ->where('id','<>',$menu['id'])
                        ->increment('order');

                    MenuItem::where('order','>',$request['order'])
                        ->where('location','=',$old_location)
                        ->where('id','<>',$menu['id'])
                        ->decrement('order');
                }



                return ['Menü unsuru güncellendi', 'success', route('site.menu-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateMenu($id){
        $menu = MenuItem::with('sub_items','sub_items.menu_groups','sub_items.menu_groups.menu_links')->find($id);
        $count = MenuItem::where('location','=',$menu['location'])->count();


        return view('admin.site.menu.update',['menu'=>$menu,'count'=>$count,
            'locations'=>MenuLocations::asArray(),'menu_locations'=>$this->menu_locations
        ,'menu_id'=>$id

        ]);
    }

    public function getMenuCount($location,$add =0,$selected=0){

        $count = MenuItem::where('location','=',$location)->count();
        $add = ($selected>0)?0:1;
        $count = ($count == 0)?1:$count+$add;
            $txt = "";

     //   for($i=1;$i<($count );$i++){
        for($i=$count;$i>0;$i--){
            if($selected==$i){
                $txt.="<option value='".$i."' selected>".$i."</option>";
            }else{
                $txt.="<option value='".$i."'>".$i."</option>";
            }

        }
        return $txt;
    }

    public function createSubMenu($menu_id){

        return view('admin.site.menu.create_sub',['menu_id'=>$menu_id,'count'=>MenuSubItem::where('menu_id','=',$menu_id)->count()+2]);
    }

    public function createSubLink($group_id){

        return view('admin.site.menu.create_link',['group_id'=>$group_id,
            'group'=>SubLinkGroup::with('menu_sub_item')->find($group_id),
            'count'=>MenuSubItemLink::where('group_id','=',$group_id)->count()+2]);
    }

    public function createSubGroup($sub_menu_id){

        return view('admin.site.menu.create_group',['sub_menu_id'=>$sub_menu_id,'count'=>SubLinkGroup::where('menu_sub_item_id','=',$sub_menu_id)->count()+2]);
    }

    public function createSubMenuPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $menu = new MenuSubItem();
                $menu->title = $request['title_sub'];
                $menu->menu_id=$request['menu_id'];
                $menu->link = (!empty($request['link_sub']))?$request['link_sub']:'';

                $menu->status = (!empty($request['status_sub']))?1:0;
                $menu->order = $request['order_sub'];


                $file = $request->file('image');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path =  "images/menu";
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('image');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $menu->image = "images/menu/" . $filename;
                    $menu->thumb = "images/menu/" . $th;
                }

                $menu->save();

                MenuSubItem::where('order','>=',$request['order_sub'])
                    ->where('menu_id','=',$request['menu_id'])
                    ->where('id','<>',$menu['id'])
                    ->increment('order');




                return ['yeni alt menü unsuru eklendi', 'success', route('site.update-menu',$request['menu_id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function createSubLinkPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $group=SubLinkGroup::with('menu_sub_item')->find($request['group_id']);
                //$sub=MenuSubItem::find($request['sub_menu_id']);

                $menu = new MenuSubItemLink();
                $menu->title = $request['title_link'];
                $menu->group_id=$request['group_id'];
                $menu->link = (!empty($request['sub_link']))?$request['sub_link']:'';

                $menu->status = (!empty($request['status_link']))?1:0;
                $menu->order = $request['order_link'];

                $menu->save();
                MenuSubItemLink::where('order','>=',$request['order_link'])
                    ->where('group_id','=',$request['group_id'])
                    ->where('id','<>',$menu['id'])
                    ->increment('order');


                return ['yeni alt menü link eklendi', 'success', route('site.update-menu',$group->menu_sub_item()->first()->menu_id), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function createSubGroupPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $sub=MenuSubItem::find($request['sub_menu_id']);

                $menu = new SubLinkGroup();
                $menu->title = $request['title_group'];
                $menu->menu_sub_item_id=$request['sub_menu_id'];
                $menu->link = (!empty($request['group_link']))?$request['group_link']:'';

                $menu->status = (!empty($request['status_group']))?1:0;
                $menu->order = $request['order_group'];

                $menu->save();
                SubLinkGroup::where('order','>=',$request['order_group'])
                    ->where('menu_sub_item_id','=',$request['sub_menu_id'])
                    ->where('id','<>',$menu['id'])
                    ->increment('order');


                return ['yeni alt menü link grubu eklendi', 'success', route('site.update-menu',$sub['menu_id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateSubMenu($menu_id){
        $sub_menu=MenuSubItem::find($menu_id);
        return view('admin.site.menu.update_sub',['sub_menu'=>$sub_menu,'menu_id'=>$sub_menu['menu_id'],'count'=>MenuSubItem::where('menu_id','=',$sub_menu['menu_id'])->count()+1]);
    }

    public function updateSubMenuPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $menu = MenuSubItem::find($request['id']);
                $old=$menu['order'];
                $menu->title = $request['title_sub'];
                $menu->link = (!empty($request['link_sub']))?$request['link_sub']:'';
                $menu->status = (!empty($request['status_sub']))?1:0;
                $menu->order = $request['order_sub'];


                $menu->save();

                if($request['order_sub']!=$old){
                    if($request['order_sub']>$old){
                        MenuSubItem:: where('order','>=',$old)
                            ->where('menu_id','=',$request['menu_id'])
                            ->where('order','<=',$request['order_sub'])
                            ->where('id','<>',$menu['id'])
                            ->decrement('order');

                    }else{
                        MenuSubItem:: where('order','<=',$old)
                            ->where('menu_id','=',$request['menu_id'])
                            ->where('order','>=',$request['order_sub'])
                            ->where('id','<>',$menu['id'])
                            ->increment('order');
                    }
                }


                return ['Alt menü unsuru güncellendi', 'success', route('site.update-menu',$request['menu_id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }


    public function deleteSubMenu($menu_id){
        $sub=MenuSubItem::find($menu_id);

        $groups = SubLinkGroup::where('menu_sub_item_id','=',$menu_id)->get();
        foreach ($groups as $group){
            MenuSubItemLink::where('group_id','=',$group['id'])->delete();
        }
        SubLinkGroup::where('menu_sub_item_id','=',$menu_id)->delete();


        MenuSubItem::where('order','>=',$sub['order'])
            ->where('menu_id','=',$sub['menu_id'])
            ->where('id','<>',$sub['id'])
            ->decrement('order');
        $sub->delete();
        return "Alt bağlantı silindi";
    }

    public function deleteSubGroup($group_id){

        $group = SubLinkGroup::find($group_id);

            MenuSubItemLink::where('group_id','=',$group['id'])->delete();




        SubLinkGroup::where('order','>=',$group['order'])
            ->where('menu_sub_item_id','=',$group['menu_sub_item_id'])
            ->where('id','<>',$group['id'])
            ->decrement('order');
        $group->delete();
        return "Bağlantı grubu silindi";
    }

    public function deleteSubLink($link_id){
        $sub=MenuSubItemLink::find($link_id);
        MenuSubItemLink::where('order','>=',$sub['order'])
            ->where('menu_sub_item_id','=',$sub['menu_sub_item_id'])
            ->where('id','<>',$sub['id'])
            ->decrement('order');
        $sub->delete();
        return "Alt Link silindi";
    }


    public  function areaList(){
//$areas = Area::all();
//foreach ($areas as $area){
//    $code = "A".$area['id'];
//    $area->code = $code;
//    $area->save();
//}
        return view('admin.site.area.list',['areas'=> Area::all()]);
    }

    public function createArea(){

//        $ch = true;
//        while($ch){
//        $code ="A".rand(100,999);
//        $ch=$this->checkUnique('code','areas',$code);
//        }

        $last = Area::select('id')->orderBy('id','desc')->first();
        $code =(!empty($last['id'])) ? "A".($last['id']+1) : "A1";
        return view('admin.site.area.create',['code'=>$code]);
    }

    public function createAreaPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $area= new Area();
                $area->title = $request['title'];
                $area->code = $request['code'];
                $area->txt_1 = (!empty($request['txt_1']))?$request['txt_1']:'';
                $area->txt_2 = (!empty($request['txt_2']))?$request['txt_2']:'';
                $area->link = (!empty($request['link']))?$request['link']:'';
                $area->type = $request['type'];
                $area->textStyle = $request['textStyle'];

                $area->status = (!empty($request['status']))?1:0;
                $file = $request->file('area');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                 //   $path = public_path("images/area");
                    $path = "images/area";
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('area');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $area->image = "images/area/" . $filename;
                    $area->thumb = "images/area/" . $th;
                }

                $area->save();





                return ['yeni ALAN eklendi', 'success', route('site.area-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateAreaPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $area= Area::find($request['id']);
                $area->title = $request['title'];
                $area->txt_1 = (!empty($request['txt_1']))?$request['txt_1']:'';
                $area->txt_2 = (!empty($request['txt_2']))?$request['txt_2']:'';
                $area->link = (!empty($request['link']))?$request['link']:'';
                $area->type = $request['type'];
                $area->textStyle = $request['textStyle'];
                $area->status = (!empty($request['status']))?1:0;
                $file = $request->file('area');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                   // $path = public_path("images/area");
                    $path = "images/area";
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('area');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $area->image = "images/area/" . $filename;
                    $area->thumb = "images/area/" . $th;
                }

                $area->save();





                return ['ALAN Güncelendi', 'success', route('site.area-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateArea($id){

        return view('admin.site.area.update',['area'=>Area::find($id),'area_id'=>$id]);
    }

    public  function productLocation ($brand_id=0,$model_id=0){
        if($model_id>0  ){
            $products=Product::with('images')->where('model_id','=',$model_id)->get();
        }elseif($brand_id>0 && $model_id==0){
            $products=Product::with('images')->where('brand_id','=',$brand_id)->get();
        }else{
            $products=Product::with('images')->get();
        }

        return view('admin.site.product.list',['products'=>$products,'model_id'=>$model_id,'brand_id'=>$brand_id]);
    }

    public function locateProduct($id){
        $p_locations = ProductLocation::where('product_id','=',$id)->pluck('location_id')->toArray();
        $site_locations= SiteLocation::whereNotIn('id',$p_locations)->get();

        $count_array = [];
        foreach (SiteLocation::all() as $location){
            $count_array[$location['id']] = ProductLocation::where('location_id','=',$location['id'])->count();
        }
       $locations = ProductLocation::with('location')->where('product_id','=',$id)->get();
        return view('admin.site.product.locations',['product'=>Product::with('images')->find($id),
            'product_id'=>$id,'locations'=>$locations,'count_locations'=>$count_array,'site_locations'=>$site_locations,'selected'=>0]);
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

                return ['ürün yeni konuma eklendi', 'success', route('site.locate-product',[$request['product_id']]), '', ''];
            });
            return json_encode($resultArray);

        }
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




    public  function bannerList(){
//$areas = Area::all();
//foreach ($areas as $area){
//    $code = "A".$area['id'];
//    $area->code = $code;
//    $area->save();
//}
        return view('admin.site.banner.list',['banners'=> Banner::all()]);
    }

    public function createBanner(){

//        $ch = true;
//        while($ch){
//        $code ="A".rand(100,999);
//        $ch=$this->checkUnique('code','areas',$code);
//        }

        $count = Banner::all()->count();

        return view('admin.site.banner.create',['count'=>$count]);
    }

    public function createBannerPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $banner= new Banner();
                $banner->title = $request['title'];
                $banner->order = $request['order'];
                $banner->link = $request['link'];
                $banner->status = (!empty($request['status']))?1:0;
                $file = $request->file('image');
                if (!empty($file)) {
                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    //   $path = public_path("images/area");
                    $path = "images/banners";

                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $banner->image = "images/banners/" . $filename;
                }
                $banner->save();

                Banner::where('order','>=',$request['order'])
                    ->where('id','<>',$banner['id'])
                    ->increment('order');



                return ['yeni BANNER eklendi', 'success', route('site.banner-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateBannerPost(Request $request){


        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $banner= Banner::find($request['id']);
                $old=$banner['order'];

                $banner->title = $request['title'];
                $banner->order = $request['order'];
                $banner->link = $request['link'];
                $banner->status = (!empty($request['status']))?1:0;
                $file = $request->file('image');
                if (!empty($file)) {
                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    //   $path = public_path("images/area");
                    $path = "images/banners";

                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $banner->image = "images/banners/" . $filename;
                }
                $banner->save();


                if($request['order']!=$old){
                    if($request['order']>$old){
                        Banner:: where('order','>=',$old)
                            ->where('order','<=',$request['order'])
                            ->where('id','<>',$banner['id'])
                            ->decrement('order');

                    }else{
                        Banner:: where('order','<=',$old)
                            ->where('order','>=',$request['order'])
                            ->where('id','<>',$banner['id'])
                            ->increment('order');
                    }
                }


                return ['BANNER Güncelendi', 'success', route('site.area-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateBanner($id){
        $count = Banner::all()->count();
        return view('admin.site.banner.update',['banner'=>Banner::find($id),'banner_id'=>$id,'count'=>$count]);
    }


}
