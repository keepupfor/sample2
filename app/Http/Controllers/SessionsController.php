<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email|max:255',
            'password'=>'required|between:6,15'
        ]);
      $condition=['email'=>$request->email,'password'=>$request->password];
      if (Auth::attempt($condition,$request->has('remember'))){
          session()->flash('success','欢迎回来');
          return redirect()->route('users.show',[Auth::user()]);
      }
      else
      {
          session()->flash('danger','登录失败,邮箱或密码不正确');
          return redirect()->back();
      }
    }

    public function destroy()
    {
      Auth::logout();
      session()->flash('warning','您已退出!');
      return redirect()->route('login');
    }
}
