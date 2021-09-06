<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\UserCheck;
use App\Model\User;

class UserCheckController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        
    }

    public function create() {
        
    }

    public function store(Request $request) {
            $loggedIn = auth()->user();

            $model = new UserCheck();
            $data = [
                'estate_id'=>$request->estate_id,
                'user_id'=>$request->user_id,
                'tag_number'=>$request->tag_number,
                'check_in_by_id'=>$loggedIn->id,
                'check_in_at'=>date('Y-m-d h:i:s')
            ];
            $model->fill($data);

            if($model->save()){
                $insertedId = $model->id;
                $data = UserCheck::find($insertedId); 
                $response = [
                    'status'=>true,
                    'message'=>'Resident checked in successfully.',
                    'userCheck'=>$data
                ];
            } else {
                $response = [
                    'status'=>false,
                    'message'=>'Something went wrong. Please try again later.'
                ]; 
            }
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Visitor::findBySlug($slug);
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

    public function update(Request $request, $id) {
        $loggedIn = auth()->user();
        $model = UserCheck::find($id); 
        $data = [
            "check_out_by_id" => $loggedIn->id,
            "check_out_at" => date('Y-m-d h:i:s')
        ];
        $model->update($data);
        $response = [
            'status'=>true,
            'message'=>'Check out successfully.',
            'userCheck'=>$model
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        
    }

    public function details(Request $request) {
        $condition = [];
        if($request->resident_code)
            $condition['resident_code'] = $request->resident_code;

        $response = [
            'status'=>false,
            'record'=>'No record found'
        ];

        if(count($condition)>0){
            $record = User::where($condition)->first();
            if($record) {
                $record['userCheck'] = UserCheck::where([
                    ['user_id','=',$record->id],
                    ['estate_id','=',$record->estate_id]
                ])->whereNotNull('check_in_by_id')->orderBy('id','DESC')->first();
                
                $response = [
                    'status'=>true,
                    'record'=>$record
                ];
            }
        }
        return response()->json($response, $this->stausCode);
    }

}
