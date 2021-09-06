<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\User;
use App\Model\Estate;
use App\Model\Event;
use App\Model\Visitor;
use App\Model\Artisan;
use App\Model\Product;
use App\Model\PurchasedProduct;
use App\Model\PowerProduct;
use App\Model\Payment;
use App\Model\Package;

class DashboardController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }

    public function totalStats(Request $request) {
        $authUser = auth()->user();
        $userId = $authUser->id;
        $countStats = [];
        $conditions = [];
        $admin = User::select('*');
        $manager = User::select('*');
        $company = User::select('*');
        $user = User::select('*');
        $guard = User::select('*');
        $product = new Product();
        $purchasedProduct = new PurchasedProduct();
        $purchasedPower = PowerProduct::where('status','bought');
        $purchasedPackage =Payment::where('pay_type','subscription');
        $payment = new Payment();

        if(in_array($authUser->user_type,['superadmin','admin'])){
            $countStats['admin'] = $admin->where('user_type','admin')->count();
            $countStats['manager'] = $manager->where('user_type','estate_manager')->count();
            $countStats['company'] = $company->where('user_type','company')->count();
            $countStats['artisan'] = Artisan::count();
            $countStats['package'] = Package::count();
            $estateCount = Estate::count();
            // $countStats['payment'] = $payment->count();
        }
        if(in_array($authUser->user_type,['estate_manager','company'])){
            if($authUser->user_type=='estate_manager'){
                $estateIds = Estate::where('manager_id',$userId)->pluck('id','id')->toArray();
                $guard->whereIn('manager_id',$userId);
            }else if($authUser->user_type=='company'){
                $estateIds = Estate::where('company_id',$userId)->pluck('id','id')->toArray();
                $guard->whereIn('company_id',$userId);
            }
            $user->whereIn('estate_id',$estateIds);
            $product->whereIn('estate_id',$estateIds);
            $purchasedProduct->whereIn('estate_id',$estateIds);
            $purchasedPower->whereIn('estate_id',$estateIds);
            $payment->whereIn('estate_id',$estateIds);
            $estateCount = count($estateIds);
        }

        $countStats['estate'] = $estateCount;
        $countStats['user'] = $user->where('user_type','resident')->count();
        $countStats['guard'] = $guard->where('user_type','guard')->count();
        $countStats['product'] = $product->count();
        $countStats['purchasedProduct'] = $purchasedProduct->count();
        $countStats['purchasedPower'] = $purchasedPower->count();
        $countStats['purchasedPackage'] = $purchasedPackage->count();
               
        $response = [
            'status'=>true,
            'record'=>$countStats
        ];
        return response()->json($response, $this->stausCode);
    }
       
    public function getSets(Request $request) {
        $authUser = auth()->user();
        $userId = $authUser->id;

        $labels = [];
        $length = date("t");
        for($i=1;$i<=$length; $i++){
            array_push($labels,sprintf("%02d", $i));
        }

        $datasets = [];
        $payment =  Payment::select(DB::raw("DATE_FORMAT(created_at,'%d-%m') AS date"), DB::raw("COUNT(id) AS total"));
        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$userId)->get()->pluck('id');
            $payment->whereIn('estate_id',$estateIds);
        }
        
        //----------------- Payment ----------------
        $payments = $payment->whereDate("created_at",">=", date('Y-m').'-01')
        ->groupBy('date')
        ->orderBy('date','ASC')
        ->get();
        $datasets['payment'] = $this->setGraphData($payments);
        
        //----------------- wallet_credit ----------------
        $paymentWc = $payment->where('pay_type', 'wallet_credit')
        ->whereDate("created_at",">=", date('Y-m').'-01')
        ->groupBy('date')
        ->orderBy('date','ASC')
        ->get();
        $datasets['walletCredit'] = $this->setGraphData($paymentWc);
        
        //----------------- product_pay ----------------
        $paymentPp = $payment->where('pay_type', 'product_pay')
        ->whereDate("created_at",">=", date('Y-m').'-01')
        ->groupBy('date')
        ->orderBy('date','ASC')
        ->get();
        $datasets['productPay'] = $this->setGraphData($paymentPp);
        
        //----------------- subscription ----------------
        if($authUser->user_type == 'superadmin'){
            $paymentSc = $payment->where('pay_type', 'subscription')
            ->whereDate("created_at",">=", date('Y-m').'-01')
            ->groupBy('date')
            ->orderBy('date','ASC')
            ->get();
            $datasets['subscription'] = $this->setGraphData($paymentSc);
        }

        $response = [
            'status'=>true,
            'record'=>[
                'labels'=>$labels,
                'data'=>$datasets
            ]
        ];
        return response()->json($response, $this->stausCode);
    }

    public function userCountStats(Request $request) {
        $loggedIn = auth()->user();
        $userId = $loggedIn->id;
        // $countStats = [
        //     'user'=>0,
        //     'estate'=>0,
        //     'visitor'=>0,
        //     'artisan'=>0,
        //     'product'=>0,
        //     'purchasedProduct'=>0,
        //     'paidProduct'=>0,
        //     'unpaidProduct'=>0,
        //     'payment'=>0,
        // ];
        $countStats = [];
        if($loggedIn->user_type == 'resident'){
            $countStats['user'] = User::where('resident_id',$userId)->count();
            $countStats['event'] = Event::where('user_id',$userId)->count();
            $countStats['visitor'] = Visitor::where('user_id',$userId)->count();
            $countStats['artisan'] = Artisan::count();
            $countStats['product'] = Product::where('estate_id',$loggedIn->estate_id)->count();
            $countStats['purchasedProduct'] = PurchasedProduct::where('user_id',$userId)->count();
            $countStats['paidProduct'] = PurchasedProduct::where('user_id',$userId)->where('paidStatus','complete')->count();
            $countStats['unpaidProduct'] = PurchasedProduct::where('user_id',$userId)->where('paidStatus','partial')->count();
            $countStats['payment'] = Payment::where('user_id',$userId)->count();
        }
        $response = [
            'status'=>true,
            'record'=>$countStats
        ];
        return response()->json($response, $this->stausCode);
    }
        
    public function userGetDatasets(Request $request) {
        $loggedIn = auth()->user();
        $userId = $loggedIn->id;

        $labels = [];
        $length = date("t");
        for($i=1;$i<=$length; $i++){
            array_push($labels,sprintf("%02d", $i));
        }

        $datasets = [];
        
        //----------------- Payment ----------------
        $payments = Payment::select(DB::raw("DATE_FORMAT(created_at,'%d-%m') AS date"), DB::raw("COUNT(id) AS total"))
        ->where('user_id', $userId)
        ->whereDate("created_at",">=", date('Y-m').'-01')
        ->groupBy('date')
        ->orderBy('date','ASC')
        ->get();
        $datasets['payment'] = $this->setGraphData($payments);
        
        //----------------- wallet_credit ----------------
        $paymentWc = Payment::select(DB::raw("DATE_FORMAT(created_at,'%d-%m') AS date"), DB::raw("COUNT(id) AS total"))
        ->where('user_id', $userId)
        ->where('pay_type', 'wallet_credit')
        ->whereDate("created_at",">=", date('Y-m').'-01')
        ->groupBy('date')
        ->orderBy('date','ASC')
        ->get();
        $datasets['walletCredit'] = $this->setGraphData($paymentWc);
        
        //----------------- product_pay ----------------
        $paymentPp = Payment::select(DB::raw("DATE_FORMAT(created_at,'%d-%m') AS date"), DB::raw("COUNT(id) AS total"))
        ->where('user_id', $userId)
        ->where('pay_type', 'product_pay')
        ->whereDate("created_at",">=", date('Y-m').'-01')
        ->groupBy('date')
        ->orderBy('date','ASC')
        ->get();
        $datasets['productPay'] = $this->setGraphData($paymentPp);
        
        //----------------- product_pay ----------------
        $paymentPp = Payment::select(DB::raw("DATE_FORMAT(created_at,'%d-%m') AS date"), DB::raw("COUNT(id) AS total"))
        ->where('user_id', $userId)
        ->where('pay_type', 'product_pay')
        ->whereDate("created_at",">=", date('Y-m').'-01')
        ->groupBy('date')
        ->orderBy('date','ASC')
        ->get();
        $datasets['productPay'] = $this->setGraphData($paymentPp);

        $response = [
            'status'=>true,
            'record'=>[
                'labels'=>$labels,
                'data'=>$datasets
            ]
        ];
        return response()->json($response, $this->stausCode);
    }

    function setGraphData($records){
        $paymentData = [];
        foreach  ($records as $key => $value){
            $dateArr = explode('-',$value->date);
            $dataKey = $dateArr[0];
            $paymentData[$dataKey] = $value->total;
        }
        
        $length = date("t");
        $datasets = [];
        for($i=1;$i<=$length; $i++){
            $key = sprintf("%02d", $i);
            $value =  isset($paymentData[$key]) ?$paymentData[$key] :0;
            array_push($datasets,$value);
            // $datasets[$key] = isset($paymentData[$key]) ?$paymentData[$key] :0;
        }
        return $datasets;
    }

}
