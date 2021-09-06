<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Setting;
use App\Model\PaymentMethod;

class SettingController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $query = Setting::select('*');
        $sortBy = 'setting_type'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->get();
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function create() {
        
    }

    public function store(Request $request) {        
        $data = $request->all();
        foreach($data as $key => $value) {
            if($value['id']){
                $updated = [
                    'key_value'=> $value['key_value']
                ];
                Setting::where('id', $value['id'])->update($updated);
            }
        }

        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];    
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Setting::findBySlug($slug);
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
        $model = Setting::findBySlug($slug);        
        $data = $request->all();
        $model->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        $record = Setting::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }
    
    public function generalSetting(Request $request) {
        $query = Setting::select("*");
        if($request->status) $query->where('status', '=', $request->status);
        $records = $query->get()->toArray();
        $updatedRecords = [];
        foreach($records as $value){
            $updatedRecords[$value['setting_type']][$value['key']] = [];
            $updatedRecords[$value['setting_type']][$value['key']] = $value['key_value'];
        }
        $response = [
            'status'=>true,
            'record'=>$updatedRecords
        ];
        return response()->json($response, $this->stausCode);
    }

    //payment methods
    public function paymentMethods(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = PaymentMethod::select("*");
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
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

    public function savePaymentMethod(Request $request) {
        $loggedIn = auth()->user();
        
        $messages = [
            // 'name' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ],$messages);

        if ($validator->passes()) {
            if($request->id){
                $model = PaymentMethod::find($request->id);        
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
                $model = new PaymentMethod();
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
                'message'=>$validator->messages()->get('name')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function updateStatusPaymentMethod(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            $data=['status'=>$request->status];
            PaymentMethod::whereIn('id',$ids)->update($data);
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

    public function destroyAllPaymentMethod(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && PaymentMethod::destroy($ids)) {
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

    public function paymentMethodList(Request $request) {
        $query = PaymentMethod::select("*");
        if($request->status) $query->where('status', '=', $request->status);
        $records = $query->get()->toArray();
        $updatedRecords = [];
        foreach($records as $value){
            $updatedRecords[$value['name']] = [];
            if($value['payment_mode'] =='sandbox'){
                $updatedRecords[$value['name']]['api_key'] = $value['sandbox_api_key'];
                $updatedRecords[$value['name']]['api_secret_key'] = $value['sandbox_api_secret_key'];
                $updatedRecords[$value['name']]['contract_code'] = $value['sandbox_contract_code'];
                $updatedRecords[$value['name']]['payment_url'] = $value['sandbox_payment_url'];
            }
        }
        $response = [
            'status'=>true,
            'record'=>$updatedRecords
        ];
        return response()->json($response, $this->stausCode);
    }

}
