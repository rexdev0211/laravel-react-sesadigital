<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Package;

class PackageController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Package::select("*");
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
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Package();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;
            $model->total_price = $request->price+$request->estate_service_charge+$request->commission_fee;

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
        $record = Package::where('slug', '=',$slug)->first();
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
        $model = Package::findBySlug($slug);        
        $data = $request->all();
        $data['total_price'] = $request->price+$request->estate_service_charge+$request->commission_fee;
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
            Package::whereIn('id',$ids)->update($data);
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
        $record = Package::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Package::destroy($ids)) {
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

    public function allPackages(Request $request) {
        $query = Package::select("*");
        if($request->status) $query->where('status', '=', $request->status);

        $query->orderBy('id', 'DESC');
        $records = $query->get();
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }
}
