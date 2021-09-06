<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Model\Group;
use App\Model\Estate;
use App\Model\User;

class GroupController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
       
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Group::select('*');
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->status) $query->where('status', '=', $request->status);

        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->pluck('id')->toArray();
            $query->whereIn('estate_id', $estateIds);
        }
        if($authUser->user_type == 'company'){
            $estateIds = Estate::where('company_id',$authUser->id)->pluck('id')->toArray();
            $query->whereIn('estate_id', $estateIds);
        }

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function create() {
        
    }

    public function store(Request $request) {
        $loggedIn = auth()->user();
        
        $messages = [
            // 'manager_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            // 'manager_id' => 'required',
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Group();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;

            if($model->save()){
                $response = [
                    'status'=>true,
                    'message'=>'Record added successfully.'
                ];
            } else {
                $response = [
                    'status'=>false,
                    'message'=>'Something went wrong. Please try again later.'
                ]; 
            }
        } else {
            $response = [
                'status'=>false,
                'message'=>$validator->messages()->get('email')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Group::findBySlug($slug);
        if($record) 
            $response = [
                'status'=>true,
                'record'=>$record
            ];
        else 
            $response = [
                'status'=>false,
                'record'=>'No record found'
            ];
        return response()->json($response, $this->stausCode);
    }
    
    public function edit($id) {
        //
    }

    public function update(Request $request, $slug) {
        $record = Group::findBySlug($slug);        
        $data = $request->all();        
        $record->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function changeStatus(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            $data=['status'=>$request->status];
            Group::whereIn('id',$ids)->update($data);
            $response = [
                'status'=>true,
                'message'=>'Record updated successfully.'
            ];
        } else {
            $response = [
                'status'=>false,
                'message'=>'Invalid request. Please try later again.'
            ];
        }
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        $record = Group::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Group::destroy($ids)) {
            $response = [
                'status'=>true,
                'message'=>'Record deleted successfully.'
            ];
        } else {
            $response = [
                'status'=>false,
                'message'=>'Records not deleted. Please try later again.'
            ];
        }
       
        return response()->json($response, $this->stausCode);
    }

    public function groupRelatedLists(Request $request) {
        $authUser = auth()->user();
        $query = Estate::select('id','name');
        if($request->status) $query->where('status', '=', $request->status);
        if($authUser->user_type == 'estate_manager')
            $query->where('manager_id', '=', $authUser->id);
        if($authUser->user_type == 'company')
            $query->where('company_id', '=', $authUser->id);
        
        $query->orderBy('name', 'ASC');
        $record['estates'] = $query->get();  

        $response = [
            'status'=>true,
            'record'=>$record
        ];
        return response()->json($response, $this->stausCode);
    }

    public function getUsers(Request $request) {
        $query = User::select('id','user_type','first_name','last_name');
        if($request->estate_id) $query->where('estate_id', '=', $request->estate_id);
        if($request->status) $query->where('status', '=', $request->status);
        if($request->group_type && $request->group_type_value) 
            $query->where($request->group_type, '=', $request->group_type_value);
        $query->orderBy('first_name', 'ASC');
        $record = $query->get();  

        $response = [
            'status'=>true,
            'record'=>$record
        ];
        return response()->json($response, $this->stausCode);
    }

}
