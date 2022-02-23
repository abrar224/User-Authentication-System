<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;

class CustomAuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    public function registration(){
        return view('auth.registration');
    }
    public function registerUser(Request $request){
        $request->validate([
            'name'=>'required',
            'username'=>'required|unique:users',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:5|max:20'
        ]);
        $user = new User();
        $user->full_name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $res = $user->save();
        if($res){
            return back()->with('success','You have registered successfully');
        }
        else{
            return back()->with('fail','Something wrong');
        }
    }
    public function loginUser(Request $request){
        $request->validate([
            'username'=>'required',
            'password'=>'required|min:5|max:20'
        ]);
        $user = User::where('username','=',$request->username)->first();
        if($user){
            if(Hash::check($request->password,$user->password)){
                $request->session()->put('loginuser',$user->username);
                return redirect('homepage');
            }
            else{
                return back()->with('fail','Wrong Password');
            }
        }
        else{
            return back()->with('fail','Invalid Username');
        }
    }
    public function homepage(){
        $data = array();
        if(Session::has('loginuser')){
            $data = User::where('username','=',Session::get('loginuser'))->first();
        }
        return view('homepage');
    }
    public function logout(){
        if(Session::has('loginuser')){
            Session::pull('loginuser');
            return redirect('login');
        }
    }
}
