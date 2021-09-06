<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Event;
use App\Model\User;
use App\Model\Template;

class EventController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Event::with(['resident' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        if($request->user_id) 
            $query->where("user_id", '=', $request->user_id);
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
            $model = new Event();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;       ;
            $model->access_code = uniqid();

            if($model->save()){
                if($loggedIn->id != $request->user_id){
                    $user = User::find($request->user_id);
                    $name = ucwords($user->first_name.' '.$user->last_name);
                    if($user->email){
                        $mailData = [
                            'templateId'=>11,
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
                            'templateId'=>12,
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
                'message'=>$validator->messages()->get('email')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Event::findBySlug($slug);
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
        $model = Event::findBySlug($slug);        
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
            Event::whereIn('id',$ids)->update($data);
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
        $record = Event::findOrFail($id);
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
        if($ids && Event::destroy($ids)) {
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
        $query = Event::select('id','name');
        if($request->status) $query->where('status', '=', $request->status);
        $query->orderBy('name', 'ASC');
        $records =$query->get();
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

}
