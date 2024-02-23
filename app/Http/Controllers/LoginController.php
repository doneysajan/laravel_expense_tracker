<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model if not already imported
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
                $API = $user->createToken('API-Token', ['server:update'])->plainTextToken;
                $profileImgUrl = $user->profile ? url('uploads/profile/' . $user->profile) : null; // Construct profile image URL
                $response = [
                    'success' => true,
                    'user_id' => $user->id,
                    'fullname' => $user->fullname,
                    'API_Token' => $API,
                    'profileImgUrl' => $profileImgUrl, // Include profileImgUrl in the response
                    'message' => 'Logged in'
                ];
                return response()->json($response, 200);
            } else {
                $response = ['success' => false, 'message' => 'Incorrect email or password. Please try again.'];
                return response()->json($response, 401);
            }
        }

        $response = ['success' => false, 'message' => 'Invalid request.'];
        return response()->json($response, 400);
    }

    
    public function getUser(Request $request)
    {
        $userData=User::where('id',$request->user()->id)->first();
        
        return response()->json($userData, 200);
    }
    
    public function updateUser(Request $request)
    {
        $user = $request->user();
        //$userData=User::where('id',$request->user()->id);
        if (!$user) {
            $response = ['success' => false, 'message' => 'User not found'];
            return response()->json($response, 404);
        }
        $request->validate([
            'profile' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/profile/', $filename);
        }

        // Update the user data
        $user->fullname = $request->input('fullname', $user->fullname);
        $user->email = $request->input('email', $user->email);
        $user->phone = $request->input('phone', $user->phone);
        $user->address = $request->input('address', $user->address);
        $user->currency = $request->input('currency', $user->currency);
        $user->budget = $request->input('monthlybudget', $user->monthlybudget);
        $user->profile= $filename ?? null;

        if ($user->save()) {
            $response = ['success' => true, 'message' => 'User updated successfully', 'user' => $user];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'message' => 'Error: Unable to update user'];
            return response()->json($response, 500);
        }
    }

    //REMOVE PROFILEIMAGE
    public function removeProfileImage(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            $response = ['success' => false, 'message' => 'User not found'];
            return response()->json($response, 404);
        }

        // Delete the profile picture file from the server
        if ($user->profile) {
            $filePath = public_path('uploads/profile/' . $user->profile);
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Remove the profile picture reference from the database
        $user->profile = null;

        if ($user->save()) {
            $response = ['success' => true, 'message' => 'Profile picture removed successfully'];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'message' => 'Error: Unable to remove profile picture'];
            return response()->json($response, 500);
        }
    }

    //CHANGE PASSWORD
    public function changePassword(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            $response = ['success' => false, 'message' => 'User not found'];
            return response()->json($response, 404);
        }

        if (!Hash::check($request->input('currentPassword'), $user->password)) {
            $response = ['success' => false, 'message' => 'Current password is incorrect'];
            return response()->json($response, 400);
        }

        // Validate the new password
        // $request->validate([
        //     'newPassword' => 'required|string|min:8|confirmed',
        // ]);

        $user->password = bcrypt($request->input('newPassword'));

        if ($user->save()) {
            $response = ['success' => true, 'message' => 'Password changed successfully'];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'message' => 'Error: Unable to change password'];
            return response()->json($response, 500);
        }
    }

    //DEACTIVATE ACCOUNT
    public function deactivateAccount(Request $request)
    {
        $user = $request->user();
        User::where('id', $user->id)->delete(); 
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Account deleted successfully']);
    }

    //LOGOUT
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); 
        return response()->json(['message' => 'Logged out successfully']);
    }
}
