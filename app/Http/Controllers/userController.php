<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    //
    public function signUp(Request $request)
    {
        $user = new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->mobile=$request->mobile;
        $user->facebook=$request->facebook;
        $user->password=bcrypt($request->password);
        $user->save();
        if ($user){
            
            return response($user,201);
        }
        else{
            return response([
                'message'=>'Wronge In Register'
            ],401);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response([
            'message'=>'Logged out'
        ],201);
    }
    public function login(Request $request)
    {
       
        $user = User::where('email',$request->email)->get()->first();
        
        if(!$user )
        {
            return response([
                'message'=>'Email is not Exist'
            ],401);
        }
        if(!Hash::check($request->password,$user->password)){
            return response([
                'message'=>'Wronge Password'
            ],401);
        }
        $token =$user->createToken('ProgramLanguage')->plainTextToken;

        $response= [
            'user' => $user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function myProduct()
    {
        $user=Auth::user();
        return $user->getMyProduct;
    }
    
}
