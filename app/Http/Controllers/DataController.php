<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Answer;
use App\Models\BuyBack;
use App\Models\CargoCompany;
use App\Models\CargoCompanyBranch;
use App\Models\City;
use App\Models\Color;
use App\Models\ColorModel;
use App\Models\District;
use App\Models\ImeiQuery;
use App\Models\Memory;
use App\Models\ModelAnswer;
use App\Models\ModelQuestion;
use App\Models\Neighborhood;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use App\Models\Question;
use App\Models\ServiceAddress;
use App\Models\Tmp;
use App\Models\Town;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;

class DataController extends Controller
{

    use ApiTrait;

    public function returnKey(Request $request){

        return response()->json(['key'=>$this->generateKey()]);

    }

    public function deleteTmp($id){
        $tmp = Tmp::find($id);
        $tmp->delete();
        return response()->json(['status'=>200,'msg'=>'DELETED']);
    }

    public function addTmp(Request $request){

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                //     return $request['invoice_date'];

                $messages = [];
                $rules = [
                    'name'=>'required',
                    'course'=>'required',
                    'email'=>'required|email',
                ];
                $this->validate($request, $rules, $messages);
                $resultArray = DB::transaction(function () use ($request) {

                    $tmp = new Tmp();
                    $tmp->title = $request['name'];
                    $tmp->data = $request['course'] . ":" . $request['email'] . ":" . $request['phone'];
                    $tmp->save();

                    return ['status'=>true, 'status_code'=>200,'msg'=>'Başarıyla Eklendi'];
                });

            }else{
                $resultArray['status'] = false;
                $resultArray['status_code'] = 406;
                $resultArray['msg'] = 'hatalı anahtar';
            }
        }else{
            $returnArray['status'] = false;
            $returnArray['status_code'] = 405;
            $returnArray['msg'] = 'uygun olmayan method';
        }

        return json_encode($resultArray);
    }

    public function updateTmp(Request $request){

        if ($request->isMethod('post')) {
            if ($request->header('x-api-key') == $this->generateKey()) {

                //     return $request['invoice_date'];

                $messages = [];
                $rules = [

                ];
                $this->validate($request, $rules, $messages);
                $resultArray = DB::transaction(function () use ($request) {

                    $tmp = Tmp::find($request['id']);
                    $tmp->title = $request['name'];
                    $tmp->data = $request['course']  ;
                    $tmp->save();

                    return ['status'=>true, 'status_code'=>200,'msg'=>'Başarıyla GÜNCELLENDİ'];
                });

            }else{
                $resultArray['status'] = false;
                $resultArray['status_code'] = 406;
                $resultArray['msg'] = 'hatalı anahtar';
            }
        }else{
            $returnArray['status'] = false;
            $returnArray['status_code'] = 405;
            $returnArray['msg'] = 'uygun olmayan method';
        }

        return json_encode($resultArray);
    }

    public function listTmp(){
        return response()->json(['items'=>Tmp::all(),'status'=>200]);
    }

    public function getTmp($id){
        $tmp = Tmp::find($id);
        if(!empty($tmp['id'])){
            return response()->json(['item'=>$tmp,'status'=>200]);
        }else{
            return response()->json(['item'=>null,'status'=>404]);
        }

    }

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
                 //   $path = public_path("images/brands/");
                    $path = "images/brands/";
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

    public function getModels($brand_id,$selected=0){

        $models = ProductModel::where('Brandid','=',$brand_id)->where('status','=',1)->orderBy('Modelname')->get();
        if(count($models)>0){

        $txt="<option value='0'>Seçiniz</option>";
        if($selected==0){
            foreach ($models as $model){
                $txt.="<option value='".$model['id']."'>".$model['Modelname']."</option>";
            }
        }else{
            foreach ($models as $model)
                if($model['id']==$selected){
                $txt.="<option value='".$model['id']."' selected>".$model['Modelname']."</option>";
                }else{
                    $txt.="<option value='".$model['id']."'>".$model['Modelname']."</option>";
                }
        }

        return $txt;
        }else{
            return  "none";
        }
    }

    public function getBrands($selected=0){
        $brands = ProductBrand::where('status','=',1)->orderBy('BrandName')->get();
        if(count($brands)>0){

        $txt="<option value='0'>Seçiniz</option>";
        if($selected==0){
            foreach ($brands as $brand){
                $txt.="<option value='".$brand['id']."'>".$brand['BrandName']."</option>";
            }
        }else{
            foreach ($brands as $brand)
                if($brand['id']==$selected){
                $txt.="<option value='".$brand['id']."' selected>".$brand['BrandName']."</option>";
                }else{
                    $txt.="<option value='".$brand['id']."'>".$brand['BrandName']."</option>";
                }
        }

        return $txt;
        }else{
            return  "none";
        }
    }

    public function getQuestions($model_id,$model_answer_array="",$buyback_id=0){


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


            if(!empty($model_answer_array)){
                $model_answer_array=explode('x',$model_answer_array);
                return view('admin.buyback.model_questions',
                    ['buyback'=>BuyBack::find($buyback_id),'array'=>$array,'model'=>ProductModel::find($model_id),'model_answer_array'=>$model_answer_array ]);
            }else{
               return view('admin.data.model_questions',['array'=>$array,'model'=>ProductModel::find($model_id) ]);
            }



        }else{
            return "none";
        }
    }

    public function getColors($model_id){
        $mc = ColorModel::where('model_id','=',$model_id)->pluck('color_id')->toArray();
        $colors = Color::where('id','>',0)->orderBy('color_name')->get();
        $txt="<option value='0'>Seçiniz</option>";
        foreach($colors as $color){
            $txt.="<option value='".$color['id']."'>".$color['color_name']."</option>";
        }
        return $txt;
    }

    public function getOffer($model_id,$answers){
        $array = explode("@",$answers);
            $model = ProductModel::with('brand','category')->find($model_id);

            $total_minus =0;
        $txt ="<table style='width: 100%'>";
        foreach ($array as $answer_id){
            if(!empty($answer_id)){
                $a = Answer::with('question')->find($answer_id);
                $ma = ModelAnswer::where('answer_id','=',$answer_id)->where('model_id','=',$model_id)->first();
                $txt.="<tr><td colspan='2' style='height: 35px'><b>".$a->question()->first()->question."</b></td></tr>";
               if(!empty($ma['id'])){
                    $total_minus+=$ma['value'];
                    if($ma['value']>0){
                        $txt.= "<tr><td width='50%' style='height: 35px'>".$a['answer']."</td><td>"." -".$ma['value']." TL</td></tr>";
                    }else{
                        $txt.="<tr><td style='height: 35px'>". $a['answer']."</td><td></td></tr>";
                    }

                }else{
                    $txt.= "<tr><td style='height: 35px'>".$a['answer']."</td><td></td></tr>";
                }

                $total = ( ($model['max_price']-$total_minus) >=$model['min_price']) ? ($model['max_price']-$total_minus) : $model['min_price'];




            }
        }

        $txt.="<tr><td colspan='2'><hr><b>TEKLİF :.".$total." TL</b></td></tr></table>";


            return view('admin.data.get_offer',['txt'=>$txt,'model'=>$model]);
    }

    public function getBuyerInfo($model_id,$calculate_result,$price,$imei,$color_id,$imei_id){

     //   return  $imei_id;
        return view("admin.data.buyer_info",['cities'=>City::select('id','name')->orderBy('name')->get(),
            'model_id'=>$model_id,'result'=>$calculate_result,'price'=>$price,'imei'=>$imei,'color_id'=>$color_id,'imei_id'=>$imei_id]);
    }

    public function getTowns($city_id,$selected=0){
        $towns = Town::select('id','name')->where('city_id','=',$city_id)->orderBy('name')->get();
        $txt="<option value='0'>İlçe Seçiniz</option>";
        if($selected> 0){
            foreach ($towns as $town){
                if($town['id']==$selected){
                    $txt.="<option value='".$town['id']."' selected>".$town['name']."</option>";
                }else{
                    $txt.="<option value='".$town['id']."'>".$town['name']."</option>";
                }
            }
        }else{
        foreach ($towns as $town){
            $txt.="<option value='".$town['id']."'>".$town['name']."</option>";
        }
        }
        return $txt;
    }

    public function getDistricts($town_id,$selected=0){
        $districts = District::select('id','name')->where('town_id','=',$town_id)->orderBy('name')->get();
        $txt="<option value='0'>Mahalle Seçiniz</option>";
        if($selected>0){

            foreach ($districts as $district){
                if($district['id']==$selected){
                    $txt.="<option value='".$district['id']."' selected>".$district['name']."</option>";
                }else{
                    $txt.="<option value='".$district['id']."'>".$district['name']."</option>";
                }

            }
        }else{
            foreach ($districts as $district){
                $txt.="<option value='".$district['id']."'>".$district['name']."</option>";
            }
        }

        return $txt;
    }

    public function getNeigborhoods($district_id,$selected=0){
        $neighborhoods = Neighborhood::select('id','name')->where('district_id','=',$district_id)->orderBy('name')->get();
        $txt="<option value='0'>Bölge Seçiniz</option>";

        if($selected>0){
            foreach ($neighborhoods as $neighborhood){
                if($neighborhood['id']==$selected){
                    $txt.="<option value='".$neighborhood['id']."' selected>".$neighborhood['name']."</option>";
                }else{
                    $txt.="<option value='".$neighborhood['id']."'>".$neighborhood['name']."</option>";
                }

            }
        }else{
            foreach ($neighborhoods as $neighborhood){
                $txt.="<option value='".$neighborhood['id']."'>".$neighborhood['name']."</option>";
            }
        }


        return $txt;
    }

    public function getPostalcode($neighborhood_id){
        $n = Neighborhood::find($neighborhood_id);
        return $n['zipcode'];
    }

    public function checkEmail($email){
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "no";
        }else{
            return  "ok";
        }
    }

    public function checkTckn($tckn){
        $tckn = intval($tckn);

        if($tckn < 10000000000 || $tckn > 99999999999){
            return "no";
        }else{
            return  "ok";
        }
    }

    public function checkIban($iban){
        $iban = intval($iban);

        if($iban < 1000000000000000 || $iban > 9999999999999999){
            return "no";
        }else{
            return  "ok";
        }
    }

    public function checkPhone($phone){
        $phone = intval($phone);
        if($phone < 2000000000 || $phone > 5999999999){
            return "no";
        }else{
            return  "ok";
        }

    }

    public function curl_get(){
        $endpoint = "http://my.domain.com/test.php";
        $client = new \GuzzleHttp\Client();
        $id = 5;
        $value = "ABC";

        $response = $client->request('GET', $endpoint, ['query' => [
            'key1' => $id,
            'key2' => $value,
        ]]);

// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();
    }

    public function checkImeiServer(){
//        $imei = intval($imei);
//        if($imei < 2000000000 || $imei > 5999999999){
//            return "no";
//        }else{
//            return  "ok";
//        }

//Your username.
        $username =  'ede5e6da-fe83-11eb-af7c-3b68da59d087';// 'my-trusted-client';

//Your password.
        $password = 'R3furbi53D12';// 'secret';
    //   $url ="https://test.mcks.gov.tr/refurbished-devices/oauth/token?grant_type=password&username=test&password=test";
       $url ="https://kayit.mcks.gov.tr/refurbished-devices/oauth/token?grant_type=password&username=".$username."&password=".$password;
      // $url ="https://kayit.mcks.gov.tr/refurbished-devices/oauth/token";
      //  $url='https://test.mcks.gov.tr/refurbished-devices/oauth/token?grant_type=password&'.$username.'=test&password='.$password;
       // $url ="https://test.mcks.gov.tr/refurbished-devices/oauth/token ";
       // $url="https://test.mcks.gov.tr/refurbished-devices/isSuitableForRefurbish/35154004770731";
      //  $url="https://test.mcks.gov.tr/refurbished-devices/oauth/token";
        //$fields="grant_type=password&username=test&password=test";
       // echo $url ;
        $fields="";
    //    $url = "https://reqbin.com/echo/get/json";

//$url ='https://kayit.mcks.gov.tr/refurbished-devices/oauth';

$url ='https://test.mcks.gov.tr/refurbished-devices/oauth/token';
$url ='https://kayit.mcks.gov.tr/refurbished-devices/oauth/token';


        //The URL of the resource that is protected by Basic HTTP Authentication.
     // $url = 'http://site.com/protected.html';


        //$vars = ['username'=>$username,'password'=>$password,'grant_type'=>'password'];
        $vars = ['username'=>'test','password'=>'test','grant_type'=>'password'];
//Initiate cURL.
        //$ch = curl_init($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
//Specify the username and password using the CURLOPT_USERPWD option.
      //  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
         curl_setopt($ch, CURLOPT_POSTFIELDS,$vars);  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Apple-Tz: 0',
            'X-Apple-Store-Front: 143444,12'
        ));
        $response = curl_exec($ch);
        if(curl_errno($ch)){
            //If an error occured, throw an Exception.
            throw new Exception(curl_error($ch));
        }

//Print out the response.
        $result =json_decode($response);

        dd($result);


//
//        $curl = curl_init($url);
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//
//        $headers = array(
//            "Accept: application/json",
//            "Authorization: Bearer {token}",
//        );
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
////for debug only!
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//
//        $resp = curl_exec($curl);
//        curl_close($curl);
//        var_dump($resp);

        //print  $server_output ;

// Further processing ...
      //  dd($server_output);
    }

    public function imeiQuery($model_id=0,$imei_no=0)
    {



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


        if ($token && $imei_no>0){

//            /return $arr['access_token'];

         /*   $tarih=gunceltarihsaat();

            $sql="INSERT INTO
    `btk_token`
    (`Token`,
    `Tarih`)
    VALUES
    ('$token',
    '$tarih')";

            $this->query($sql);

            $s_token = "select * from btk_token order by id desc limit 1";
            $q_token  = $this->query($s_token);
            $b_token  = $q_token ->fetch();

            $access_token=$b_token->Token;*/

            $imei=$imei_no;


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
            $imei_query->imei = $imei_no;
            $imei_query->user_id = (!empty(Session::get('admin_id')))?Session::get('admin_id'):0;
            $imei_query->model_id = $model_id;
            $imei_query->result =$responseCode;
            $imei_query->token = $arr_1['access_token'];
            $imei_query->token_type = $arr_1['token_type'];
            $imei_query->scope = $arr_1['scope'];
            $imei_query->ip_address = $_SERVER['REMOTE_ADDR'];
            $imei_query->save();


         //0   return ($responseCode==1)?"ok":"no";

        //    return ['hafıza tipi güncellendi', 'success', route('memory.memorylist'), '', ''];
if($responseCode==1){
    $resultArray=['ok', $imei_query['id']];
}else{
    $resultArray=['none', $imei_query['id']];
}
        return json_encode($resultArray);

        }else{
            return 0 ;
        }
    }


    //////////CARGO//////////////////////////////

    public function cargoList()
    {

        return view('admin.cargo.list', ['cargo_companies' => CargoCompany::all()]);
    }

    public function cargoAdd (){
        return view('admin.cargo.add');
    }
    public function cargoUpdate ($cargo_id){

        return view('admin.cargo.update',['cargo'=>CargoCompany::with('branches')->find($cargo_id),'cargo_id'=>$cargo_id]);
    }

    public function cargoAddPost(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $cargo = new CargoCompany();
                $cargo->name = $request['name'];
                $cargo->person = (!empty($request['person'])) ? $request['person'] : '';
                $cargo->email = (!empty($request['email'])) ? $request['email'] : '';
                $cargo->phone_number = (!empty($request['phone_number'])) ? $request['phone_number'] : '';


                $file = $request->file('logo');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['name']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path =  "images/cargo/";
                    $th = GeneralHelper::fixName($request['name']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('logo');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);

                    $cargo->logo = "images/cargo/" . $th;
                }

                $cargo->save();


                return ['yeni şirket eklendi', 'success', route('cargo.cargo-list'), '', ''];
            });
            return json_encode($resultArray);

        }


    }

    public function cargoUpdatePost(Request $request)
    {
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {

                $cargo = CargoCompany::find($request['id']);
                $cargo->name = $request['name'];
                $cargo->person = (!empty($request['person'])) ? $request['person'] : '';
                $cargo->email = (!empty($request['email'])) ? $request['email'] : '';
                $cargo->phone_number = (!empty($request['phone_number'])) ? $request['phone_number'] : '';


                $file = $request->file('logo');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['name']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path =  "images/cargo/";
                    $th = GeneralHelper::fixName($request['name']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('logo');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(100, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);

                    $cargo->logo = "images/cargo/" . $th;
                }

                $cargo->save();


                return ['şirket Güncellendi', 'success', route('cargo.cargo-list'), '', ''];
            });
            return json_encode($resultArray);

        }


    }

    public function addBranch($company_id){

        return view('admin.cargo.add_branch',['company'=>CargoCompany::find($company_id),'cities'=>City::all()]);
    }
    public function addBranchPost(Request $request){


        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $branch =  new CargoCompanyBranch();
                $branch->cargo_company_id = $request['company_id'];
                $branch->title = $request['title'];
                $branch->person = (!empty($request['person']))?$request['person']:'';
                $branch->address = $request['address'];
                $branch->city_id = $request['city_id'];
                if(!empty($request['town_id'])){
                    $branch->town_id=$request['town_id'];
                    if(!empty($request['district_id'])){
                        $branch->district_id=$request['district_id'];
                        if(!empty($request['neighborhood_id'])){
                            $branch->neighborhood_id=$request['neighborhood_id'];
                        }else{
                            $branch->neighborhood_id=0;
                        }
                    }else{
                        $branch->district_id=0;
                        $branch->neighborhood_id=0;
                    }

                }else{
                    $branch->town_id=0;
                    $branch->district_id=0;
                    $branch->neighborhood_id=0;
                }

                $branch->phone_number=(!empty($request['phone_number']))?$request['phone_number']:'';
                $branch->phone_number_2=(!empty($request['phone_number_2']))?$request['phone_number_2']:'';
                $branch->active=(!empty($request['status2']))?1:0;


                $branch->save();
                return ['Şube Eklendi', 'success', route('cargo.cargo-update',[$request['company_id']]), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateBranch($branch_id){
        return view('admin.cargo.update_branch',['branch'=>CargoCompanyBranch::find($branch_id),'cities'=>City::all()]);
    }

    public function updateBranchPost(Request $request){


        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $branch =   CargoCompanyBranch::find($request['id']);
                //$branch->cargo_company_id = $request['company_id'];
                $branch->title = $request['title'];
                $branch->person = (!empty($request['person']))?$request['person']:'';
                $branch->address = $request['address'];
                $branch->city_id = $request['city_id'];
                if(!empty($request['town_id'])){
                    $branch->town_id=$request['town_id'];
                    if(!empty($request['district_id'])){
                        $branch->district_id=$request['district_id'];
                        if(!empty($request['neighborhood_id'])){
                            $branch->neighborhood_id=$request['neighborhood_id'];
                        }else{
                            $branch->neighborhood_id=0;
                        }
                    }else{
                        $branch->district_id=0;
                        $branch->neighborhood_id=0;
                    }

                }else{
                    $branch->town_id=0;
                    $branch->district_id=0;
                    $branch->neighborhood_id=0;
                }

                $branch->phone_number=(!empty($request['phone_number']))?$request['phone_number']:'';
                $branch->phone_number_2=(!empty($request['phone_number_2']))?$request['phone_number_2']:'';
                $branch->active=(!empty($request['status2']))?1:0;


                $branch->save();
                return ['Şube Güncellendi', 'success', route('cargo.cargo-update',[$request['company_id']]), '', ''];
            });
            return json_encode($resultArray);

        }
    }



    public function cargoCompanies(Request $request )
    {


        if ($request->header('x-api-key') == $this->generateKey()) {
            $status_code = 200;
            $resultArray['status'] = true;
            $resultArray['data']= ['cargo_companies'=>CargoCompany::where('status','=',1)->get()];


        } else {
            $resultArray['status'] = false;
            // $resultArray['status_code'] = 406;
            $status_code=406;
            $resultArray['errors'] =['msg'=>'hatalı anahtar'] ;
        }


        return response()->json($resultArray,$status_code);
        // return json_encode($resultArray);
    }

    //////////CARGO//////////////////////////////

    public function serviceAddressesList()
    {

        return view('admin.service_addresses', ['service_addresses' => ServiceAddress::all()]);
    }

    public function addServiceAddress(){
        return view('admin.add_service_address',['cities'=>City::all()]);
    }

    public function updateServiceAddress($address_id){
        return view('admin.update_service_address',['cities'=>City::all(),'address'=>ServiceAddress::find($address_id)]);
    }

     public function addServiceAddressPost(Request $request){


        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $branch =  new ServiceAddress();

                $branch->title = $request['title'];
                $branch->name_surname = (!empty($request['person']))?$request['person']:'';
                $branch->address = $request['address'];
                $branch->city_id = $request['city_id'];
                if(!empty($request['town_id'])){
                    $branch->town_id=$request['town_id'];
                    if(!empty($request['district_id'])){
                        $branch->district_id=$request['district_id'];
                        if(!empty($request['neighborhood_id'])){
                            $branch->neighborhood_id=$request['neighborhood_id'];
                        }else{
                            $branch->neighborhood_id=0;
                        }
                    }else{
                        $branch->district_id=0;
                        $branch->neighborhood_id=0;
                    }

                }else{
                    $branch->town_id=0;
                    $branch->district_id=0;
                    $branch->neighborhood_id=0;
                }

                $branch->phone_number=(!empty($request['phone_number']))?$request['phone_number']:'';
                $branch->phone_number_2=(!empty($request['phone_number_2']))?$request['phone_number_2']:'';
                $branch->active=(!empty($request['status2']))?1:0;


                $branch->save();
                return ['Adres Eklendi', 'success', route('service-addresses-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

     public function updateServiceAddressPost(Request $request){


        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $branch =  ServiceAddress::find($request['id']);

                $branch->title = $request['title'];
                $branch->name_surname = (!empty($request['person']))?$request['person']:'';
                $branch->address = $request['address'];
                $branch->city_id = $request['city_id'];
                if(!empty($request['town_id'])){
                    $branch->town_id=$request['town_id'];
                    if(!empty($request['district_id'])){
                        $branch->district_id=$request['district_id'];
                        if(!empty($request['neighborhood_id'])){
                            $branch->neighborhood_id=$request['neighborhood_id'];
                        }else{
                            $branch->neighborhood_id=0;
                        }
                    }else{
                        $branch->district_id=0;
                        $branch->neighborhood_id=0;
                    }

                }else{
                    $branch->town_id=0;
                    $branch->district_id=0;
                    $branch->neighborhood_id=0;
                }

                $branch->phone_number=(!empty($request['phone_number']))?$request['phone_number']:'';
                $branch->phone_number_2=(!empty($request['phone_number_2']))?$request['phone_number_2']:'';
                $branch->active=(!empty($request['status2']))?1:0;


                $branch->save();
                return ['Adres Güncellendi', 'success', route('service-addresses-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

}
