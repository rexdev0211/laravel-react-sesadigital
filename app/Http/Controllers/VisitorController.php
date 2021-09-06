<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Visitor;
use App\Model\VisitorSetting;
use App\Model\User;
use App\Model\Notification;
use App\Model\Template;

class VisitorController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Visitor::with(['resident' => function ($q)  {
            $q->select('id','first_name','last_name','resident_code');
        }]);
        $query->with('visitorVisit');
        //$query->with('artisans');

        if($request->user_id) 
            $query->where("user_id", '=', $request->user_id);
        if($request->created_id) 
            $query->where("created_id", '=', $request->created_id);
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->phone) $query->where('phone', '=', $request->phone);
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
            // 'manager_id' => 'required',
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Visitor();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;
            $model->estate_id = $loggedIn->estate_id;
            $accessCode = $this->generateCode();
            $isExists = Visitor::where([['access_code',$accessCode],['estate_id',$loggedIn->estate_id]])->count();
            if($isExists>0)$accessCode = str_shuffle($accessCode);
            $model->access_code = $accessCode;

            if($model->save()){
                if($loggedIn->id != $request->user_id){
                    $user = User::find($request->user_id);
                    $name = ucwords($user->first_name.' '.$user->last_name);
                    if($user->email){
                        $mailData = [
                            'templateId'=>8,
                            'to'=>$user->email,
                            'tags' => [
                                '{NAME}'=>$name,
                                '{ACCESS_CODE}'=>$accessCode
                            ]
                        ];
                        $template = new Template();
                        $template->sendEmail($mailData);
                    }
                    if($user->phone){
                        $smsData = [
                            'templateId'=>9,
                            'estateId'=>$user->estate_id,
                            'toId'=>$user->id,
                            'toIdName'=>$name,
                            'phones'=>$user->phone,
                            'tags' => [
                                '{NAME}'=> $name,
                                '{ACCESS_CODE}'=>$accessCode
                            ]
                        ];
                        $template = new Template();
                        $template->sendSMS($smsData);
                    }
                    $sendData = [
                        'fromId'=>$loggedIn->id,
                        'estateId'=>$user->estate_id,
                        'toId'=>$user->id,
                        'notifiType' => 'sign-in',
                        'token'=>[$user->device_token],
                        'title'=> 'Visitor signed in',
                        'body'=> 'A new visitor has been successful signed in.'
                    ];
                    $notification = new Notification();
                    $notification->saveNotification($sendData);
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
            $response = [
                'status'=>false,
                'message'=>$validator->messages()->get('name')
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

    public function update(Request $request, $slug) {
        $model = Visitor::findBySlug($slug);        
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
            Visitor::whereIn('id',$ids)->update($data);
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
        $record = Visitor::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Visitor::destroy($ids)) {
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

    public function lists(Request $request) {
        $query = Visitor::select('id','name');
        if($request->status) $query->where('status', '=', $request->status);
        $query->orderBy('name', 'ASC');
        $records =$query->get();
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function settings($id) {
        $record = VisitorSetting::where('user_id',$id)->get()->first();
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

    public function updateSetting(Request $request) {

        if($request->id){
            $model = VisitorSetting::find($request->id);        
            $data = $request->all();
            $model->update($data);

            $response = [
                'status'=>true,
                'message'=>'Record updated successfully.'
            ];
        } else {
            $loggedIn = auth()->user();

            if ($request->user_id) {
                $model = new VisitorSetting();
                $model->fill($request->all());
                $model->created_id = $loggedIn->id;
    
                if($model->save()){
                    $record = VisitorSetting::where('user_id',$request->user_id)->get()->first();
                    $response = [
                        'status'=>true,
                        'message'=>'Record added successfully.',
                        'record'=>$record
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
                    'message'=>'Something went wrong. Please try again later.'
                ];
            }  
        }
        return response()->json($response, $this->stausCode);
    }   

}
