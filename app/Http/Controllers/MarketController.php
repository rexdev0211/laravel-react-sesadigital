<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Market;
use App\Model\User;
use App\Model\Template;

class MarketController extends Controller
{
    

    public $stausCode = 200;
    public $uploadDir = 'marketplace';

    public function __construct() {
        parent::__construct();
    }



    public function index(Request $request) {
        
       // $authUser = auth()->user();
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }


        $results = DB::select( DB::raw("SELECT id,name FROM estates") );

        $records = Market::latest()->paginate(5);
        $response = [
            'status'=>true,
            'record'=>$records,
            'estate'=>$results,
        ];
        return response()->json($response, $this->stausCode);
    }

    public function create()
    {
    }
  
    public function store(Request $request)
    {
        $request->validate([
            'market_list_name' => 'required',
            'publish_date' => 'required',
            'end_date' => 'required',
            'notes' => 'required',
            'assigned_stated' => 'required',
            'status' => 'required',
            'market_charges' => 'required',
        ]);
  
        $saved = Market::create($request->all());
        
        if($saved)
        {
            $response = [
                        'status'=>true,
                        'message'=>'Record added successfully.'
                    ];
            }else{
               $response = [
                        'status'=>false,
                        'message'=>'Record not added.'
                    ]; 
        }

            return response()->json($response, $this->stausCode);

    }
   
    public function show(Market $Market,$id)
    {

        if (Market::where('id', $id)->exists()) {
        $rec = Market::find($id);
        $results = DB::select( DB::raw("SELECT id,name FROM estates") );

         $response = [
            'status'=>true,
            'record'=>$rec,
            'estate'=>$results,
        ];

    }else{

        $response = [
            'status'=>false,
            'record'=>"No Record Found."
        ];

    }
        return response()->json($response, $this->stausCode);
    }
   
    public function edit(Market $Market)
    {
    }
  
   
    public function update(Request $request, $id)
    {  
        if (Market::where('id', $id)->exists()) {
        $Rec = Market::find($id);

        $Rec->market_list_name = $request->market_list_name;
        $Rec->publish_date = $request->publish_date;
        $Rec->end_date = $request->end_date;
        $Rec->notes = $request->notes; 
        $Rec->assigned_stated = $request->assigned_stated; 
        $Rec->status = $request->status; 
        $Rec->market_charges = $request->market_charges; 



        $Rec->save();

        return response()->json([
          "message" => $Rec
        ], 200);
      } else {
        return response()->json([
          "message" => "Rec not found"
        ], 404);
      }
    }
  
       public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Market::destroy($ids)) {
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