<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Str;


class AuthController extends Controller
{

    public function login(Request $request){
     
        $request->validate([
            'national_code'=>'required|string',
            'mobile'=>'required|string',
        ]);
     
    $user = User::where('national_code', $request->national_code)->where('mobile', $request->mobile)->first();
    
    
    if ($user) {
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
                'message' => 'success',
                'user' => $user,
                'token'=>$token,
        ]);
    } 
    else {
        return response()->json([
            'message' => 'لطفا ثبت نام کنید',
        ], 401);
    }
    
    }

    public function signin(Request $request){
        $request->validate([
            'type' =>'required|boolean',
            'name'=>'required|string',
            'email'=>'Nullable|string',
            'mobile'=>'required|string',
            'national_code'=>'required|string',
        ]);
       
        $user = User::create([
            'type' => $request->type,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'national_code' => $request->national_code,
            'uuid' =>Str::uuid(),
            'is_admin' => false,
        ]);
        return response()->json([
            'message' => 'success',
            'user' => $user,
            'token'=>$user->createToken('token')->plainTextToken,
        ] , 201);
    }
}
