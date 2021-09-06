<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

use App\Model\User;
use App\Model\Estate;
use App\Model\Template;
use App\Model\NextOfKin;
use App\Model\Notification;
use App\Model\Role;
use App\Import;

class UserController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'profiles';

    public function __construct() {
        parent::__construct();
    }


    public function getNumResidents(Request $request) {
        $authUser = auth()->user();

        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }

        $house_code = $request->house_code;

        $results = DB::select( DB::raw("SELECT * FROM users WHERE user_type = 'resident' AND house_code = '".$request->house_code."' ") );
        $numAlpha = count($results);



         $response = [
            'status'=>true,
            'record'=> $numAlpha
        ];
        return response()->json($response, $this->stausCode);
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();

        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        //company_id
        $query = User::with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['securityCompany' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        $query->with(['role' => function ($q)  {
            $q->select('id','name');
        }]);
        if($request->company_name) 
            $query->whereHas('securityCompany', function ($q) use($request) {
                $q->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' . $request->company_name. '%');
            });
        // if($request->estate_id) 
        //     $query->whereHas('estate', function ($q) use($request) {
        //         $q->where('name', 'LIKE', '%' .$request->estate_id. '%');
        //     });
        $query->where('user_type', '=', $request->user_type);
       

        if($request->name)  $query->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' .$request->name. '%'); 
        if($request->estate_id) $query->where('estate_id', '=', $request->estate_id);
        if($request->email) $query->where('email', '=', $request->email);
        if($request->phone) $query->where('phone', '=', $request->phone);
        if($request->status) $query->where('status', '=', $request->status);
        if($request->resident_id) $query->where('resident_id', '=', $request->resident_id);
        if($request->house_code) $query->where('house_code', '=', $request->house_code);

        if($request->resident_category) $query->where('resident_category', '=', $request->resident_category);
        if($request->address) $query->where("address", 'LIKE', '%' .$request->address. '%'); 
        if($request->resident_type) $query->where('resident_type', '=', $request->resident_type);
        if($request->gender) $query->where('gender', '=', $request->gender);

        if($request->company_id) $query->where('company_id', '=', $request->company_id);
        else {
            if($request->user_type == 'guard'){
                if($authUser->user_type == 'estate_manager')
                    $query->where('manager_id', $authUser->id);
                if($authUser->user_type == 'company')
                    $query->where('company_id', $authUser->id);
            } else {
                if($authUser->user_type == 'estate_manager'){
                    $estateIds = Estate::where('manager_id',$authUser->id)->pluck('id')->toArray();
                    $query->whereIn('estate_id', $estateIds);
                }
                if($authUser->user_type == 'company'){
                    $estateIds = Estate::where('company_id',$authUser->id)->pluck('id')->toArray();
                    $query->whereIn('estate_id', $estateIds);
                }
            }
        }

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);

        //add number of user added by resident
        foreach ($records as $value) {
            $results = DB::select( DB::raw("SELECT * FROM users WHERE user_type = 'user' AND resident_id = '".$value->id."' ") );
           $value->res_num_user = count($results);

        }

        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }


    public function getNumUsers(Request $request){
        
        $num_usr_allowed = 0;

         $results = DB::select( DB::raw("SELECT id,num_of_users FROM estates WHERE id = '".$request->user_no_residents."' ") );

        $response = [
            'status'=>true,
            'result'=>$results[0]
        ];
        return response()->json($response, $this->stausCode);
    }       


    public function create() {
        
    }

    public function store(Request $request) {
        $authUser = auth()->user();
        $messages = [
            'email.unique' => 'This email is already exists',
        ];

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255|unique:users',
        ],$messages);

        if ($validator->passes()) {
            $lastResidentCode = 0000;
            if($request->resident_type == "resident"){
                  $user = User::select('resident_code')->where('user_type','=','user')->orWhere('user_type','=','resident')->orderBy('id', 'DESC')->first();
                $lastResidentCode = $user?(int)$user->resident_code:0;
            }

            $username = $this->createUsername($request->first_name);
            $password = mt_rand(99999, 999999);

            $houseCode = $request->first_name;
           // $estate_id = $request->estate_id;


            //getEstateName
            //$estates = DB::select( DB::raw("SELECT name FROM estates WHERE id = '".$estate_id."' ") );
            //$estLbl = ucfirst($estates[0]->name);
            $user = new User();
            $user->fill($request->all());
            //$user->house_code = substr($estLbl,0,4)."-".$request->house_code;
            $user->resident_code = sprintf("%04d", $lastResidentCode+1); 
            $user->created_id = $authUser->id;
            $user->username = $username;
            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $user['photo'] = $this->uploadDir."/".$photo_name;
            }

            $user['password'] = Hash::make($password);

            if($authUser->user_type == 'estate_manager'){
                $user['manager_id'] = $authUser->id;
            }
            if($authUser->user_type == 'company'){
                $user['company_id'] = $authUser->id;
            }

            if($user->save()){
                $lastInsertId=$user->id;
                if($user->phone){
                    $smsData = [
                        'templateId'=>10,
                        'userId'=>$authUser->id,
                        'toId'=>$lastInsertId,
                        'toIdName'=>ucwords($user->first_name.' '.$user->last_name),
                        'phones'=>$user->phone,
                        'tags' => [
                            '{NAME}'=> ucwords($user->first_name.' '.$user->last_name),
                            '{USERNAME}'=>$username,
                            '{PASSWORD}'=>$password
                        ]
                    ];
                    $template = new Template();
                    $template->sendSMS($smsData);
                }
                if($user->email){
                    $mailData = [
                        'templateId'=>1,
                        'to'=>$user->email,
                        'tags' => [
                            '{NAME}'=> ucwords($user->first_name.' '.$user->last_name),
                            '{USERNAME}'=>$username,
                            '{PASSWORD}'=>$password,
                        ]
                    ];
                    $template = new Template();
                    $template->sendEmail($mailData);
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
                'message'=>$validator->messages()->get('email')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        // $record = User::findBySlug($slug);
        $record = User::with(['estate' => function ($q)  {
            $q->select('id','name');
        }])->where('slug', '=',$slug)->first();
        if($record) 
            $response = [
                'status'=>true,
                'record'=>$record
            ];
        else 
            $response = [
                'status'=>false,
                'record'=>'No record found Baby'
            ];
        return response()->json($response, $this->stausCode);
    }
    
    public function edit($id) {
        //
    }

    public function update(Request $request, $slug) {
        $record = User::findBySlug($slug);
        if(!$record)
            return $this->errorResponse('Record not found');

        $messages = [
            'email.unique' => 'This email is already exists',
        ];

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255|unique:users,email,'.$record->id,
        ],$messages);
        if($validator->fails())
            return $this->errorResponse($validator->messages()->get('email'));

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

        $record->update($data);

        $user = User::findBySlug($slug);    
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.',
            'myProfile' => $user
        ];
        return response()->json($response, $this->stausCode);
    }

    public function changeStatus(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            $data=['status'=>$request->status];
            User::whereIn('id',$ids)->update($data);
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

    public function resetProfile(Request $request) {
        $record = User::find($request->id); 
        if($record && $record->id>0){
            $password = mt_rand(99999, 999999);
            $data=[
                'has_password_updated'=>0,
                'password' => Hash::make($password)
            ];
            $record->update($data);
            $username = $record->username;
            $adminType = config('site_vars.adminType');
            if(array_key_exists($record->user_type,$adminType)){
                $username = $record->email;
            }
            if($record->email){
                $mailData = [
                    'templateId'=>2,
                    'to'=>$record->email,
                    'tags' => [
                        '{NAME}'=> ucwords($record->first_name.' '.$record->last_name),
                        '{USERNAME}'=>$username,
                        '{PASSWORD}'=>$password,
                    ]
                ];
                $template = new Template();
                $template->sendEmail($mailData);
            }
            $response = [
                'status'=>true,
                'message'=>'Record has been reset successfully.'
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
        $record = User::findOrFail($id);
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
        if($ids && User::destroy($ids)) {
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

    public function changePassowrd(Request $request, $slug) {
        if($request->currentPassword == $request->newPassword){
            $response = [
                'status'=>false,
                'message'=>'New passowrd can not be same as current password.'
            ];
        } else {
            $record = User::findBySlug($slug);
            if ( ! Hash::check($request->currentPassword, $record->password, [])) {
                $response = [
                    'status'=>false,
                    'message'=>'Incorrect current password.'
                ];
            } else {
                $data['password'] = Hash::make($request->newPassword);
                $data['has_password_updated'] = 1;
                $record->update($data);
                $response = [
                    'status'=>true,
                    'message'=>'Passowrd has been changed successfully.'
                ];
            }
        }
        return response()->json($response, $this->stausCode);
    }

    public function getResidentRelatedLists(Request $request) {
        $authUser = auth()->user();
        $query = Estate::select('id','name');
        if($authUser->user_type == 'estate_manager'){
            $query->where('manager_id', '=', $authUser->id);
        }
        if($authUser->user_type == 'company'){
            $query->where('company_id', '=', $authUser->id);
        }
        $query->orderBy('name', 'ASC');
        $estates =$query->get();

        $records = [
            'estates'=>$estates,
        ];
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function getSecurityGuardRelatedLists(Request $request) {
        $query = User::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id');
        $query->where('user_type', '=', 'company');
        $query->orderBy('name', 'ASC');
        $comapnies =$query->get();

        $records = [
            'comapnies'=>$comapnies,
        ];
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function addUser(Request $request) {
        $loggedIn = auth()->user();
        $messages = [
            // 'email.unique' => 'This email is already exists',
        ];

        $validator = Validator::make($request->all(),[
            // 'email' => 'required|email|max:255|unique:users',
        ],$messages);

        if ($validator->passes()) {
            
            $user = User::select('resident_code')->where('user_type','=','user')->orWhere('user_type','=','resident')->orderBy('id', 'DESC')->first();

            $lastResidentCode = $user?(int)$user->resident_code:0;
            $user = new User(); 
            $user->resident_code = sprintf("%04d", $lastResidentCode+1); 

            $user->fill($request->all());
            $user->created_id = $loggedIn->id;
            $username = $this->createUsername($request->first_name);
            $user->username = $username;
            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model['photo'] = $this->uploadDir."/".$photo_name;
            }

            // $password = mt_rand(99999, 999999);
            // $user['password'] = Hash::make($password);

            if($user->save()){
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
                'message'=>$validator->messages()->get('email')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function editUser(Request $request, $slug) {
        $record = User::findBySlug($slug);        
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

        $record->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }
    
    //bulk import 
    public function import(Request $request){
        ini_set('max_execution_time', '0');
        $data = Excel::toArray(new Import, $request->file('file'));
        $data = $data[0];
        $userType = $request->user_type;
        $loggedIn = auth()->user();
        $response = [
            'status'=>false,
            'message'=>'Invalid details in file. Please make it correct and proceed further.'
        ];
        $isVliadData = true;
        $phones = $emails = [];
        foreach ($data as $value){
            if($userType == "estate_manager"){
                if(!isset($value["firstName"]) || !isset($value["lastName"]) || !isset($value["phone"]) || !isset($value["email"])){
                    $isVliadData = false;
                    break;
                } else if(trim($value["firstName"]) == "" || trim($value["lastName"]) == "" || trim($value["phone"]) == "" || trim($value["email"]) == ""){
                    $isVliadData = false;
                    break;
                }
            } else if($userType == "guard"){
                if(!isset($value["firstName"]) || !isset($value["lastName"]) || !isset($value["phone"]) || !isset($value["address"]) || !isset($value["gender"]) || !isset($value["email"]) || !isset($value["dob"])){
                    $isVliadData = false;
                    break;
                } else if(trim($value["firstName"]) == "" || trim($value["lastName"]) == "" || trim($value["phone"]) == "" || trim($value["gender"]) == "" || trim($value["email"]) == ""){
                    $isVliadData = false;
                    break;
                }
            } else {
                if(!isset($value["firstName"]) || !isset($value["lastName"]) || !isset($value["phone"]) || !isset($value["address"]) || !isset($value["gender"]) || !isset($value["email"]) || !isset($value["houseCode"]) || !isset($value["residentCategory"]) || !isset($value["residentStatus"]) || !isset($value["residentType"])){
                    $isVliadData = false;
                    break;
                } else if(trim($value["firstName"]) == "" || trim($value["lastName"]) == "" || trim($value["phone"]) == "" || trim($value["gender"]) == "" || trim($value["email"]) == "" || trim($value["houseCode"]) == "" || trim($value["residentCategory"]) == "" || trim($value["residentStatus"]) == "" || trim($value["residentType"]) == ""){
                    $isVliadData = false;
                    break;
                }
            }

            if(trim($value["phone"])){
                array_push($phones,trim($value["phone"]));
            } else if(trim($value["email"])){
                array_push($emails,trim($value["email"]));
            } 
        }
        if($isVliadData && count($phones)>0){
            $isPhoneExists = User::whereIn('phone',$phones)->get()->count();
            if($isPhoneExists>0) {
                $isVliadData = false;
                $response = [
                    'status'=>false,
                    'message'=>'Invalid details in file. Some phone numbers already exists in system.'
                ];
            }
        }
        if($isVliadData && count($emails)>0){
            $isEmailExists = User::whereIn('email',$emails)->get()->count();
            if($isEmailExists>0) {
                $isVliadData = false;
                $response = [
                    'status'=>false,
                    'message'=>'Invalid details in file. Some emails are already exists in system.'
                ];
            }
        }
        $lastResidentCode = 0000;
        if($isVliadData && $userType == "resident"){
            $user = User::select('resident_code')->where('user_type','=',$userType)->orderBy('id', 'DESC')->first();
            $lastResidentCode = (int)$user->resident_code;
        }
        if($isVliadData){
            foreach ($data as $value){
                $model = new User();
                $model->user_type = $userType;
                $model->created_id = $loggedIn->id;
                $name = "sesa";
                if(trim($value["lastName"])) $model->last_name = $name = $value["lastName"];
                if(trim($value["firstName"])) $model->first_name = $name = $value["firstName"];
                if(trim($value["phone"])) $model->phone = $value["phone"];
                if(trim($value["email"])) $model->email = $value["email"];
                if($userType != "estate_manager"){
                    if(trim($value["address"])) $model->address = $value["address"];
                    if(trim($value["gender"])) $model->gender = strtolower(trim($value["gender"]));

                    if($isVliadData && $userType == "guard"){
                        if(trim($value["dob"])) $model->dob = date("Y-m-d",strtotime($value["dob"]));
                    }else if($isVliadData && $userType == "resident"){
                        $model->resident_code = sprintf("%04d", $lastResidentCode+1); 
                        if(trim($value["houseCode"])) $model->house_code = $value["houseCode"];
                        if(trim($value["residentCategory"])) $model->resident_category = $value["residentCategory"];
                        if(trim($value["residentStatus"])) $model->resident_status = $value["residentStatus"];
                        if(trim($value["residentType"])) $model->resident_type = $value["residentType"];
                    }
                }
                
                $username = $this->createUsername($name);
                $password = mt_rand(99999, 999999);
    
                $model->created_id = $loggedIn->id;
                $model->username = $username;
                $model->password = Hash::make($password);
                $model->status = 'active';
    
                if($model->save()){
                    if($userType == "estate_manager"){
                        $username = $value["email"];
                    }
                    $lastInsertId=$model->id;
                    if($value["phone"]){
                        $smsData = [
                            'templateId'=>10,
                            'userId'=>$loggedIn->id,
                            'toId'=>$lastInsertId,
                            'toIdName'=>ucwords($value["firstName"]." ".$value["lastName"]),
                            'phones'=>$value["phone"],
                            'tags' => [
                                '{NAME}'=> ucwords($name),
                                '{USERNAME}'=>$username,
                                '{PASSWORD}'=>$password
                            ]
                        ];
                        $template = new Template();
                        $template->sendSMS($smsData);
                    }

                    if($value["email"]){
                        $mailData = [
                            'templateId'=>1,
                            'to'=>$value["email"],
                            'tags' => [
                                '{NAME}'=> ucwords($value["firstName"]." ".$value["lastName"]),
                                '{USERNAME}'=>$username,
                                '{PASSWORD}'=>$password,
                            ]
                        ];
                        $template = new Template();
                        $template->sendEmail($mailData);
                    }
                }
                
            }
            $response = [
                'status'=>true,
                'message'=>'Record added successfully.'
            ];
        }        
        return response()->json($response, $this->stausCode);
    }

    //next of kins
    public function nextofKins(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        
        $query = NextOfKin::with(['relationship' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['user' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        if($request->name)  $query->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' .$request->name. '%'); 
        if($request->user_id) $query->where('user_id', '=', $request->user_id);
        if($request->email) $query->where('email', '=', $request->email);
        if($request->phone) $query->where('phone', '=', $request->phone);

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

    public function addNextofKin(Request $request) {
        $loggedIn = auth()->user();
        $messages = [
            // 'email.unique' => 'This email is already exists',
        ];

        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new NextOfKin();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;
            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model['photo'] = $this->uploadDir."/".$photo_name;
            }

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
                'message'=>$validator->messages()->get('first_name')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function editNextofKin(Request $request, $slug) {
        $record = NextOfKin::findBySlug($slug);        
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

        $record->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function nextofKinsDelete(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && NextOfKin::destroy($ids)) {
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

    public function assignPanicAlert(Request $request) {
        $id = $request->id;
        if($id){
            $data=['assign_panic_alert'=>$request->assign_panic_alert];
            NextOfKin::where('id','=',$id)->update($data);
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

}
