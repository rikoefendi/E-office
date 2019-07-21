<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;

class UserController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

    public function index(Request $request)
    {
      //get all users data with paginate except user is Authentication
      $users = User::where('id', '!=', \Auth::user()->id);
      if($request->level){
        $users->where('level', $request->level);
      }
      $users = $users->paginate(10);
      //return view with users data
      return view('user.index', compact('users'));
    }

    public function create()
    {
      return view('user.create');
    }

    public function store(Request $request)
    {
      //validation of input
      $request->validate([
        'email' => 'required|email|unique:users',
        'name' => 'required|min:8|max:30',
        'level' => 'required|max:1',
        'status' => 'required|max:1',
        'password' => 'required|confirmed|min:8',
        'avatar' => 'required|mimes:jpg,jpeg,png|max:1024'
      ]);

      //if validation success running code in bottom

      //input file avatar
      $file = $request->file('avatar');
      //get name avatar
      $avatarName = $file->getClientOriginalName();

      //get input only email,name,level,status return array
      $inputs = $request->only('email', 'name', 'level', 'status');
      //hashing password and to $inputs array
      $inputs['password'] = \Hash::make($request->password);
      //store/create user to database
      $user = User::create($inputs);
      //if create user is success save avatar to storage and path to table user
      if($user){
        //get content avatar from input
        $avatarFile = file_get_contents($file->getPathName());
        //path file
         $avatarPath = '/public/avatars/'.$avatarName;
        //save to Storage
        $saved = Storage::put($avatarPath, $avatarFile);

        if($saved){
          //save path to table user
          $user->avatar = str_replace('/public', '', '/storage'.$avatarPath);
          $user->save();
        }

        //return back with message success
        return back()->with('status', 'Berhasil menambahkan '.$user->name);

      }

    }

    public function edit(Request $request, $id)
    {
      //get user data to edit by id

      $user = User::findOrFail($id);

      return view('user.edit', compact('user'));
    }

    public function update(Request $request)
    {
      $request->validate([
        'email' => 'required|email',
        'name' => 'required|min:8|max:30',
        'level' => 'required|max:1',
        'status' => 'required|max:1',
        // 'password' => 'required|confirmed|min:8',
        // 'avatar' => 'required|mimes:jpg,jpeg,png|max:1024'
      ]);

      //if validation success running code in bottom

      //get input only email,name,level,status return array
      $inputs = $request->only('email', 'name', 'level', 'status');
      //if user changed the password
      if($request->password){
        $request->validate(['password' => 'required|confirmed|min:8']);
        //hashing password and to $inputs array
        $inputs['password'] = \Hash::make($request->password);
      }
      //if user change the avatar
      if($request->file('avatar')){
        $request->validate([ 'avatar' => 'required|mimes:jpg,jpeg,png|max:1024']);
        //input file avatar
        $file = $request->file('avatar');
        //get name avatar
        $avatarName = $file->getClientOriginalName();
        //get content avatar from input
        $avatarFile = file_get_contents($file->getPathName());
        //path file
         $avatarPath = '/public/avatars/'.$avatarName;
        //save to Storage
        $saved = Storage::put($avatarPath, $avatarFile);
        if($saved){
          //save path to table user
          $inputs['avatar'] = str_replace('/public', '', '/storage'.$avatarPath);
        }

        //return back with message success
      //find user ro update by id
      $user = User::findOrFail($request->id);
      //store/update user to database
      $user->update($inputs);

      }
      return redirect('/users')->with('status', 'Berhasil merubah '.$request->name);
    }

    public function destroy($id)
    {
      //get user by id

      $user = User::findOrFail($id);
      $name = $user->name;

      //delete user
      if($user->delete()){
        return back()->with('status', 'Berhasil menghapus '.$name);
      }
    }
}
