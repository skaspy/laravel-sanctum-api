<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',  // unique on users (table) in email (column)
            'password'=>'required|string|confirmed' // confirmed means you have to fill in the password twice
        ]);

        $user = User::create([  //calls the User-Mddel to create a new user for our database
            'name'=> $fields['name'],  // left is variable, right is input value
            'email'=> $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=> $user,
            'token'=> $token
        ];

        return response($response, 201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email']) -> first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)){  // check input against DB-password
            return response([
                'message' => 'Bad credentials.'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=> $user,
            'token'=> $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
