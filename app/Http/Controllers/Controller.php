<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

use App\Model\User;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $perPage;

    public function __construct() {
        $this->perPage = config('site_vars.perPage');
    }

    public function successResponse($data){
        $stausCode = 200;
        $data['status'] = true;
        return response()->json($data,  $stausCode);
    }

    public function errorResponse($message, $stausCode=200){
        return response()->json([
            'status'=>false,
            'message'=>$message
        ], 
        $stausCode);
    }

    public function createUsername($name){
        $username = str_replace(' ','.',$name);
        $username = strtolower($username);
        $user = User::select('username')->where('username','LIKE','%'.$username.'%')->orderBy('id','DESC')->first();
        $postfix = 1;
        if($user && $user->username){
            $lastUsername = explode('.',$user->username);
            $postfix = end($lastUsername)+1;
        }
        return $username.'.'.$postfix;
    }

    public function str_random($length){
        if(!$length) $length = 32;
        return Str::random($length);
    }

    public function generateCode(){
        $size = 6;
        $alphaKey = '';
	    $keys = range('A', 'Z');
	
        for ($i = 0; $i < 2; $i++) {
            $alphaKey .= $keys[array_rand($keys)];
        }
	
	    $length = $size - 2;
	
        $key = '';
        $keys = range(0, 9);
	
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
	
	    return $alphaKey . $key;      
    }
}
