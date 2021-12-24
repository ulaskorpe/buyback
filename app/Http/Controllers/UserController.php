<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helpers\GeneralHelper;
use App\Models\Color;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    use ApiTrait;



    public function __construct()
    {
    }


    public function userList(){

        return view('admin.users.list', ['users' => User::with('group')->get()]);


    }

    public function create(){
        return view('admin.users.create',['groups'=>UserGroup::all()]);
    }
    public function groupCreate(){
        return view('admin.users.group_create' );
    }
    public function groupUpdate ($id){
        return view('admin.users.group_update',['group'=>UserGroup::find($id),'group_id'=>$id] );
    }
    public function update ($id){
        return view('admin.users.update',['groups'=>UserGroup::all(),'user'=>User::find($id),'user_id'=>$id]);
    }

    public function generatePw(){
        return $this->randomPassword(8,1);
    }

    public function checkEmail($email,$id=0){
        if($this->validateEmail($email)){
            if($this->checkUnique('email','users',$email,$id)){
                    return "Bu eposta kullanılmakta";
            }else{
                return "ok";
            }
        }else{
            return "Geçersiz eposta adresi";
        }
    }

    public function createPost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $user = new  User();
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->phone = (!empty($request['phone'])) ? $request['phone']:"";
                $user->password = md5($request['password']);
                $user->group_id = $request['group_id'];
                $user->sudo = (!empty($request['sudo']))?1:0;
                $user->status = (!empty($request['status']))?1:0;
                $user->save();

                return ['Kullanıcı Eklendi', 'success', route('users.user-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function groupCreatePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $g = new UserGroup();
                $g->name=$request['name'];
                $g->users = (!empty($request['users'])) ?1:0;
                $g->buybacks = (!empty($request['buybacks'])) ?1:0;
                $g->system = (!empty($request['system'])) ?1:0;
                $g->site = (!empty($request['site'])) ?1:0;

                $g->status = (!empty($request['status'])) ?1:0;
                $g->save();

                return ['Grup Eklendi', 'success', route('users.user-groups'), '', ''];
            });
            return json_encode($resultArray);

        }
    }
    public function groupUpdatePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $g = UserGroup::find($request['id']);
                $g->name=$request['name'];
                $g->users = (!empty($request['users'])) ?1:0;
                $g->buybacks = (!empty($request['buybacks'])) ?1:0;
                $g->system = (!empty($request['system'])) ?1:0;
                $g->site = (!empty($request['site'])) ?1:0;
                $g->status = (!empty($request['status'])) ?1:0;
                $g->save();

                return ['Grup Güncellendi', 'success', route('users.user-groups'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function updatePost(Request $request){
        if ($request->isMethod('post')) {
            $messages = [];
            $rules = [

            ];
            $this->validate($request, $rules, $messages);
            $resultArray = DB::transaction(function () use ($request) {
                $user = User::find($request['id']);
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->phone = (!empty($request['phone'])) ? $request['phone']:"";
                $user->group_id = $request['group_id'];
                $user->sudo = (!empty($request['sudo']))?1:0;
                $user->status = (!empty($request['status']))?1:0;
                $user->save();

                return ['Kullanıcı Güncellendi', 'success', route('users.user-list'), '', ''];
            });
            return json_encode($resultArray);

        }
    }

    public function userGroups(){
        return view('admin.users.groups_list',['groups'=>UserGroup::all()]);
    }
}
