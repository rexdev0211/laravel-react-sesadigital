<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

use App\Model\User;
use App\Model\Template;

class AdminController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'profiles';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $stausCode = 200;
        $query = User::select('*');
        $query->with('managerEstates');
        $query->with('companyEstates');
        
        $query->where('user_type', '=', $request->user_type);
        if($request->name){
            $query->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'like', $request->name);
        }
        if($request->email) $query->where('email', '=', $request->email);
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
        return response()->json($response, $stausCode);
    }

    public function create() {
        
    }

    public function store(Request $request) {
        $stausCode = 200;
        $loggedInUser = auth()->user();
        $messages = [
            'email.unique' => 'This email is already exists',
        ];

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|max:255|unique:users',
        ],$messages);

        if ($validator->passes()) {
            $model = new User();
            $model->fill($request->all());
            $model->created_id = $loggedInUser->id;
            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model['photo'] = $this->uploadDir."/".$photo_name;
            }

            $password = mt_rand(99999, 999999);
            $model['password'] = Hash::make($password);

            if($model->save()){                
                $lastInsertId=$model->id;
                
                // $loggedIn = auth()->user();
                // if($model->phone){
                //     $smsData = [
                //         'templateId'=>10,
                //         'userId'=>$loggedIn->id,
                //         'toId'=>$lastInsertId,
                //         'toIdName'=>ucwords($model->first_name.' '.$model->last_name),
                //         'phones'=>$model->phone,
                //         'tags' => [
                //             '{NAME}'=> ucwords($model->first_name.' '.$model->last_name),
                //             '{USERNAME}'=>$model->email,
                //             '{PASSWORD}'=>$password
                //         ]
                //     ];
                //     $template = new Template();
                //     $template->sendSMS($smsData);
                // }
                if($model->email){
                    $mailData = [
                        'templateId'=>1,
                        'to'=>$model->email,
                        'tags' => [
                            '{NAME}'=> ucwords($model->first_name.' '.$model->last_name),
                            '{USERNAME}'=>$model->email,
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
            
        return response()->json($response, $stausCode);
    }

    public function show($slug) {
        // $record = User::findBySlug($slug);
        $query = User::with('managerEstates');
        $query->with('companyEstates');
        $query->where('slug',$slug);
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
        $response = [
            'message'=>'Record updated successfully.'
        ];
        return $this->successResponse($response);
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

    public function list(Request $request) {
        $query = User::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id', 'resident_code');
        if($request->user_type) $query->where('user_type','=',$request->user_type);
        $records = $query->get();
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function listWithType(Request $request) {
        $adminType = config('site_vars.adminType');
        unset($adminType['superadmin']);
        
        $query = User::select('id', DB::raw("CONCAT(first_name,' ',last_name) AS name"),'user_type');
        $query->whereIn('user_type',array_keys($adminType));
        $records = $query->get();
        foreach($records as $key => $value){
            $records[$key]['nameWithType'] = $value->name." (".$adminType[$value->user_type].")";
        }
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

}
