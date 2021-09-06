<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\Good;
use App\Model\GoodItem;
use App\Model\GoodBuyItem;

class GoodController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'goods';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Good::select('*');
        if($request->type)  $query->where("type", $request->type);
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->item_type)  $query->where("item_type", 'LIKE', '%' .$request->item_type. '%');
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
        $authUser = auth()->user();

        $messages = [
            // 'manager_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            // 'manager_id' => 'required',
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new Good();
            $model->fill($request->all());
            $model->created_id = $authUser->id;

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
                'message'=>$validator->messages()->get('name')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function show($slug) {
        $record = Good::findBySlug($slug);
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
        $record = Good::findBySlug($slug);        
        $data = $request->all();        
        $record->update($data);
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
            Good::whereIn('id',$ids)->update($data);
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
        $record = Good::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && Good::destroy($ids)) {
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

    public function items(Request $request, $goodId) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = GoodItem::select('*');
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->status) $query->where('status', '=', $request->status);

        if($request->goodId) $query->where('good_id', $request->goodId);

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

    public function item_add(Request $request) {
        $messages = [
            // 'manager_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            // 'manager_id' => 'required',
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new GoodItem();
            $model->fill($request->all());
            if ($file = $request->file('image')){
                $photo_name = $this->str_random(6).$request->file('image')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model->image = $this->uploadDir."/".$photo_name;
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
                'message'=>$validator->messages()->get('name')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function item_edit(Request $request, $slug) {
        $record = GoodItem::findBySlug($slug);        
        $data = $request->all();   
        if (isset($data['image'])){
            $file = $data['image'];
            $photo_name = $this->str_random(6).$data['image']->getClientOriginalName();
            $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
            $data['image'] = $this->uploadDir.'/'.$photo_name;
            if($record->image){
                $oldFilePath = config('site_vars.publicPath').$record->image;
                if(file_exists($oldFilePath)){
                    @unlink($oldFilePath);
                }
            }
        } else {
            $data['image'] = $record->image;
        }     
        $record->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function get_details($slug) {
        $record = GoodItem::findBySlug($slug);
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

    public function itemChangeStatus(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            $data=['status'=>$request->status];
            GoodItem::whereIn('id',$ids)->update($data);
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

    public function destroyAllItem(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && GoodItem::destroy($ids)) {
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

    //from user panel
    public function getGoods(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }

        $query = Good::with(['goodItems' => function ($q)  {
            $q->where('status','active');
        }]);

        $query->where('status','active');

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

    public function buyGoods(Request $request) {
        $data = $request->all();
        if (count($data)>0) {
            $authUser = auth()->user();
            foreach ($data as $key => $value){
                $model = new GoodBuyItem();
                $model->fill($value);
                $model->user_id = $authUser->id;
                $model->estate_id = $authUser->estate_id;
                $model->save();
            }

            $response = [
                'status'=>true,
                'message'=>'Record added successfully.'
            ];
        } else {
            $response = [
                'status'=>false,
                'message'=>'Invalid request!'
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function purchased(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }

        $query = GoodBuyItem::with('good');
        $query->with('goodItem');
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]); 
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);

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

    public function purchased1(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }

        $query = Good::with(['goodItems' => function ($q)  {
            $q->where('status','active');
        }]);
        $query->with('goodItems.goodBuyItems');
        $query->with(['goodItems.goodBuyItems.estate' => function ($q)  {
            $q->select('id','name');
        }]); 
        $query->with(['goodItems.goodBuyItems.user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);

        $query->whereHas('goodItems.goodBuyItems', function ($q) use($request) {
            $q->where('id','!=','');
        });

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

    public function buyItemChangeStatus(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && count($ids)>0){
            $data=['status'=>$request->status];
            GoodBuyItem::whereIn('id',$ids)->update($data);
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
