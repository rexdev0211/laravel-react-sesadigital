<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Model\Template;

class TemplateController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Template::select('*');
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->subject)  $query->where("subject", 'LIKE', '%' .$request->subject. '%');
        if($request->status) $query->where('status', '=', $request->status);

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
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Template();
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
        } else {
            $response = [
                'status'=>false,
                'message'=>$validator->messages()->get('name')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Template::findBySlug($slug);
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
        $model = Template::findBySlug($slug);        
        $data = $request->all();
        
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
            Template::whereIn('id',$ids)->update($data);
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
        $record = Template::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Template::destroy($ids)) {
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
