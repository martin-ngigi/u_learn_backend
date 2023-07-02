<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //create a new user
    public function createUser(Request $request){
        try{
            /// validate the data
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required | email| unique:users,email',
                'password' => 'required',
            ]);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validator->errors()
                ], 401);
            }

            // create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            return response()->json([
                'status' => true,
                'message' => 'user created successfully',
                'token' => $user->createToken("API_TOKEN")->plainTextToken,
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function loginUser(Request $request){
        try{
            /// validate data
            $validateUser = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            /// email validation failure
            if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    'status' => false,
                    'message' => 'email and password do not match our records',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'logged in successfully',
                'token' => $user->createToken("API_TOKEN")->plainTextToken,
            ], 200);
        }
        catch(\Exception $e){
            
        }
    }
}
