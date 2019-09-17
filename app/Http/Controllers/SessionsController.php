<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class SessionsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
       $credentials = $this->validate($request, [
           'email' => 'required|email|max:255',
           'password' => 'required'
       ]);

       if (Auth::attempt($credentials, $request->has('remember'))) {
           if(Auth::user()->activated) {
               session()->flash('success', '欢迎回来！');
               //session()->save();    ///////
               return redirect()->intended(route('users.show', [Auth::user()]));
           } else {
               Auth::logout();
               session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
               //session()->save();    ////////
               return redirect('/');
           }
       } else {
           session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
           //session()->save();            ///////
           return redirect()->back();
       }
    }

    public function destroy()
    {
        echo "aabb1<br />";
        Auth::logout();
        echo "aabb2<br />";
        session()->flash('success', '您已成功退出！');
        echo "aabb3<br />";
        //session()->save();      /////////
        //echo "aabb4<br />";
        return redirect('login');

    }
}