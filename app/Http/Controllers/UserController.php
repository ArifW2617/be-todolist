<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request) {
        $messages = [
            'password.regex' => 'Password harus mengandung minimal satu huruf besar dan simbol unik',
        ];

        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:6|regex:/[A-Z]/|regex:/[@$!%*#?&]/",
        ], $messages);

        if($validator->fails()) {
            return response()->json([
                "message" => "Invalid Input",
                "errors" => $validator->errors()
            ], 400);
        }

        $user = User::create([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => $request->input('password'),
        ]);

        return response()->json([
            "message" => "Success created account",
        ],200);
    }

    public function login(Request $request) {
        $credentials = [
            "email" => $request->input('email'),
            "password" => $request->input('password'),
        ];

        if(Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken("auth_token")->plainTextToken;

            return response()->json([
                "message" => "Login Successfull",
                "data" => $user,
                "token" => $token
            ], 200);
        }

        return response()->json([
            "status" => "error",
            "message" => "Invalid email or password" 
        ], 401);
    }
}
