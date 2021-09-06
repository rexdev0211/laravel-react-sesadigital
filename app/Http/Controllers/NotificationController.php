<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Notification;
use App\Model\User;
use App\Model\Template;
use App\Model\NextOfKin;
use App\Model\Estate;

class NotificationController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'notifications';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Notification::with(['from' => function ($q)  {
            $q->select('id','first_name','last_name','photo');
        }]);
        // if(!in_array($authUser->user_type,['superadmin','admin'])){
        //     $query->where("to_id", '=', $request->to_id);
        // }
        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->pluck('id')->toArray();
            $query->where(function($q) use($estateIds,$request) {
                $q->where(function($subq) use($estateIds,$request) {
                    $subq->whereIn('estate_id',$estateIds);
                    $subq->whereIn('notifi_type',['panic','contact','system_message']);
                });
                $q->orWhere('to_id',$request->to_id);
            });
        } else if($authUser->user_type == 'company'){
            $estateIds = Estate::where('company_id',$authUser->id)->pluck('id')->toArray();
            $query->where(function($q) use($estateIds,$request) {
                $q->where(function($subq) use($estateIds,$request) {
                    $subq->whereIn('estate_id',$estateIds);
                    $subq->whereIn('notifi_type',['system_message']);
                });
                $q->orWhere('to_id',$request->to_id);
            });
        } else if($authUser->user_type == 'guard'){
            $query->where(function($q) use($authUser,$request) {
                $q->where(function($subq) use($authUser,$request) {
                    $subq->where('estate_id',$authUser->estate_id);
                    $subq->whereIn('notifi_type',['panic','contact','system_message']);
                });
                $q->orWhere('to_id',$request->to_id);
            });
        } else if($authUser->user_type == 'resident'){
            $query->where(function($q) use($request) {
                $q->orWhere('to_id',$request->to_id);
            });
        }
        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        if(!in_array($authUser->user_type,['superadmin','admin'])){
            $query->update(['is_seen'=>1]);
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
            // 'manager_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            // 'manager_id' => 'required',
            'artisan_id' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Notification();
            $model->fill($request->all());       ;
            $model->slug = $loggedIn->id."-".time();

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
                'message'=>$validator->messages()->get('artisan_id')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($id) {
        $record = Notification::find($id);
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
        $authUser = auth()->user();
        if(!in_array($authUser->user_type,['superadmin','admin'])){
            $model = Notification::find($id);        
            $data = $request->all();
            $model->update($data);
        }
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        $record = Notification::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Notification::destroy($ids)) {
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

    public function getLatest(Request $request) {
        $this->perPage = 10;
        $authUser = auth()->user();

        $query = Notification::with(['from' => function ($q)  {
            $q->select('id','first_name','last_name','photo');
        }]);
        // $query->where('to_id', $request->to_id);
        $query->where('is_seen', $request->is_seen);
        if($authUser->user_type == 'estate_manager'){
            $estateIds = Estate::where('manager_id',$authUser->id)->pluck('id')->toArray();
            $query->where(function($q) use($estateIds,$request) {
                $q->where(function($subq) use($estateIds,$request) {
                    $subq->whereIn('estate_id',$estateIds);
                    $subq->whereIn('notifi_type',['panic','contact','system_message']);
                });
                $q->orWhere('to_id',$request->to_id);
            });
        } else if($authUser->user_type == 'company'){
            $estateIds = Estate::where('company_id',$authUser->id)->pluck('id')->toArray();
            $query->where(function($q) use($estateIds,$request) {
                $q->where(function($subq) use($estateIds,$request) {
                    $subq->whereIn('estate_id',$estateIds);
                    $subq->whereIn('notifi_type',['system_message']);
                });
                $q->orWhere('to_id',$request->to_id);
            });
        } else if($authUser->user_type == 'guard'){
            $query->where(function($q) use($authUser,$request) {
                $q->where(function($subq) use($authUser,$request) {
                    $subq->where('estate_id',$authUser->estate_id);
                    $subq->whereIn('notifi_type',['panic','contact','system_message']);
                });
                $q->orWhere('to_id',$request->to_id);
            });
        } else if($authUser->user_type == 'resident'){
            $query->where(function($q) use($request) {
                $q->orWhere('to_id',$request->to_id);
            });
        }

        $sortBy = 'id'; $orderBy = 'DESC';
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);
        
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function saveToken(Request $request) {
        $loggedIn = auth()->user();
        if($loggedIn){
            $model = User::where('id',$loggedIn->id)->update(['device_token'=>$request->token]);
                
            $response = [
                'status'=>true,
                'message'=>'Token saved successfully.'
            ];
        } else {
            $response = [
                'status'=>false,
                'message'=>'Not saved.'
            ];
        }
        return response()->json($response);
    }

    public function sendNotification(Request $request) {
        $firebaseToken = User::whereNotNull('device_token')
                        ->where('user_type'!='p-0')
                        ->pluck('device_token')
                        ->all();
          
        $SERVER_API_KEY = 'AAAA3o7KZ98:APA91bF1RrRdEqpiyhB3t50Pwzn1pKtOGxwjsIY40JR_LNC5v4q0YPoX0Xa6Go_evjKLIKw3X2-_12f42r28WhuRv2UeyB8ImJDThFswMkk0RllX_MJR61O5LdEVhoo092jpZWxaB0eS';
  
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ],
            "data" => [
                "badge"=> 1,
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
        return $response;
    }

    //Panic alert
    public function panicAlert(Request $request){
        $authUser = auth()->user();

        $estate = Estate::with(['estateManager' => function ($q)  {
            $q->select('id','first_name','last_name','email');
        }])->where('id','=',$authUser->estate_id)->get()->first();
        if($estate){

            $location='';
            $ip = \Request::ip();
            // $ip="157.38.83.221";
            $locationData = \Location::get($ip);
        
            if($locationData){ //need to work here
                if($locationData->cityName) $location .= $locationData->cityName.", ";
                if($locationData->regionName) $location .= $locationData->regionName.", ";
                if($locationData->regionName) $location .= $locationData->regionName.", ";
                if($locationData->countryName) $location .= $locationData->countryName;
                if($location) $location .= " - ";
                if($locationData->zipCode) $location .= $locationData->zipCode;

                $locationData= json_encode($locationData);
            }

            $model = new Notification();
            $model['from_id']=$authUser->id;
            $model['estate_id']=$authUser->estate_id;
            $model['notifi_type']='panic';
            $model['title']='Panic Alert';
            $model['message']='A panic alert has been initiated by '. ucwords($authUser->first_name." ".$authUser->last_name);
            $model['location']=$location;
            $model['location_json']=$locationData;
            $model->save();
            
            $date = date('Y-m-d');
            $time = date('h:i A');

            if($estate->estateManager && $estate->estateManager->email){
                $manager = $estate->estateManager;
                $mailData = [
                    'templateId'=>5,
                    'to'=>$manager->email,
                    'tags' => [
                        '{NAME}'=> ucwords($manager->first_name.' '.$manager->last_name),
                        '{ESTATE}'=>$estate->name,
                        '{LOCATION}'=>$location,
                        '{DATE}'=>$date,
                        '{TIME}'=>$time,
                    ]
                ];
                $template = new Template();
                $template->sendEmail($mailData);
            }

            $nextOfKin = NextOfKin::where('assign_panic_alert','=',1)->get()->first();
            $nextOfKinName = ucwords($nextOfKin->first_name.' '.$nextOfKin->last_name);
            if($nextOfKin && $nextOfKin->email){
                $mailData = [
                    'templateId'=>5,
                    'to'=>$nextOfKin->email,
                    'tags' => [
                        '{NAME}'=> $nextOfKinName,
                        '{ESTATE}'=>$estate->name,
                        '{LOCATION}'=>$location,
                        '{DATE}'=>$date,
                        '{TIME}'=>$time,
                    ]
                ];
                $template = new Template();
                $template->sendEmail($mailData);
            }
            if($nextOfKin && $nextOfKin->phone){
                $smsData = [
                    'templateId'=>4,
                    'estateId'=>$estate->id,
                    'toId'=>$nextOfKin->id,
                    'toIdName'=>$nextOfKinName,
                    'phones'=>$nextOfKin->phone,
                    'tags' => [
                        '{NAME}'=> $nextOfKinName,
                        '{ESTATE}'=>$estate->name
                    ]
                ];
                $template = new Template();
                $template->sendSMS($smsData);
            }
            $response = [
                'status'=>true,
                'message'=>'Please wait, an action is being taken on your panic alert.'
            ];
        } else {
            $response = [
                'status'=>false,
                'message'=>'Estate details are not found in the system.'
            ];
        }

        return response()->json($response, $this->stausCode);
    }

    //Contact estate
    public function contactEstate(Request $request){
        $authUser = auth()->user();

        $query = Estate::with(['estateManager' => function ($q)  {
            $q->select('id','first_name','last_name','email');
        }]);
        $query->with(['gaurds' => function ($q)  {
            $q->select('id','first_name','last_name','email','phone');
            $q->where('status','active');
        }]);
        $query->where('id','=',$authUser->estate_id)->get()->first();
        $estate = $query->get()->first();

        if($estate){
            $title = $request->title;
            $description = $request->description;
            
            $model = new Notification();
            $model['from_id']=$authUser->id;
            $model['estate_id']=$authUser->estate_id;
            $model['notifi_type']='contact';
            $model['contact_category']=$request->contact_category;
            $model['title']=$title;
            $model['message']=$description;
            $category="Contact Us/Feedbck";
            if($request->contact_category==1){
                $model['is_show_guard']=1;
                $category="Report an Incident/Threat";
            }
            if ($file = $request->file('photo')){
                $photo= [];
                foreach ($file as $key => $value){
                    $photoName = $this->str_random(6).$request->file('photo')[$key]->getClientOriginalName();
                    $value->move('assets/uploads/'.$this->uploadDir,$photoName);
                    $photo[$key] = $this->uploadDir."/".$photoName;
                }
                $model['photo']=implode(',',$photo);
            }
            
            $model->save();

            if($estate->estateManager && $estate->estateManager->email){
                $manager = $estate->estateManager;
                $mailData = [
                    'templateId'=>13,
                    'to'=>$manager->email,
                    'tags' => [
                        '{NAME}'=> ucwords($manager->first_name.' '.$manager->last_name),
                        '{ESTATE}'=>$estate->name,
                        '{CATEGORY}'=>$category,
                        '{TITLE}'=>$request->title,
                        '{MESSAGE}'=>$request->description,
                    ]
                ];
                $template = new Template();
                $template->sendEmail($mailData);
            }

            if($estate->gaurds){
                foreach($estate->gaurds as $value){
                    if($value->email){
                        $mailData = [
                            'templateId'=>13,
                            'to'=>$value->email,
                            'tags' => [
                                '{NAME}'=> ucwords($value->first_name.' '.$value->last_name),
                                '{ESTATE}'=>$estate->name,
                                '{CATEGORY}'=>$category,
                                '{TITLE}'=>$title,
                                '{MESSAGE}'=>$description,
                            ]
                        ];
                        $template = new Template();
                        $template->sendEmail($mailData);
                    }
                    if($value && $value->phone){
                        $smsData = [
                            'templateId'=>14,
                            'estateId'=>$estate->id,
                            'toId'=>$value->id,
                            'toIdName'=>ucwords($value->first_name.' '.$value->last_name),
                            'phones'=>$value->phone,
                            'tags' => [
                                '{NAME}'=> ucwords($value->first_name.' '.$value->last_name),
                                '{ESTATE}'=>$estate->name
                            ]
                        ];
                        $template = new Template();
                        $template->sendSMS($smsData);
                    }
                }
            }

            $response = [
                'status'=>true,
                'message'=>'Please wait, an action is being taken on your contact query.'
            ];
        } else {
            $response = [
                'status'=>false,
                'message'=>'Estate details are not found in the system.'
            ];
        }

        return response()->json($response, $this->stausCode);
    }
}
