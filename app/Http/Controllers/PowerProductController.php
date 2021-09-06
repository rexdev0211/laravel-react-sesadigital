<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\PowerProduct;
use App\Model\Payment;
use App\Model\User;

class PowerProductController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
        
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        if( $request->_rec=== 'not'){
            $query = PowerProduct::select('id','created_id','estate_id','power_company','amount','power_unit', 'slug', 'status');
        } else {
            $query = PowerProduct::select("*");
        }
        
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);
        if($request->estate) 
            $query->whereHas('estate', function ($q) use($request) {
                $q->where('name', 'LIKE', '%' .$request->estate. '%');
            });

        if($request->user_id) $query->where('user_id', $request->user_id);
        if($request->estate_id) $query->where('estate_id', $request->estate_id);
        if($request->power_company)  $query->where('power_company', 'LIKE', '%' .$request->power_company. '%');
        if($request->status) $query->where('status', '=', $request->status);

        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->get()->pluck('id');
            $query->whereIn('estate_id',$estateIds);
        }
        if($authUser->user_type == 'company'){
            $estateIds = Estate::where('company_id',$authUser->id)->pluck('id')->toArray();
            $query->whereIn('estate_id', $estateIds);
        }

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
            'estate_id' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new PowerProduct();
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
                'message'=>$validator->messages()->get('estate_id')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $query = PowerProduct::where('slug', '=',$slug);
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $record = $query->first();
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
        $model = PowerProduct::findBySlug($slug);        
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
        
        return response()->json($response, $this->stausCode);
    }

    public function changeStatus(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            $data=['status'=>$request->status];
            PowerProduct::whereIn('id',$ids)->update($data);
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
        $record = PowerProduct::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && PowerProduct::destroy($ids)) {
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

    public function buy(Request $request, $id) {
        $record = PowerProduct::where('status','available')->where('id',$id)->first();        
        if($record){
            $authUser = auth()->user();
            $paymentData = [
                "pay_type"=>'power_product_pay',
                "pay_gateway"=>$request->method,
                "user_id"=>$authUser->id,
                "estate_id"=>$authUser->estate_id,
                "transaction_id"=>'POWP|'.time().'|'.sprintf("%06d", $record->id),
                "amount"=>$record->amount,
                "status"=>'Successful',
                "description"=>'Power purchased from '.$record->power_company,
                "power_product_id"=>$record->id,
                "slug"=>time().$authUser->id
            ];
            $model = new Payment();
            $model->fill($paymentData);
            if($model->save()){
                $user = User::find($authUser->id);
                $wallet_balance = $authUser->wallet_balance-$record->amount;
                $data = [
                    "wallet_balance"=>$wallet_balance
                ];
                $user->update($data);

                $record->user_id = $authUser->id;
                $record->status = 'bought';
                $record->purchased_at = date('Y-m-d');
                $record->update($data);

                $response = [
                    'status'=>true,
                    'message'=>'Payment done successfully.',
                    'user'=>$user
                ];
            }else {
                $response = [
                    'status'=>false,
                    'message'=>'Something went wrong. Please try again later.'
                ];
            }
        }  else {
            $response = [
                'status'=>false,
                'message'=>'Something went wrong. Please try again later.'
            ];
        }
            
        
        return response()->json($response, $this->stausCode);
    }
}
