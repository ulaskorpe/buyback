<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Answer;
use App\Models\Color;
use App\Models\Memory;
use App\Models\ModelAnswer;
use App\Models\ModelQuestion;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use App\Models\Question;
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


        return view('admin.brand.brandupdate', ['brand' => ProductBrand::find($id),'brand_id'=>$id]);
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

    public function colorlist()
    {

        return view('admin.color.colorlist', ['colors' => Color::all()]);
    }

    public function coloradd()
    {

        return view('admin.color.coloradd');
    }

    public function colorUpdate ($id)
    {

        return view('admin.color.colorupdate',['color'=>Color::find($id),'color_id'=>$id]);
    }

    public function coloraddPost(Request $request)
    {
        if ($request->isMethod('post')) {


            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

//                $rgb = $request['color_code'];
//                $rgbarr = explode(",",$rgb,3);
//                $color_code= sprintf("#%02x%02x%02x", $rgbarr[0], $rgbarr[1], $rgbarr[2]);


//                $rgb = str_replace('rgb(', '', str_replace(')', '', trim($request['color_code'])));
//                $arr = explode(",", $rgb);
//
//                $r = (intval($arr[0]) > 0) ? dechex(intval($arr[0])) : "00";
//                $g = (intval($arr[1]) > 0) ? dechex(intval($arr[1])) : "00";
//                $b = (intval($arr[2]) > 0) ? dechex(intval($arr[2])) : "00";
//
//                $rgb = "#" . $r . $g . $b;
                $color = new Color();
                $color->color_name = $request['color_name'];
                $color->color_code = $request['color_code'];
                $color->status = (!empty($request['status'])) ? 1 : 0;

                $file = $request->file('sample');
                if (!empty($file)) {


                    $file = $request->file('sample');
                    $filename = GeneralHelper::fixName($request['color_name']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/colors/");
                    $img = Image::make($file->getRealPath());
                    $img->resize(100, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $filename);


                    $color->sample = "images/colors/" . $filename;

                }

                $color->save();


                return ['yeni renk eklendi', 'success', route('color.color-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function colorUpdatePost(Request $request)
    {
        if ($request->isMethod('post')) {


            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


                $color = Color::find($request['id']);
                $color->color_name = $request['color_name'];
                $color->color_code = $request['color_code'];
                $color->status = (!empty($request['status'])) ? 1 : 0;

                $file = $request->file('sample');
                if (!empty($file)) {

                    $file = $request->file('sample');
                    $filename = GeneralHelper::fixName($request['color_name']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/colors/");
                    $img = Image::make($file->getRealPath());
                    $img->resize(100, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $filename);
                    $color->sample = "images/colors/" . $filename;
                }

                $color->save();

                return ['renk güncellendi', 'success', route('color.color-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function questionList(){
        return view('admin.question.questionlist',['questions'=>Question::all()]);
    }

    public function questionAdd(){
        return view('admin.question.questionadd');
    }

    public function questionUpdate($q_id){
        return view('admin.question.questionupdate',['question'=>Question::find($q_id),
            'answers'=>Answer::where('question_id','=',$q_id)->orderBy('count')->get(),'question_id'=>$q_id]);
    }

    public function questionAddPost(Request $request)
    {
        if ($request->isMethod('post')) {
            // return $request;

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $keys = ['0','a','b','c','d','e','f','g'];
                $q = new Question();
                $q->question = $request['question_value'];
                $q->status = (!empty($request['status']))?1:0;
                $q->save();
                for($i=1;$i<($request['count']+1);$i++){
                    $name = "answer".$i;
                    if(!empty($request[$name])){
                        $a = new Answer();
                        $a->question_id = $q['id'];
                        $a->answer = $request[$name];
                        $a->key = $q['id'].$keys[$i];
                        $a->count = $i;
                        $a->save();
                        //echo $i.".".$request[$name]."<br>";
                    }
                }




                return ['yeni soru eklendi', 'success', route('question.question-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function questionUpdatePost(Request $request)
    {
        if ($request->isMethod('post')) {
            // return $request;

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $keys = ['0','a','b','c','d','e','f','g'];
                $q = Question::find($request['id']);
                $q->question = $request['question_value'];
                $q->status = (!empty($request['status']))?1:0;
                $q->save();
                for($i=1;$i<($request['count']+1);$i++){
                    $name = "answer".$i;
                    $a = Answer::where('question_id','=',$q['id'])->where('count','=',$i)->first();
                    $a->answer = $request[$name];
                    $a->save();
                    //echo $i.".".$request[$name]."<br>";

                }




                return ['Soru güncellendi', 'success', route('question.question-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateAnswer($answer_id,$value){
        $answer = Answer::find($answer_id);
        $answer->answer = trim($value);
        $answer->save();
        //return $answer['answer'].":".$value;
        return "Yanıt Güncellendi";

    }

    public function memorylist()
    {

        return view('admin.memory.memorylist',['memories'=>Memory::all()]);
    }

    public function memoryadd()
    {

        return view('admin.memory.memoryadd');
    }

    public function memoryAddPost(Request $request)
    {
        if ($request->isMethod('post')) {


            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $mem = new Memory();
                $mem->memory_value = $request['memory_value'];
                $mem->Status = (!empty($request['status'])) ? 1 : 0;
                $mem->save();
                return ['yeni hafıza tipi eklendi', 'success', route('memory.memorylist'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function memoryupdatePost(Request $request)
    {
        if ($request->isMethod('post')) {


            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $mem = Memory::find($request['id']);
                $mem->memory_value = $request['memory_value'];
                $mem->Status = (!empty($request['status'])) ? 1 : 0;
                $mem->save();
                return ['hafıza tipi güncellendi', 'success', route('memory.memorylist'), '', ''];
            });
            return json_encode($resultArray);

        }
    }


    public function memoryupdate($id)
    {

        return view('admin.memory.memoryupdate',['memory'=>Memory::find($id)]);
    }

    public function checkMemory($val,$id=0){
        if($id>0){
            $ch = Memory::where('memory_value','=',$val)->where('id','<>',$id)->first();
        }else{
            $ch = Memory::where('memory_value','=',$val)->first();
        }

        return (empty($ch['id'])) ? "ok":"";
    }

    public function getModels($brand_id){
        $models = ProductModel::where('Brandid','=',$brand_id)->orderBy('Modelname')->get();
        if(count($models)>0){

        $txt="<option value='0'>Seçiniz</option>";
        foreach ($models as $model){
            $txt.="<option value='".$model['id']."'>".$model['Modelname']."</option>";
        }
        return $txt;
        }else{
            return  "none";
        }
    }

    public function getQuestions($model_id){
        $questions=ModelQuestion::where('model_id','=',$model_id)->orderBy('count')->get();



        if(count($questions)>0){
            $array = [];
            $i=0;
            $q_array=[];
            foreach ($questions as $question){
                $q = Question::find($question['question_id']);
                $answers = Answer::where('question_id','=',$question['question_id'])->orderBy('count')->get();
                $q_array[$i]=$question['question_id'];
                //  $mq = ModelQuestion::where('model_id','=',$model_id)->where('question_id','=',$question['id'])->first();
                $array[$i]['question'] = $q['question'];
                $array[$i]['model_question_id'] = $question['id'];
                $array[$i]['order'] = $question['count'];
                //       echo $question['question']."<hr>";
                $j=0;
                $answer_array=array();
                foreach ($answers as $answer){
                    $model_answer = ModelAnswer::where('answer_id','=',$answer['id'])->where('model_id','=',$model_id)->first();
                    $answer_array[$j]['answer']=$answer['answer'];
                    $answer_array[$j]['answer_id']=$answer['id'];
                    $answer_array[$j]['key']=$answer['key'];
                    $answer_array[$j]['count']=$answer['count'];
                    $answer_array[$j]['model_answer_id']=$model_answer['id'];
                    $answer_array[$j]['value']=(!empty($model_answer['id']))?$model_answer['value']:0;
                    $j++;
                }
                $array[$i]['answers'] = $answer_array;
                $i++;
            }




        return view('admin.data.model_questions',['array'=>$array,'model'=>ProductModel::find($model_id)]);

        }else{
            return "none";
        }
    }

    public function getOffer($model_id,$answers){
        $array = explode("@",$answers);
            $model = ProductModel::with('brand','category')->find($model_id);
            echo $model['max_price'];
        //$txt =
        foreach ($array as $answer_id){
            if(!empty($answer_id)){

            }
        }


            return view('admin.data.get_offer',['answers'=>$array,'model'=>$model]);
    }

}
