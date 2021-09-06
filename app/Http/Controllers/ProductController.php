<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Product;
use App\Model\ProductInstallment;
use App\Model\PurchasedProduct;
use App\Model\Estate;

class ProductController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'products';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $stausCode = 200;
        $query = Product::with('installments');
        if(array_key_exists($authUser->user_type,config('site_vars.adminType'))){
            $query->with(['estate' => function ($q)  {
                $q->select('id','name');
            }]);
            if($request->estate_name) 
                $query->whereHas('estate', function ($q) use($request) {
                    $q->where('name', 'LIKE', '%' . $request->estate_name. '%');
                });
        }
        if($request->name)$query->where('name', 'like', $request->name);
        if($request->status) $query->where('status', '=', $request->status);

        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->pluck('id')->toArray();
            $query->whereIn('estate_id', $estateIds);
        } else if($authUser->user_type == 'company'){
            $estateIds = Estate::where('company_id',$authUser->id)->pluck('id')->toArray();
            $query->whereIn('estate_id', $estateIds);
        } else if(in_array($authUser->user_type,['gaurd','resident'])){
            $query->where('estate_id', $authUser->estate_id);
        }

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);
        $records[0]->created_id;
        $response = [
            'status'=>true,
            'record'=>$records,
            'create'=>$records[0]->created_id
        ];
        return response()->json($response, $stausCode);
    }

    public function create() {
        
    }

    public function store(Request $request) {
        $stausCode = 200;
        $loggedInUser = auth()->user();
        $messages = [
            'name.required' => 'This is required field.',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Product();
            $model->fill($request->all());
            $model->total_amount = $request->amount;
            $model->created_id = $loggedInUser->id;
            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model['photo'] = $this->uploadDir."/".$photo_name;
            }

            if($model->save()){
                $lastInsertId=$model->id;
                if($request->installments){
                    $installments = explode(",",$request->installments);
                    $insData = [];
                    foreach($installments as $value){
                        if($value)
                            array_push($insData,['product_id'=>$lastInsertId,'amount'=>$value]);
                    }
                    ProductInstallment::insert($insData);
                }
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
            // foreach($validator->messages()->get('*') as $value){
            //     echo '<pre>';print_r($value);
            // }
            $response = [
                'status'=>false,
                'message'=>$validator->messages()->get('name')
            ];
        }       
            
        return response()->json($response, $stausCode);
    }

    public function show($slug) {
        $authUser = auth()->user();
        // $record = Product::findBySlug($slug);
        $query = Product::with('installments');
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $record = $query->where('slug','=',$slug)->first();
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
        $record = Product::findBySlug($slug);        
        $data = $request->all();
        if (isset($data['photo'])){
            $file = $data['photo'];
            $photo_name = $this->str_random(6).$data['photo']->getClientOriginalName();
            $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
            $data['photo'] = $this->uploadDir.'/'.$photo_name;
            if($record->photo){
                $oldFilePath = config('site_vars.publicPath').$record->photo;
                if(file_exists($oldFilePath)){
                    @unlink($oldFilePath);
                }
            }
        } else {
            $data['photo'] = $record->photo;
        }
        $data['total_amount'] = $request->amount;
        $record->update($data);
        ProductInstallment::where('product_id',$record->id)->delete();
        if($data["installments"]){
            $installments = explode(",",$data["installments"]);
            $insData = [];
            foreach($installments as $value){
                if($value)
                    array_push($insData,['product_id'=>$record->id,'amount'=>$value]);
            }
            ProductInstallment::insert($insData);
        }
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
            Product::whereIn('id',$ids)->update($data);
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
        $record = Product::findOrFail($id);
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
        if($ids && Product::destroy($ids)) {
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

    public function list(Request $request) {
        $records = Product::select('name','id')->get()->pluck('id','name');
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    //purchased product
    public function purchased(Request $request) {
        $authUser = auth()->user();

        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $stausCode = 200;
        $query = PurchasedProduct::select('*');
        if(array_key_exists($authUser->user_type,config('site_vars.adminType'))){
            $query->with(['user' => function ($q)  {
                $q->select('*',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
            }]);
            $query->with(['estate' => function ($q)  {
                $q->select('id','name');
            }]);

            $query->whereHas('user', function ($q) use($request) {
                if($request->resident_name) 
                    $q->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' . $request->resident_name. '%');
                if($request->resident_email) $q->where('email',  $request->resident_email);
                if($request->resident_phone) $q->where('phone',  $request->resident_phone);
            });
            if($request->estate_name) 
                $query->whereHas('estate', function ($q) use($request) {
                    $q->where('name', 'LIKE', '%' . $request->estate_name. '%');
                });
        }
        if($request->user_id)$query->where('user_id',  $request->user_id);
        if($request->name)$query->where('name', 'like', $request->name);
        if($request->paidStatus)$query->where('paidStatus', '=', $request->paidStatus);

        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->get()->pluck('id');
            $query->whereIn('estate_id',$estateIds);
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
        return response()->json($response, $stausCode);
    }

    public function purchasedDetails($slug) {
        $authUser = auth()->user();
        $query = PurchasedProduct::select('*');
        if(array_key_exists($authUser->user_type,config('site_vars.adminType'))){
            $query->with(['user' => function ($q)  {
                $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"),'email','phone');
            }]);
            $query->with(['estate' => function ($q)  {
                $q->select('id','name');
            }]);
        }
        $query->where('slug','=',$slug);
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

}
