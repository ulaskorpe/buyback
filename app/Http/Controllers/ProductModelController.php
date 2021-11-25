<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Answer;
use App\Models\Memory;
use App\Models\ModelAnswer;
use App\Models\ModelQuestion;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductModel;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ProductModelController extends Controller
{

    use ApiTrait;

    public function modelList()
    {

        return view('admin.model.modellist', ['models' => ProductModel::with('brand', 'category','memory')->get()]);
    }

    public function modelAdd()
    {

        return view('admin.model.modeladd',
            ['brands' => ProductBrand::select('BrandName', 'id')->orderBy('BrandName')->get(),
                'categories' => ProductCategory::orderBy('category_name')->get(),'memories'=>Memory::orderBy('memory_value')->get()]);
    }

    public function modelUpdate($id)
    {

        return view('admin.model.modelupdate',
            ['brands' => ProductBrand::select('BrandName', 'id')->orderBy('BrandName')->get(),
                'categories' => ProductCategory::orderBy('category_name')->get(),'model'=>ProductModel::find($id)
                ,'memories'=>Memory::orderBy('memory_value')->get(),'model_id'=>$id]);
    }

    public function modeladdPost(Request $request)
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
                $model->Status = (!empty($request['status'])) ? 1 : 0;
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



                return ['yeni model eklendi', 'success', route('model.model-list'), '', ''];
            });
            return json_encode($resultArray);

        }


    }

    public function modelUpdatePost(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {


                $model = ProductModel::find($request['id']);



                $model->Modelname = $request['modelname'];
                $model->Brandid = $request['brand_id'];
                $model->Catid = $request['cat_id'];
                $model->memory_id = $request['memory_id'];
                $model->Seotitle = (!empty($request['seotitle'])) ? $request['seotitle'] : '';
                $model->SeoDesc = (!empty($request['seodesc'])) ? $request['seodesc'] : '';
                $model->Status = (!empty($request['status'])) ? 1 : 0;

                if($request['brand_id']!=$model['Brandid']){
                    $brand = ProductBrand::select('BrandName')->find($request['brand_id']);
                    $ch = true ;
                    while($ch){
                        $code = substr(strtolower($brand['BrandName']),0,4).rand(100000,999999);
                        $ch = $this->checkUnique('code','models',$code);

                    }
                    $model->code = $code;
                }


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


                return ['Model Güncellendi', 'success', route('model.model-list'), '', ''];
            });
            return json_encode($resultArray);

        }


    }

    public function modelQuestions($model_id){


//        $questions = Question::with('answers')
//            ->whereIn('id',ModelQuestion::where('model_id','=',$model_id)->orderBy('count')->pluck('question_id')->toArray())->get();
        $questions=ModelQuestion::where('model_id','=',$model_id)->orderBy('count')->get();
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
                $answer_array[$j]['key']=$answer['key'];
                $answer_array[$j]['count']=$answer['count'];
                $answer_array[$j]['model_answer_id']=$model_answer['id'];
                $answer_array[$j]['value']=(!empty($model_answer['id']))?$model_answer['value']:0;
                $j++;
            }
            $array[$i]['answers'] = $answer_array;
            $i++;
        }



        return view('admin.model.model_question',['array'=>$array,'model'=>ProductModel::find($model_id),
            'other_questions'=>Question::whereNotIn('id',$q_array)->get(),'last'=>($i+1),
            'model_id'=>$model_id]);

    }

    public function reorderModelQuestions($model_question_id,$count){
        $mq = ModelQuestion::find($model_question_id);


        if($mq['count']>$count){
            ModelQuestion::where('model_id','=',$mq['model_id'])
                ->where('count','>=',$count)
                ->where('count','<=',$mq['count'])
                ->where('id','<>',$mq['id'])
                ->increment('count');
        }else{
            ModelQuestion::where('model_id','=',$mq['model_id'])
                ->where('count','<=',$count)
                ->where('count','>=',$mq['count'])
                ->where('id','<>',$mq['id'])
                ->decrement('count');
        }

        $mq->count = $count;
        $mq->save();
        return "Soru sırası güncellendi";

    }

    public function addModelQuestions($question_id,$model_id,$count){
        ModelQuestion::where('model_id','=',$model_id)->where('count','>=',$count)->increment('count',1);
        $mq=new ModelQuestion();
        $mq->model_id = $model_id;
        $mq->question_id = $question_id;
        $mq->count = $count;
        $mq->save();
        return "Soru modele eklendi";
    }

    public function deleteModelQuestions($model_question_id){
        $mq = ModelQuestion::find($model_question_id);
        ModelQuestion::where('model_id','=',$mq['model_id'])->where('count','>',$mq['count'])->decrement('count',1);
        $mq->delete();
        return "Soru modelden silindi";
    }

    public function updateModelAnswer($model_answer_id,$value){

        ModelAnswer::where('id','=',$model_answer_id)->update(['value'=>$value]);

        return "Değer Güncellendi";//$model_answer_id.":".$value;
    }
}
