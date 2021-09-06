<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Model\User;
use App\Model\Notification;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller {
    private $authenticator;
    public $stausCode = 200;
    public function __construct() {
    }

      public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
        $token = $user->createToken('TutsForWeb')->accessToken;
 
        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request) {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            // 'user_type' => ['user']
        ];

        $userType = array_keys(config('site_vars.userType'));

        $user = User::where('username', $request->username)->whereIN('user_type', $userType)->first();

        if(!$user){
            $response = [
                'status'=>false,
                'message'=>'Invalid username and password.'
            ];
            return response()->json($response, $this->stausCode);
        }

        if($user->status=='inactive'){
            $response = [
                'status'=>false,
                'message'=>'Your account has been disabled. Please contact to our support team.'
            ];
            return response()->json($response, $this->stausCode);
        }

        if(! Hash::check($request->password, $user->password, [])){
            $response = [
                'status'=>false,
                'message'=>'Invalid username and password.'
            ];
            return response()->json($response, $this->stausCode);
        }

        if (! $token = JWTAuth::attempt($credentials)) {
            $response = [
                'status'=>false,
                'message'=>'Invalid username and password.'
            ];
            return response()->json($response, $this->stausCode);
        }

        $response = [
            'status'=>true,
            'message'=>'Login successfully.',
            'token' => $token
        ];
        return response()->json($response, $this->stausCode);
    }


    public function adminLogin(Request $request) {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $adminType = array_keys(config('site_vars.adminType'));

        $user = User::where('email', $request->email)->whereIn('user_type', $adminType)->first();

        if(!$user){
            $response = [
                'status'=>false,
                'message'=>'Invalid username and password.'
            ];
            return response()->json($response, $this->stausCode);
        }

        if($user->status=='inactive'){
            $response = [
                'status'=>false,
                'message'=>'Your account has been disabled. Please contact to our support team.'
            ];
            return response()->json($response, $this->stausCode);
        }

        if(! Hash::check($request->password, $user->password, [])){
            $response = [
                'status'=>false,
                'message'=>'Invalid username and password.'
            ];
            return response()->json($response, $this->stausCode);
        }

        if (! $token = JWTAuth::attempt($credentials)) {
            $response = [
                'status'=>false,
                'message'=>'Invalid username and password.'
            ];
            return response()->json($response, $this->stausCode);
        }

        $response = [
            'status'=>true,
            'message'=>'Login successfully.',
            'token' => $token
        ];

        return response()->json($response, $this->stausCode);           
    }

    public function details() {
        return response()->json([
            'status'=>true,
            'record' => auth()->user()
        ], 200);
    }

     public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

     public function SecurityNotifications(Request $request) {

        $notilist = Notification::where('to_id', $request->guardID)->get();

        if($notilist) 
            $response = [
                'status'=>true,
                'record'=>$notilist
            ];
        else 
            $response = [
                'status'=>false,
                'record'=>'No record found'
            ];
            return response()->json($response, $this->stausCode);
    }
}
