<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

use App\Model\Estate;
use App\Model\User;
use App\Model\Template;

class EstateController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'estates';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        $authUser = auth()->user();
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Estate::with(['estateManager' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        $query->with(['securityCompany' => function ($q)  {
            $q->select('id','first_name','last_name');
        }]);
        if($request->manager_id) 
            $query->whereHas('estateManager', function ($q) use($request) {
                $q->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' . $request->manager_id. '%');
            });
        if($request->company_id) 
            $query->whereHas('securityCompany', function ($q) use($request) {
                $q->where(DB::raw('CONCAT_WS(" ", first_name, last_name)'), 'LIKE', '%' . $request->company_id. '%');
            });
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->email) $query->where('email', '=', $request->email);
        if($request->phone) $query->where('phone', '=', $request->phone);
        if($request->status) $query->where('status', '=', $request->status);

        if($authUser->user_type == 'estate_manager'){
            $query->where('manager_id', $authUser->id);
        }
        if($authUser->user_type == 'company'){
            $query->where('company_id', '=', $authUser->id);
        }

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);
        $response = [
            'status'=>true,
            'record'=>$records,
            'userId'=>$authUser->id
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
            $model = new Estate();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;
            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model['photo'] = $this->uploadDir."/".$photo_name;
            }

            $model['company_id'] = strtoupper($request->company_id)=='NULL' ? null : $request->company_id;

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
                'message'=>$validator->messages()->get('email')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Estate::findBySlug($slug);
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
        $model = Estate::findBySlug($slug);        
        $data = $request->all();
        if (isset($data['photo'])){
            $file = $data['photo'];
            $photo_name = $this->str_random(6).$data['photo']->getClientOriginalName();
            $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
            $data['photo'] = $this->uploadDir.'/'.$photo_name;
            if($model->photo){
                $oldFilePath = config('site_vars.publicPath').$model->photo;
                if(file_exists($oldFilePath)){
                    @unlink($oldFilePath);
                }
            }
        } else {
            $data['photo'] = $model->photo;
        }
        if(isset($request->company_id))
            $data['company_id'] = strtoupper($request->company_id)=='NULL' ? null : $request->company_id;

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
            $data=[];
            if(isset($request->is_signout_required)){
                $data=['is_signout_required'=>$request->is_signout_required];
            } else {
                $data=['status'=>$request->status];
            }
            
            Estate::whereIn('id',$ids)->update($data);
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
        $record = Estate::findOrFail($id);
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
        if($ids && Estate::destroy($ids)) {
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
        $authUser = auth()->user();
        $query = Estate::select('id','name','phone');
        if($request->status) $query->where('status', '=', $request->status);
        if($request->manager_id) $query->where('manager_id','=',$request->manager_id);
        if($authUser->user_type == 'estate_manager')
            $query->where('manager_id', '=', $authUser->id);
        if($authUser->user_type == 'company')
            $query->where('company_id', '=', $authUser->id);
        $query->orderBy('name', 'ASC');
        $records =$query->get();
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function getEstateRelatedLists(Request $request) {
        $query = User::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id');
        $query->where('user_type', '=', 'estate_manager');
        $query->orderBy('name', 'ASC');
        $estateManagers =$query->get();

        $query = User::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id');
        $query->where('user_type', '=', 'company');
        $query->orderBy('first_name', 'ASC');
        $comapanies =$query->get();
        $records = [
            'estateManagers'=>$estateManagers,
            'comapanies'=>$comapanies,
        ];
        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function updateEstateGuards(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            if($request->actionType === 'assign')
                $data['estate_id']=$request->estate_id;
            else 
                $data['estate_id']=null;
            User::whereIn('id',$ids)->update($data);
            $records = User::whereIn('id',$ids)->get();
            foreach($records as $record){
                if($record->email){
                    $mailData = [
                        'templateId'=>3,
                        'to'=>$record->email,
                        'tags' => [
                            '{ACTION_TYPE}'=> $request->actionType,
                            '{NAME}'=> ucwords($record->first_name.' '.$record->last_name),
                            '{ESTATE}'=>$record->username,
                            '{DATE}'=>date(config('site_vars.dateFormat')),
                        ]
                    ];
                    $template = new Template();
                    $template->sendEmail($mailData);
                }
            }
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
