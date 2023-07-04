<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //create a new user
    public function createUser(Request $request){
        try{
            /// validate the data
            $validator = Validator::make($request->all(),[
                'avatar' => 'required',
                'type' => 'required',
                'open_id' => 'required',
                'name' => 'required',
                'email' => 'required',
               // 'password' => 'required | min:6',
            ]);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'msg' => 'validation failed',
                    'errors' => $validator->errors()
                ], 401);
            }

            /// validated will have all user fields values
            /// we can the user in DB
            $validated = $validator->validate(); /// save all validated values

            $map = [];
            $map['type'] = $validated['type']; ///email, phone, google, facebook, apple
            $map['open_id'] = $validated['open_id'];

            $user = User::where($map)->first();

            //check whether user has logged in or not.
            /// empty means user doesnt exist, then save user in database for the first time
            if(empty($user->id)){
                // this certain user has never been in our database
                // our job is to assign the user in the database
                // this token is user id
                $validated['token'] = md5(uniqid().rand(10000, 99999));
                /// user first time created
                $validated['created_at'] = Carbon::now();
                //encrypt password
                //$validated['password'] = Hash::make($validated['password']);

                /// return the id of the row after saving.
                $userID = User::insertGetId($validated);

                /// total user information after saving in the database.
                /// user information will be displayed in the UI
                $userInfo = User::where('id', '=', $userID)->first();

                /// access token for validation.
                $accessToken = $userInfo->createToken(uniqid())->plainTextToken;

                $userInfo->access_token = $accessToken;/// save the access token
                User::where('id', '=', $userID)->update(['access_token' => $accessToken]);
                return response()->json([
                    'code' => 200,
                    'msg' => 'user created successfully',
                    'data' => $userInfo
                ], 200);
            }

            /// else, user already exists, then login and create a new access token.
            $accessToken = $user->createToken(uniqid())->plainTextToken;
            $user->access_token = $accessToken;
            User::where('open_id', '=', $validated['open_id'])->update(['access_token' => $accessToken]);

            return response()->json([
                'code' => 200,
                'msg' => 'user logged successfully',
                'data' => $user,
            ], 200);
        }
        catch(\Throwable $e){
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ], 500);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
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
