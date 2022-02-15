<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Article;
use App\Models\ArticlePart;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ArticleController extends Controller
{
    use ApiTrait;
    public  function articleList(){

        return view('admin.site.article.list',['articles'=>Article::all()]);
    }

    public function createArticle(){

        $last = Article::select('id')->orderBy('id','desc')->first();

        $code =(!empty($last['id'])) ? "AR".($last['id']+1) : "AR1";

        return view('admin.site.article.create',['code'=>$code] );
    }

    public function createArticlePart($article_id){


        return view('admin.site.article.create_part',['article'=>Article::find($article_id),'count'=>(ArticlePart::where('article_id','=',$article_id)->count()+1)] );
    }

    public function updateArticlePart($part_id){

    $part= ArticlePart::find($part_id);
        return view('admin.site.article.update_part',['part'=>$part,'count'=>(ArticlePart::where('article_id','=',$part['article_id'])->count())] );
    }

    public function createArticlePost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $article= new Article();
                $article->title = $request['title'];
                $article->code = $request['code'];
                $article->prologue = $request['prologue'];

                $article->status = (!empty($request['status']))?1:0;
                $article->save();
                return ['yeni YAZI eklendi', 'success', route('site.article-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateArticlePost(Request $request){
        if ($request->isMethod('post')) {


            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $article= Article::find($request['id']);

               $article->title = $request['title'];
                $article->prologue = $request['prologue'];

                $article->status = (!empty($request['status']))?1:0;
                $article->save();
                return ['YAZI güncellendi', 'success', route('site.article-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateArticle($id){
//$a =  Article::with('parts')->find($id);

        return view('admin.site.article.update',['article'=>Article::with('parts')->find($id),'article_id'=>$id ]);
    }

    public function createArticlePartPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $article_part= new ArticlePart();
                $article_part->title = $request['title2'];
                $article_part->article_id = $request['article_id'];
                $article_part->paragraph=(!empty($request['descr'])) ? $request['descr']:'';
                $article_part->status = (!empty($request['status']))?1:0;
                $article_part->count = $request['count'];
                $file = $request->file('image');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title2']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/articles");
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('image');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $article_part->image = "images/articles/" . $filename;
                    $article_part->thumb = "images/articles/" . $th;
                }



                $article_part->save();


                ArticlePart::where('article_id','=',$request['article_id'])->where('count','>=',$request['count'])
                    ->where('id','<>',$article_part['id'])
                    ->increment('count');
                return ['yeni paragraf eklendi', 'success', route('site.update-article',$request['article_id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updateArticlePartPost(Request $request){
        if ($request->isMethod('post')) {

            //     return $request['invoice_date'];

            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $article_part= ArticlePart::find($request['id']);
                $old=$article_part['count'];
                $article_part->title = $request['title2'];
                $article_part->paragraph=(!empty($request['descr'])) ? $request['descr']:'';
                $article_part->status = (!empty($request['status']))?1:0;
                $article_part->count = $request['count'];
                $file = $request->file('image');
                if (!empty($file)) {

                    $filename = GeneralHelper::fixName($request['title2']) . "_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());
                    $path = public_path("images/articles");
                    $th = GeneralHelper::fixName($request['title']) . "TH_"
                        . date('YmdHis') . "." . GeneralHelper::findExtension($file->getClientOriginalName());

                    $file = $request->file('image');
                    $img = Image::make($file->getRealPath());
                    $img->save($path . '/' . $filename);
                    $img->resize(150, 150, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path . '/' . $th);
                    $article_part->image = "images/articles/" . $filename;
                    $article_part->thumb = "images/articles/" . $th;
                }
                $article_part->save();

                if($request['count']!=$old){
                    if($request['count']>$old){
                        //$this->makeTmp(">".$old."->".$request['count'],$request['article_id'].":".$article_part['id']);
                        ArticlePart::where('article_id','=',$request['article_id'])
                            ->where('id','<>',$article_part['id'])
                            ->where('count','>=',$old)
                            ->where('count','<=',$request['count'])
                            ->decrement('count');
                    }else{
                      //  $this->makeTmp("<".$old."->".$request['count'],$request['article_id'].":".$article_part['id']);
                        ArticlePart::where('article_id','=',$request['article_id'])
                            ->where('id','<>',$article_part['id'])
                            ->where('count','<=',$old)
                            ->where('count','>=',$request['count'])
                            ->increment('count');
                    }
                }
                return ['Paragraf güncellendi', 'success', route('site.update-article',$request['article_id']), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function deletePart($part_id){
        $part=ArticlePart::find($part_id);
        ArticlePart::where('article_id','=',$part['article_id'])->where('count','>=',$part['count'])
            ->where('id','<>',$part['id'])
            ->decrement('count');
            $part->delete();
        return "Paragraf silindi";
    }
}
