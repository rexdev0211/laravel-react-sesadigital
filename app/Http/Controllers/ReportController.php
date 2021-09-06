<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\PowerProduct;
use App\Model\Payment;
use App\Model\UserCheck;

class ReportController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }

       
    public function user(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = UserCheck::select("*");
        
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"),'email','phone','house_code');
        }]);
        $query->with(['checkInBy' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);
        $query->with(['checkOutBy' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);
       
        $query->whereHas('estate', function ($q) use($request) {
                if($request->estate) $q->where('name', 'LIKE', '%' .$request->estate. '%');
            });
        $query->whereHas('user', function ($q) use($request) {
            if($request->name)  $q->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' .$request->name. '%'); 
            if($request->house_code) $q->where('house_code', $request->house_code);
            if($request->phone) $q->where('house_code', $request->phone);
        });
            
        if($request->estate_id) $query->where('estate_id', $request->estate_id);
    
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
        
    public function product(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Payment::select("*");
        
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"),'email','phone');
        }]);
        $query->with('purchasedProduct');
        if($request->estate) 
            $query->whereHas('estate', function ($q) use($request) {
                $q->where('name', 'LIKE', '%' .$request->estate. '%');
            });
            $query->whereHas('purchasedProduct', function ($q) use($request) {
                if($request->paidStatus) 
                $q->where('paidStatus', $request->paidStatus);
                if($request->product_name)  $q->where('name', 'LIKE', '%' .$request->product_name. '%');
            });

        if($request->user_id) $query->where('user_id', $request->user_id);
        if($request->estate_id) $query->where('estate_id', $request->estate_id);
        if($request->status) $query->where('status', '=', $request->status);
        if($request->email) $query->where('email', $request->email);
        if($request->phone) $query->where('phone', $request->phone);
        $query->where('pay_type', '=', 'product_pay');

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
        
    public function powerProduct(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Payment::select("*");
        
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"),'email','phone','house_code');
        }]);
        $query->with('powerProduct');
        if($request->estate) 
            $query->whereHas('estate', function ($q) use($request) {
                $q->where('name', 'LIKE', '%' .$request->estate. '%');
            });
            $query->whereHas('powerProduct', function ($q) use($request) {
                if($request->powerStatus)  $q->where('status', $request->powerStatus);
                if($request->power_company)  $q->where('power_company', 'LIKE', '%' .$request->power_company. '%');
            });

        if($request->user_id) $query->where('user_id', $request->user_id);
        if($request->estate_id) $query->where('estate_id', $request->estate_id);
        if($request->status) $query->where('status', '=', $request->status);
        if($request->start_date)  $query->whereDate("created_at",">=", $request->start_date);
        if($request->end_date)  $query->whereDate("created_at","<=", $request->end_date);

        $query->where('pay_type', '=', 'power_product_pay');

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
}
