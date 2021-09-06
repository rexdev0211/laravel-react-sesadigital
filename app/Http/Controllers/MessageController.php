<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Message;
use App\Model\User;
use App\Model\Template;
use App\Model\Notification;
use App\Model\SmsLog;
use App\Model\EmailLog;
use App\Model\Estate;

class MessageController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
        ini_set('max_execution_time', '0');
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Message::select("*");
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        if($request->name)  $query->where('name', 'LIKE', '%' .$request->name. '%');
        if($request->status) $query->where('status', '=', $request->status);

        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->pluck('id')->toArray();
            $query->whereIn('estate_id', $estateIds);
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
        $authUser = auth()->user();
        
        $messages = [
            // 'user_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Message();
            $data = $request->all();
            $model->fill($data);
            $model->created_id = $authUser->id;
            $model->channelType = $data['channelType']?implode(',',$data['channelType']):'';
            
            if($model->save()){
                $messageId = $model->id;
                $data['messageId'] = $model->id;
                if($data['triggerType'] == 'send' && $data['status'] == 'active'){
                    $this->sendMessage($data);
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
        $query = Message::where('slug', '=',$slug);
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
        $model = Message::findBySlug($slug);        
        $data = $request->all();
        $data['channelType'] = $data['channelType']?implode(',',$data['channelType']):'';
       
        if($model->update($data)){
            $data['messageId'] = $model->id;
            $data['channelType'] = explode(',',$data['channelType']);
            if($data['triggerType'] == 'send' && $data['status'] == 'active'){
                $this->sendMessage($data);
            }
            $response = [
                'status'=>true,
                'message'=>'Record updated successfully.'
            ];
        } else  {
            $response = [
                'status'=>false,
                'message'=>'Something went wrong. Please try again later.'
            ];
        }
        
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        $record = Message::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    function sendMessage($data){
        $authUser = auth()->user();
        $users = User::select('id','estate_id','first_name','last_name','email','phone','device_token')
            ->where('estate_id',$data['estate_id'])
            ->whereIn('user_type',['guard','resident'])
            ->where('status','active')
            ->get();
        $messageId= $data['messageId'];
        $notifiData = [];
        $smsLogData = [];
        $emailLogData = [];

        if(in_array('sms',$data['channelType'])){
            SmsLog::where('message_id',$messageId)->delete();
        }
        if(in_array('email',$data['channelType'])){
            EmailLog::where('message_id',$messageId)->delete();
        }

        foreach ($users as $key => $value){
            $name = ucwords($value->first_name.' '.$value->last_name);
            if(in_array('in-app',$data['channelType'])){
                $notiData = [
                    'from_id'=>$authUser->id,
                    'estate_id'=>$value->estate_id,
                    'to_id'=>$value->id,
                    'notifi_type'=>'system_message',
                    'title'=>$data['subject'],
                    'message'=>$data['description'],
                    'created_at'=>date('Y-m-d h:m:s'),
                    'updated_at'=>date('Y-m-d h:m:s')
                ];
                array_push($notifiData,$notiData);
            }
            if(in_array('sms',$data['channelType']) && $value->phone){
                    $sLogData = [
                        'estate_id'=>$value->estate_id,
                        'message_id'=>$messageId,
                        'to_id'=>$value->id,
                        'to_name'=>$name,
                        'phone'=>$value->phone,
                        'status'=>'pending',
                        'created_at'=>date('Y-m-d h:m:s'),
                        'updated_at'=>date('Y-m-d h:m:s')
                    ];
                    array_push($smsLogData,$sLogData);
            }
            if(in_array('email',$data['channelType']) && $value->email){
                $eLogData = [
                    'estate_id'=>$value->estate_id,
                    'message_id'=>$messageId,
                    'to_id'=>$value->id,
                    'to_name'=>$name,
                    'email'=>$value->email,
                    'status'=>'pending',
                    'created_at'=>date('Y-m-d h:m:s'),
                    'updated_at'=>date('Y-m-d h:m:s')
                ];
                array_push($emailLogData,$eLogData);
            }
        }
        if(!empty($notifiData))
            Notification::insert($notifiData);
        if(!empty($smsLogData))
            SmsLog::insert($smsLogData);
        if(!empty($emailLogData))
            EmailLog::insert($emailLogData);

        if($messageId && (!empty($smsLogData) || !empty($emailLogData))){
            Message::where('id',$messageId)->update(['status'=>'processing']);
        } else if($messageId && !empty($notifiData)){
            Message::where('id',$messageId)->update(['status'=>'sent']);
        }
    }

    function sendMessage1($data){
        $authUser = auth()->user();
        $users = User::select('id','estate_id','first_name','last_name','email','phone','device_token')
            ->where('estate_id',$data['estate_id'])
            ->whereIn('user_type',['guard','resident'])
            ->where('status','active')
            ->get();
        $messageId= $data['messageId'];
        foreach ($users as $key => $value){
            $name = ucwords($value->first_name.' '.$value->last_name);
            if(in_array('in-app',$data['channelType']) && $value->email){
                $sendData = [
                    'fromId'=>$authUser->id,
                    'estateId'=>$value->estate_id,
                    'toId'=>$value->id,
                    'notifiType' => 'system_message',
                    'token'=>[$value->device_token],
                    'title'=> $data['subject'],
                    'body'=> $data['description']
                ];
                $notification = new Notification();
                $notification->saveNotification($sendData);
            }
            if(in_array('sms',$data['channelType']) && $value->phone){
                    $smsData = [
                        'messageId'=>$messageId,
                        'estateId'=>$value->estate_id,
                        'toId'=>$value->id,
                        'toIdName'=>$name,
                        'phones'=>$value->phone,
                        'message' => $data['description']
                    ];
                    $template = new Template();
                    $template->sendSMSWithoutTemplate($smsData);
            }
            if(in_array('email',$data['channelType']) && $value->email){
                $mailData = [
                    'messageId'=>$messageId,
                    'estateId'=>$value->estate_id,
                    'toId'=>$value->id,
                    'toName'=>$name,
                    'to'=>$value->email,
                    'subject' => $data['subject'],
                    'message' => $data['description']
                ];
                $template = new Template();
                $template->sendEmailWithoutTemplate($mailData);
            }
        }
    }

    public function smsLogs(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = SmsLog::select("*");
        if($request->message_id) $query->where('message_id', $request->message_id);

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

    public function emailLogs(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = EmailLog::select("*");
        if($request->message_id) $query->where('message_id', $request->message_id);

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
