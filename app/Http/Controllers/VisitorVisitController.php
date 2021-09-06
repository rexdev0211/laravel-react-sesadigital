<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\VisitorVisit;
use App\Model\User;
use App\Model\Notification;
use App\Model\Template;
use App\Model\Visitor;

class VisitorVisitController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'visitors';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        // $loggedIn = auth()->user();
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = VisitorVisit::with(['user' => function ($q)  {
            $q->select('id','first_name','last_name','phone','photo','house_code','address','slug');
        }]);
        $query->with(['visitInBy' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        $query->with(['visitOutBy' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        if($request->visit_type !== 'walk-in') {
            $query->with(['visitor' => function ($q)  {
                $q->select('id','name','phone','access_code','message','slug','status');
            }]);
            if($request->visitor_name || $request->visitor_phone) 
                $query->whereHas('visitor', function ($q) use($request) {
                    if($request->visitor_name) $q->where('name', 'LIKE', '%' . $request->visitor_name. '%');
                    if($request->visitor_phone) $q->where('phone', '=', $request->visitor_phone);
                });
        }
        if($request->estate_id) $query->where("estate_id", '=', $request->estate_id);
        if($request->user_id) $query->where("user_id", '=', $request->user_id);
        if($request->visit_type) $query->where("visit_type", '=', $request->visit_type);
        if($request->tag_number) $query->where("tag_number", '=', $request->tag_number);
        if($request->name) $query->where('name', 'LIKE', '%' . $request->name. '%');
        if($request->phone) $query->where('phone', '=', $request->phone);
        

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; 
            $orderBy = $request->orderBy;
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
        $response = [
            'status'=>false,
            'message'=>'Invalid request. Please try again later.'
        ];

        if($request->user_id){
            $user = User::find($request->user_id);
            if($user){
                $status = $request->status;
                $loggedIn = auth()->user();
                $today = date('Y-m-d h:i:s');
                $model = new VisitorVisit();
                $model->fill($request->all());
                $model->estate_id = $user->estate_id;
                $model->visit_in_by_id = $loggedIn->id;
                $model->visit_in_at = $today;
                if ($file = $request->file('photo')){
                    $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                    $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                    $model->photo = $this->uploadDir."/".$photo_name;
                }
                if($model->save()){
                    if($user->email){
                        $mailData = [
                            'templateId'=>($status === "pending") ? 6 : 7,
                            'to'=>$user->email,
                            'tags' => [
                                '{NAME}'=> ucwords($user->first_name.' '.$user->last_name),
                                '{VISITOE_NAME}'=>$request->name,
                                '{PHONE}'=>$request->phone,
                                '{MESSAGE}'=>$request->message,
                                '{DATE}'=>$today,
                            ]
                        ];
                        $template = new Template();
                        //$template->sendEmail($mailData);
                    }
                    $sendData = [
                        'fromId'=>$loggedIn->id,
                        'estateId'=>$user->estate_id,
                        'toId'=>$user->id,
                        'notifiType' => 'walk-in',
                        'token'=>[$user->device_token],
                        'title'=> 'Walk-in request created',
                        'body'=> 'A request is generated for '.ucwords($request->name).' to visit you.'
                    ];
                    $notification = new Notification();
                    $notification->saveNotification($sendData);
                    $response = [
                        'status'=>true,
                        'message'=>'Walk in request is submitted successfully.'
                    ];
                }
            }
        }
        return response()->json($response, $this->stausCode);
    }

    public function show($id) {
        $query = VisitorVisit::with(['user' => function ($q)  {
            $q->select('id','first_name','last_name','phone','photo','house_code','address','slug');
        }]);
        $query->with(['visitInBy' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        $query->with(['visitOutBy' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        $query->with(['visitor' => function ($q)  {
            $q->select('id','name','phone','access_code','message','slug','status');
        }]);

        $query->where('id','=',$id);
        
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

    public function update(Request $request, $id) {
        $loggedIn = auth()->user();
        $model = VisitorVisit::find($id); 
        $data = $request->all();
        $model->update($data);
        $response = [
            'status'=>true,
            'message'=>'Thank you! You are all set to go.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroy($id) {
        
    }

    public function checkVisit(Request $request) {
        if(empty($request->access_code)){
            $response = [
                'status'=>false,
                'message'=>'Access code is required field.'
            ];
        } else if(empty($request->phone)){
            $response = [
                'status'=>false,
                'message'=>'Phone number is required field.'
            ];
        } else {
            $response = [ 'status'=>false, 'message'=>'Invalid access code and phone number.' ];

            $record = Visitor::with('resident')->where([
                ['access_code','=',$request->access_code],
                ['phone','=',$request->phone],
            ])->get()->first();
            if($record){
                $response = [ 'status'=>false, 'message'=>'Access code is not valid.' ];

                $today = date('Y/m/d');
                $accessLevelDate = explode(',',$record->access_level_date);
                // echo '<pre>';print_r($accessLevelDate);exit;
                if(in_array($today,$accessLevelDate) || ($record->access_level=="date_range" && $accessLevelDate[0]>=$today && $accessLevelDate[1]<=$today)){
                    $visitRecord = [];
                    if($record->access_type=="single"){
                        $visitRecord = VisitorVisit::where([
                            ['visitor_id','=',$record->id],
                        ])->whereNotNull('visit_in_by_id')->whereNull('visit_out_by_id')->orderBy('id','DESC')->get();
                    }
                   if(count($visitRecord)<=0){
                        $response = [
                            'status'=>true,
                            'record'=>$record,
                            'message'=>'Access code and phone number is varified.'
                        ];
                   }
                }
            }
        }
        return response()->json($response, $this->stausCode);
    }

    public function checkIn(Request $request) {
        if(empty($request->access_code)){
            $response = [
                'status'=>false,
                'message'=>'Access code is required field.'
            ];
        } else if(empty($request->phone)){
            $response = [
                'status'=>false,
                'message'=>'Phone number is required field.'
            ];
        } else if(empty($request->tag_number)){
            $response = [
                'status'=>false,
                'message'=>'Tag number is required field.'
            ];
        } else {
            $loggedIn = auth()->user();

            $model = new VisitorVisit();
            $model->fill($request->all());
            // echo '<pre>';print_r($request->all());
            // exit;
            $model->visit_in_by_id = $loggedIn->id;
            $model->visit_in_at = date('Y-m-d h:i:s');

            if($model->save()){
                $response = [
                    'status'=>true,
                    'message'=>'Visitor checked in successfully.'
                ];
            } else {
                $response = [
                    'status'=>false,
                    'message'=>'Something went wrong. Please try again later.'
                ]; 
            }
        }
        return response()->json($response, $this->stausCode);
    }

    public function checkOut(Request $request) {
        $loggedIn = auth()->user();
        $id = $request->id?$request->id:0;

        $model = VisitorVisit::find($id); 
        $data = [
            "visit_out_by_id" => $loggedIn->id,
            "visit_out_at" => date('Y-m-d h:i:s')
        ];
        $model->update($data);
        
        $query = VisitorVisit::with(['user' => function ($q)  {
            $q->select('id','first_name','last_name','phone','photo','house_code','address','slug');
        }]);
        $query->with(['visitInBy' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        $query->with(['visitOutBy' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        
        $record = $query->where('id','=',$id)->first();
        
        $response = [
            'status'=>true,
            'record'=>$record,
            'message'=>'Check out successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function changeStatus(Request $request) {
        $response = [
            'status'=>false,
            'message'=>'Invalid request. Please try later again.'
        ];
        $id = $request->id;
        if($id){
            $visit = VisitorVisit::with(['user'])->where('id','=',$id)->first();
            $user = $visit->user;
            $status = $request->status;
            $data=$request->all();
            $visit->update($data);
            $sendData = [
                'fromId'=>$user->id,
                'estateId'=>$user->estate_id,
                'notifiType' => 'walk-in',
                'title'=> 'Walk-in approved',
                'body'=> 'Walk-in request has been approved for'.ucwords($visit->name)
            ];
            if($status === 'decline'){
                $sendData['title'] = 'Walk-in declined';
                $sendData['body'] = 'Walk-in request has been declined for'.ucwords($visit->name);
            } 
            $notification = new Notification();
            $notification->saveNotification($sendData);

            $response = [
                'status'=>true,
                'message'=>'Walk-in request has been approved successfully.'
            ];
        }
        return response()->json($response, $this->stausCode);
    }

}
