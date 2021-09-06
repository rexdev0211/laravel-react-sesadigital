<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Payment;
use App\Model\User;
use App\Model\Package;
use App\Model\Product;
use App\Model\PurchasedProduct;

class PaymentController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Payment::with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);
        if($request->estate_id) 
            $query->whereHas('estate', function ($q) use($request) {
                $q->where('name', 'LIKE', '%' . $request->estate_id. '%');
            });
        if($request->user_id)  $query->where("user_id", $request->user_id);
        if($request->pay_type)  $query->where("pay_type", $request->pay_type);
        if($request->transaction_id)  $query->where("transaction_id", 'LIKE', '%' .$request->transaction_id. '%');
        if($request->status) $query->where('status', '=', $request->status);
        if($request->start_date)  $query->whereDate("created_at",">=", $request->start_date);
        if($request->end_date)  $query->whereDate("created_at","<=", $request->end_date);

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
            // 'estate_id' => 'This field is required',
        ];
        
        $response = $request->response;
        $user_id = $request->user_id;
        
        if($response && strtoupper($response["status"]) == "SUCCESS"){
            $amount = $response['amountPaid'];
            if(isset($response['paymentStatus']) && strtoupper($response['paymentStatus']) == 'PAID'){
                $paymentStatus = 'Successful';
            } else {
                $paymentStatus = $response['paymentStatus'];
            }
            
            $data = [
                "pay_type"=>$request->pay_type,
                "pay_gateway"=>$request->method,
                "user_id"=>$user_id,
                "estate_id"=>$request->estate_id,
                "transaction_id"=>$response['transactionReference'],
                "amount"=>$amount,
                "payment_reference"=>$response['paymentReference'],
                "status"=>$paymentStatus,
                "description"=>$response['paymentDescription'],
                "slug"=>time().$user_id
            ];

            if($request->package_id){
                $package = Package::find($request->package_id);
                $data['package_id'] = $package->id;
                $data['package_name'] = $package->name;
                $data['package_price'] = $package->price;
                $data['package_estate_service_charge'] = $package->estate_service_charge;
                $data['package_commission_fee'] = $package->commission_fee;
                $data['package_total_price'] = $package->total_price;
                $data['package_can_add_user'] = $package->can_add_user;
                $validTill = '';
                if($package->package_type == 'monthly'){
                    $validTill = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 month'));
                } else if($package->package_type == 'yearly'){
                    $validTill = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 year'));
                }
                $data['package_valid_till'] = $validTill;
            }

            $model = new Payment();
            $model->fill($data);
            $model->created_id = $loggedIn->id;

            if($model->save()){
                $message = 'Payment done successfully.';
                $user = User::find($user_id);
                if($request->pay_type=='wallet_credit'){
                    $wallet_balance = $user->wallet_balance+$amount;
                    $data = [
                        "wallet_balance"=>$wallet_balance
                    ];
                    $user->update($data);
                    $message = 'Load wallet done successfully.';
                } else if($request->pay_type=='subscription'){
                    $message = 'Package has been purchased successfully. We will credit the package details in your account shortly. Thank you.';
                }

                $response = [
                    'status'=>true,
                    'message'=>$message,
                    'user'=>$user
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
                'message'=>'Invalid request data'
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $query = Payment::with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);
        $query->with('purchasedProduct');
        $query->where('slug',$slug);
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
        $model = Payment::findBySlug($slug);        
        $data = $request->all();
        $model->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        $record = Payment::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function monnifyWebhookResponse(Request $request) {
        $post_data = $request->all();
        $logData = [
            "transaction_reference"=>isset($post_data['transactionReference'])?$post_data['transactionReference']:'',
            "payment_reference"=>isset($post_data['paymentReference'])?$post_data['paymentReference']:'',
            "json_response"=>json_encode($post_data)
        ];
        DB::table('transaction_webhook_logs')->insert($logData);

        $payment_status = '';
        
        $user_id = $post_data['metaData']['user_id'];
        $user = User::find($user_id);

        if(empty($user)){
            exit;
        }

        if($post_data && strtoupper($post_data["status"]) == "SUCCESS"){
            if($post_data['metaData'] && isset($post_data['metaData']["package_id"]) && $post_data['metaData']["package_id"]){
                $package = Package::find($post_data['metaData']["package_id"]);
                if(empty($package)){
                    exit;
                }
                $model = new Payment();
                $model->pay_type = "subscription";
                $model->pay_gateway="monnify";
                $model->user_id=$user_id;
                $model->estate_id=$user->estate_id;
                $model->transaction_id = isset($post_data['transactionReference'])?$post_data['transactionReference']:'';
                $model->amount = isset($post_data['amountPaid'])?$post_data['amountPaid']:'';
                $model->payment_reference = isset($post_data['paymentReference'])?$post_data['paymentReference']:'';
                if(isset($post_data['paymentStatus']) && strtoupper($post_data['paymentStatus']) == 'PAID'){
                    $paymentStatus = 'Successful';
                } else {
                    $paymentStatus = $post_data['paymentStatus'];
                }
                $model->status = $paymentStatus;
                $model->description = isset($post_data['paymentDescription'])?$post_data['paymentDescription']:'';

                $model->package_id = $package->id;
                $model->package_name = $package->name;
                $model->package_price = $package->price;
                $model->package_estate_service_charge = $package->estate_service_charge;
                $model->package_commission_fee = $package->commission_fee;
                $model->package_can_add_user = $package->can_add_user;
                $today = strtotime(date("Y-m-d"));
                $packageValidTill = date("Y-m-d", strtotime("+1 months"));
                if($package->package_type=="yearly"){
                    $packageValidTill = date("Y-m-d", strtotime("+1 years"));
                }
                $model->package_valid_till = $packageValidTill;

                $model->slug = time().$user_id;
                $model->created_id = $loggedIn->id;
                $model->save();
            }
        }
        exit;
    }

    //product purchase
    public function productPay(Request $request) {
        $loggedIn = auth()->user();
        $userId = $loggedIn->id;
        
        $product = Product::with('installments')->where('id',$request->productId)->first();
        $totalPayAmount = $request->totalPayAmount;
        $installmentAdded = $request->installmentAdded;
       
        if($product){
            if($loggedIn->wallet_balance >= $totalPayAmount){
                $data = [
                    "user_id"=>$userId,
                    "estate_id"=>$loggedIn->estate_id,
                    "name"=>$product->name,
                    "photo"=>$product->photo,
                    "amountType"=>$product->amount_type,
                    "amount"=>$product->amount,
                    "totalAmount"=>$product->total_amount,
                    "amountPayType"=>$product->amount_pay_type,
                    "installmentType"=>$product->installment_type,
                    "paidAmount"=>$totalPayAmount,
                    "description"=>$product->description,
                    "slug"=>time().$userId,
                ];

                if($product->installments){
                    $instals = [];
                    foreach ($product->installments as $key=>$value){
                        $instals[$value->id] = $value->amount;
                    }
                    if($instals){
                        $data["producInstallment"] = json_encode($instals);
                    }
                }
                $paidinstals = [];
                if($installmentAdded){
                    foreach ($installmentAdded as $instalId=>$amount){
                        $paidinstals[$instalId] = $amount;
                    }
                    if($paidinstals){
                        $data["paidInstallment"] = json_encode($paidinstals);
                    }
                }
                $paidStatus = 'complete';
                if(count($paidinstals) < count($product->installments)){
                    $paidStatus = 'partial';
                }
                $data["paidStatus"] = $paidStatus;

                $purchasedProduct = new PurchasedProduct();
                $purchasedProduct->fill($data);
                $purchasedProduct->save();
                $purchasedProductInsertId=$purchasedProduct->id;

                $paymentData = [
                    "created_id"=> $userId,
                    "pay_type"=>"product_pay",
                    "pay_gateway"=>"wallet",
                    "user_id"=>$userId,
                    "estate_id"=>$loggedIn->estate_id,
                    "transaction_id"=>'PROD|'.time().'|'.sprintf("%06d", $purchasedProductInsertId),
                    "amount"=>$totalPayAmount,
                    "status"=>'Successful',
                    "description"=>$product->name.' product purchased.',
                    "slug"=>time().$userId,
                    "purchased_product_id"=>$purchasedProductInsertId
                ];

                $model = new Payment();
                $model->fill($paymentData);
                $model->save();

                $user = User::find($userId);
                $wallet_balance = $user->wallet_balance-$totalPayAmount;
                $data = [
                    "wallet_balance"=>$wallet_balance
                ];
                $user->update($data);
                $response = [
                    'status'=>true,
                    'message'=>'Payment done successfully.',
                    'user'=>$user
                ];
            } else {
                $response = [
                    'status'=>false,
                    'message'=>'Your current wallet balance is low'
                ];
            }               
        } else {
            $response = [
                'status'=>false,
                'message'=>'Invalid request data'
            ];
        }       
        return response()->json($response, $this->stausCode);
    }

    public function productInstallmantPay(Request $request) {
        $loggedIn = auth()->user();
        $userId = $loggedIn->id;
        
        $purchasedProduct = PurchasedProduct::where('id',$request->purchasedProductId)->first();
        
        $totalPayAmount = $request->totalPayAmount;
        $installmentAdded = $request->installmentAdded;
        
        if($purchasedProduct){
            if($loggedIn->wallet_balance >= $totalPayAmount){
                $paidAmount = $purchasedProduct->paidAmount+$totalPayAmount;
                $producInstallment = json_decode($purchasedProduct->producInstallment, true);
                $paidInstallment = json_decode($purchasedProduct->paidInstallment, true);

                $data['paidAmount'] = $paidAmount;

                if($installmentAdded){
                    foreach ($installmentAdded as $instalId=>$amount){
                        $paidInstallment[$instalId] = $amount;
                    }
                }
                $data['paidInstallment'] = json_encode($paidInstallment);
                $paidStatus = 'complete';
                if(count($paidInstallment) < count($producInstallment)){
                    $paidStatus = 'partial';
                }
                $data['paidStatus'] = $paidStatus;
                $purchasedProduct->update($data);

                $paymentData = [
                    "created_id"=> $userId,
                    "pay_type"=>"product_pay",
                    "pay_gateway"=>"wallet",
                    "user_id"=>$userId,
                    "estate_id"=>$loggedIn->estate_id,
                    "transaction_id"=>'PROD|'.time().'|'.sprintf("%06d", $purchasedProduct->id),
                    "amount"=>$totalPayAmount,
                    "status"=>'Successful',
                    "description"=>$purchasedProduct->name.' product purchased.',
                    "slug"=>time().$userId,
                    "purchased_product_id"=>$purchasedProduct->id
                ];

                $model = new Payment();
                $model->fill($paymentData);
                $model->save();

                $user = User::find($userId);
                $wallet_balance = $user->wallet_balance-$totalPayAmount;
                $data = [
                    "wallet_balance"=>$wallet_balance
                ];
                $user->update($data);
                $response = [
                    'status'=>true,
                    'message'=>'Payment done successfully.',
                    'user'=>$user
                ];
            } else {
                $response = [
                    'status'=>false,
                    'message'=>'Your current wallet balance is low'
                ];
            }               
        } else {
            $response = [
                'status'=>false,
                'message'=>'Invalid request data'
            ];
        }       
        return response()->json($response, $this->stausCode);
    }

}
