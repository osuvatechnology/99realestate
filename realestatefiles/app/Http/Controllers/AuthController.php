<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Article;
use Hash;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Hash as FacadesHash;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        $request->validate([

            'emailid' => 'required|email',
            'passwd' =>'required|min:8|max:12',
        ]);
        $article= Article::where('emailid','=',$request->emailid)->first();
        if($article)
        {
            if(Hash::check($request->passwd, $article->password1))
            {
               $request->session()->put('loginId',$article->id);
               return redirect('dashboard');
            }else{
                return back()->with('fail','This Password is incorrect!');

            }
        }else{
            return back()->with('fail','This email id is not Registered!');

        }
    }

    public function create(Request $request)
    {
       $request->validate([

        'form_name'=>'required',
        'form_email'=>'required|email|unique:articles,emailid',
        'form_pass'=>'required|min:8|max:12',
        'form_phone'=>'required|numeric',

       ]);
       $article= new Article();

       $article->name = $request->form_name;
       $article->emailid = $request->form_email;
       $article->password1 = Hash::make($request->form_pass);
       $article->contactno = $request->form_phone;
       $res=$article->save();

       if($res){

        return back()->with('success','You have Registered successfully');
       }else{

        return back()->with('fail','Something wrong');
       }
    }

    public function logout(){
        if(Session::has('loginId')){
            Session::pull('loginId');
            return redirect('login');
        }
    }

    public function login(){
        return view('FrontEnd.login');
    }

    public function register(){
        return view('FrontEnd.register');
    }
    public function dashboard (){
        return view('FrontEnd.dashboard ');
    }
}
