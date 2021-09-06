<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Advert;
use App\Model\Estate;

class AdvertController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'adverts';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
        
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        
        $query = Advert::select('*');
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);

        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->start_date)  $query->where("start_date","<=", $request->start_date);
        if($request->end_date)  $query->where("end_date",">=", $request->end_date);
        if($request->status)  $query->where("status", $request->status);
        if($request->estate_id)  $query->where("estate_id", $request->estate_id);

        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->pluck('id')->toArray();
            $query->whereIn('estate_id', $estateIds);
        } else if($authUser->user_type == 'company'){
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
            $model = new Advert();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;
            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model['photo'] = $this->uploadDir."/".$photo_name;
            }


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
        $record = Advert::findBySlug($slug);
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
        $model = Advert::findBySlug($slug);        
        $data = $request->all();
        if (isset($data['photo'])){
            $file = $data['photo'];
            $photo_name = $this->str_random(6).$data['photo']->getClientOriginalName();
            $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
            $data['photo'] = $this->uploadDir.'/'.$photo_name;
            if($model->photo){
                $oldFilePath = config('site_vars.publicPath').$model->photo;
                if(file_exists($oldFilePath)){
                    @unlink($oldFilePath);
                }
            }
        } else {
            $data['photo'] = $model->photo;
        }
        
        $model->update($data);
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
            Advert::whereIn('id',$ids)->update($data);
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
        $record = Advert::findOrFail($id);
        if($record->photo){
            $oldFilePath = config('site_vars.publicPath').$record->photo;
            if(file_exists($oldFilePath)){
                @unlink($oldFilePath);
            }
        }
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Advert::destroy($ids)) {
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
}
