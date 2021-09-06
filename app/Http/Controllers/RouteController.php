<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Model\User;
use App\Model\Route;
use App\Model\RoleRoute;
use App\Model\Estate;

class RouteController extends Controller {

    public $stausCode = 200;
   
    public function __construct() {
    }
        
    public function index(Request $request) {
        $stausCode = 200;
        $query = Route::where('is_display', '=', $request->is_display);
        if($request->route_type == "admin"){
            $query->where('is_admin','=',1);
        } elseif($request->route_type == "role"){
            $query->where('is_role','=',1);
        } elseif($request->route_type == "estate_manager"){
            $query->where('is_estate_manager','=',1);
        } elseif($request->route_type == "company"){
            $query->where('is_company','=',1);
        }
        // if($request->user_id){
        //     $user = User::find($request->user_id);
        //     $routeIds = [0];
        //     if($user && $user->route_id){
        //         $routeIds = explode(',',$user->route_id);
        //     }
        //     $query->whereIn('id', $routeIds);
        // }
        $query->orderBy('parent_id', 'ASC');
        $query->orderBy('display_order', 'ASC');
        $record = $query->get();
        
        $response = [
            'status'=>true,
            'record'=>$record
        ];
        return response()->json($response, $this->stausCode);
    }

    public function create() {
        
    }

    public function store(Request $request) {
    }

    public function show($id) {
        $staff = Route::findOrFail($id);
    }
    
    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        $record = User::find($id);      
        $data = $request->all();
        $record->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        
    }

    public function authRoutes() {
        $stausCode = 200;
        $user = auth()->user();
        $query = Route::whereIn('is_display',['yes','no']);
        if($user && $user->user_type != 'superadmin'){
            if(array_key_exists($user->user_type,config('site_vars.adminType'))){
                $routeId = $user->route_id ? explode(',',$user->route_id):[0];
                $query->whereIn('id',$routeId);
            } else if(array_key_exists($user->user_type,config('site_vars.userType'))){
                $routes = RoleRoute::where('role_id',$user->role_id)->get()->first();
                $routeId = ($routes && $routes->route_id) ? explode(',',$routes->route_id):[0];
                $query->whereIn('id',$routeId);
                $user['estate'] = Estate::select('id','name','is_signout_required')->where('id',$user->estate_id)->first();
            }
        } 
        //echo '<pre>';print_r($user->route_id);exit;
        $query->orderBy('parent_id', 'ASC');
        $query->orderBy('display_order', 'ASC');
        $records = $query->get()->pluck('name','route_key');
        $response = [
            'status'=>true,
            'record'=>$records,
            'myProfile' => $user
        ];
        return response()->json($response, $stausCode);
    }

    public function getAssignRoutes(Request $request) {
        $stausCode = 200;
        $query = Route::where('is_display', '=', "yes");
        if($request->user_id){
            $routeIds = [0];
            $user = User::find($request->user_id);
            if($user && $user->route_id){
                $routeIds = explode(',',$user->route_id);
            }
            $query->whereIn('id', $routeIds);
        }
        
        $query->orderBy('parent_id', 'ASC');
        $query->orderBy('display_order', 'ASC');

        $records = $query->get();

        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }
}
