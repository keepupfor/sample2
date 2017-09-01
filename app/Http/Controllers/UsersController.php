<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',[
            'except'=>['create','show','store','index']
        ]);
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }
    public function index()
    {
        $users=User::paginate(20);
        return view('users.index',compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:25',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
        $this->validate($request, $rules);
        $user = User::create(array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ));
        Auth::login($user);
        session()->flash('success', '欢迎您的到来！');
        return redirect()->route('users.show', [$user]);
    }
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }
    public function update(User $user,Request $request)
    {
        $rules = [
            'name' => 'required|max:25',
            'password' => 'nullable|min:6|confirmed'
        ];
        $this->validate($request, $rules);
        $this->authorize('update',$user);
        $data=[];
        $data['name']=$request->name;
        if ($request->password){
            $data['password']=bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success','修改个人信息成功');
        return redirect()->route('users.show',$user->id);
    }
    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','删除成功!');
        return redirect()->route('users.index');
    }
}
