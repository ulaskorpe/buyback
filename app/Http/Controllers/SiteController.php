<?php

namespace App\Http\Controllers;

use App\Enums\MenuLocations;
use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\MenuItem;
use App\Models\MenuSubItem;
use App\Models\SiteSetting;
use App\Models\Slider;
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
                $slider->data = (!empty($request['data']))?$request['data']:'';
                $slider->link = (!empty($request['link']))?$request['link']:'';
                $slider->note = (!empty($request['note']))?$request['note']:'';
                $slider->status = (!empty($request['status']))?1:0;
                $slider->count = $request['count'];

                $file = $request->file('slider');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/slider");
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('slider');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $slider->image = "images/slider/" . $filename;
                    $slider->thumb = "images/slider/" . $th;
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
                $slider->data = (!empty($request['data']))?$request['data']:'';
                $slider->link = (!empty($request['link']))?$request['link']:'';
                $slider->note = (!empty($request['note']))?$request['note']:'';
                $slider->status = (!empty($request['status']))?1:0;
                $slider->count = $request['count'];

                $file = $request->file('slider');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/slider");
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('slider');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $slider->image = "images/slider/" . $filename;
                    $slider->thumb = "images/slider/" . $th;
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
        return view('admin.site.slider.update',['slider'=>Slider::find($id),'count'=>$count]);
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

                $file = $request->file('image');
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

                $file = $request->file('image');
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
        $menu = MenuItem::with('sub_items')->find($id);
        $count = MenuItem::where('location','=',$menu['location'])->count();
        return view('admin.site.menu.update',['menu'=>$menu,'count'=>$count,
            'locations'=>MenuLocations::asArray(),'menu_locations'=>$this->menu_locations
        ,'menu_id'=>$id

        ]);
    }

    public function getMenuCount($location,$add =1,$selected=0){

        $count = MenuItem::where('location','=',$location)->count();
        $count = ($count == 0)?2:$count+$add;
            $txt = "";

        for($i=1;$i<($count );$i++){
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

                /*
                $file = $request->file('image');
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

                MenuSubItem::where('order','>=',$request['order_sub'])
                    ->where('menu_id','=',$request['menu_id'])
                    ->where('id','<>',$menu['id'])
                    ->increment('order');




                return ['yeni alt menü unsuru eklendi', 'success', route('site.update-menu',$request['menu_id']), '', ''];
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
        MenuSubItem::where('order','>=',$sub['order'])
            ->where('menu_id','=',$sub['menu_id'])
            ->where('id','<>',$sub['id'])
            ->decrement('order');
        $sub->delete();
        return "Alt bağlantı silindi";
    }

}
