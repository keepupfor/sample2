<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
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
        $rules=[
            'name'=>'required|max:25',
            'email'=>'email|required|unique:users',
            'password'=>'required|min:6|confirmed'
        ];
        $this->validate($request, $rules);
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
        // Auth::login($user);
        session()->flash('success','欢迎您的到来！');
        return redirect()->route('users.show',[$user]);
    }
}
