<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model if not already imported
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        // Get data from the POST request
        $fullname = $request->input('fullname');
        $password = $request->input('password');
        $email = $request->input('email');
        $mobile = $request->input('mobile');

        // Hashing
        $hashedpassword = bcrypt($password); // Laravel helper function for password hashing

        // Perform the insertion into the 'users' table
        $user = new User();
        $user->fullname = $fullname;
        $user->email = $email;
        $user->phone = $mobile;
        $user->password = $hashedpassword;

        if ($user->save()) {
            $response = ['success' => true, 'message' => 'User registered successfully'];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'message' => 'Error: Unable to register user'];
            return response()->json($response, 500);
        }
    }


public function login(Request $request)
{
    if ($request->isMethod('post')) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $API=$user->createToken('API-Token', ['server:update'])->plainTextToken;
            $response = ['success' => true,'user_id' => $user->id, 'fullname' => $user['fullname'], 'API_Token' => $API,'message' => 'Logged in'];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'message' => 'Incorrect email or password. Please try again.'];
            return response()->json($response, 401);
        }
    }

    $response = ['success' => false, 'message' => 'Invalid request.'];
    return response()->json($response, 400);
}
}


    // public function login(Request $request)
    // {
    //     if ($request->isMethod('post')) 
    //     {
            
    //         $username=$request->input('email');
    //         $password=$request->input('password');

    //         $user=User::where('email', $username)->first();

    //         if($user) {
    //             if (password_verify($password, $user->password)) 
    //             {
    //                 $response=['success' => true, 'message'=>'Logged in'];
    //                 return response()->json($response, 200);
    //             } else {
    //                 $response=['success' => false, 'message'=>'Incorrect password. Please try again.'];
    //                 return response()->json($response, 401);
    //             }
    //         } 
    //         else{
    //             $response=['success' => false, 'message'=>'User not found. Please check your username.'];
    //             return response()->json($response, 400);
    //         }
    //     }
    //}

