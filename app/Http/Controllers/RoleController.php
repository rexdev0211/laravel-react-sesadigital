<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Role;
use App\Model\RoleRoute;

class RoleController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Role::with(['user' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        if($request->role_for) 
            $query->whereHas('user', function ($q) use($request) {
                $q->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' .$request->role_for. '%');
            });
        $query->with('roleRoute');
        if($request->user_id) $query->where('user_id', '=', $request->user_id);
        if($request->name)  $query->where('name', 'LIKE', '%' .$request->name. '%');
        if($request->status) $query->where('status', '=', $request->status);

        if($request->sortBy && $request->orderBy){
            $query->orderBy($request->sortBy, $request->orderBy);
        }else{
            $query->orderBy('id', 'DESC');
        }
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
            // 'user_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            // 'user_id' => 'required',
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Role();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;

            if($model->save()){
                $response = [
                    'status'=>true,
                    'message'=>'Record added successfully.',
                    'last_insert_id' => $model->id
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
                'message'=>$validator->messages()->get('name')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Role::with('roleRoute')->where('slug', '=',$slug)->first();
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
        $model = Role::findBySlug($slug);        
        $data = $request->all();
        unset($data['user_id']);

        if($model->update($data))
            $response = [
                'status'=>true,
                'message'=>'Record updated successfully.'
            ];
        else 
            $response = [
                'status'=>false,
                'message'=>'Something went wrong. Please try again later.'
            ];
        
        return response()->json($response, $this->stausCode);
    }

    public function changeStatus(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            $data=['status'=>$request->status];
            Role::whereIn('id',$ids)->update($data);
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
        $record = Role::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Role::destroy($ids)) {
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

    public function addRoleRoutes(Request $request) {
        $loggedIn = auth()->user();
        
        $messages = [
            // 'user_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            'role_id' => 'required',
            'route_id' => 'required',
        ],$messages);

        if ($validator->passes()) {
            if($request->id){
                $model = RoleRoute::find($request->id);        
                $data = $request->all();
    
                if($model->update($data))
                    $response = [
                        'status'=>true,
                        'message'=>'Record updated successfully.'
                    ];
                else 
                    $response = [
                        'status'=>false,
                        'message'=>'Something went wrong. Please try again later.'
                    ];
            } else {
                $model = new RoleRoute();
                $model->fill($request->all());
    
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
            }
            
        } else {
            $response = [
                'status'=>false,
                'message'=>$validator->messages()->get('role_id')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function getRoleList(Request $request) {
        $query = Role::select("id","name");
        if($request->status) $query->where('status', '=',  $request->status);
        if($request->user_id) $query->where('user_id', '=',  $request->user_id);
        else $query->where('user_id', '=',  null);
        $query->orderBy('name', 'ASC');
        $records =$query->get();

        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

}
