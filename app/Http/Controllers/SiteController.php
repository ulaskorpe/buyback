<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
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


}
