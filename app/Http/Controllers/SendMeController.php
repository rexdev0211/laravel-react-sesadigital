<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Model\SendMe;
use App\Model\SendMeItem;
use App\Model\Estate;
use App\Model\SendMeEstateItem;
use App\Model\SendMeBuyItem;

class SendMeController extends Controller {
   
    public $stausCode = 200;

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = SendMe::select('*');
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
        $authUser = auth()->user();

        $messages = [
            // 'manager_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            // 'manager_id' => 'required',
            'name' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new SendMe();
            $model->fill($request->all());
            $model->created_id = $authUser->id;

            if($model->save()){
                // $lastInsertId=$model->id;
                // if($request->items){
                //     $itemData = [];
                //     foreach($request->items as $item){
                //         if($item->name)
                //             array_push($itemData,[
                //                 'send_me_id'=>$lastInsertId,
                //                 'name'=>$item->name,
                //                 'quantity_type'=>$item->quantity_type,
                //                 'item_price'=>$item->item_price
                //                 ]);
                //     }
                //     ProductInstallment::insert($itemData);
                // }
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
        $record = SendMe::findBySlug($slug);
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
        $record = SendMe::findBySlug($slug);        
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
            SendMe::whereIn('id',$ids)->update($data);
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
        $record = SendMe::findOrFail($id);
        $record->delete();
        $response = [
            'status'=>true,
            'message'=>'Record deleted successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function destroyAll(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && SendMe::destroy($ids)) {
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

    public function items(Request $request, $sendMeId) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = SendMeItem::select('*');
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->status) $query->where('status', '=', $request->status);

        if($request->sendMeId) $query->where('send_me_id', $request->sendMeId);

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
            $model = new SendMeItem();
            $model->fill($request->all());

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
        $record = SendMeItem::findBySlug($slug);        
        $data = $request->all();        
        $record->update($data);
        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function get_details($slug) {
        $record = SendMeItem::findBySlug($slug);
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
            SendMeItem::whereIn('id',$ids)->update($data);
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
        if($ids && SendMeItem::destroy($ids)) {
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

    public function getSendMeWithList(Request $request, $slug) {
        $record = SendMe::findBySlug($slug);

        if($record){
            $data['record'] = $record;
            $data['items'] = SendMeItem::select(DB::raw("CONCAT(name,' | ',item_price, ' | ', quantity_type) AS display_name"),'id')
            ->where('send_me_id', $record->id)
            ->where('status', 'active')
            ->pluck('display_name','id');

            $data['estates'] = Estate::where('status', 'active')->pluck('name','id');

            $response = [
                'status'=>true,
                'data'=>$data
            ];
        } else {
            $response = [
                'status'=>true,
                'record'=>'Invalid request'
            ];
        }

        return response()->json($response, $this->stausCode);
    }

    //estates
    public function estates(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = SendMeEstateItem::select('*');
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['send_me' => function ($q)  {
            $q->select('id','name');
        }]);
        

        if($request->semd_me_id) $query->where('send_me_id', $request->semd_me_id);

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);

        $sendMeItemIds = array();
        foreach ($records as $record) {
            foreach(explode(',',$record->send_me_item_id) as $itemId){
                $sendMeItemIds[] = $itemId;
            }
        }

        $items = [];
        if(!empty($sendMeItemIds)){
            $items = SendMeItem::select(DB::raw("CONCAT(name,' | ',item_price, ' | ', quantity_type) AS display_name"),'id')
            ->whereIn('id', $sendMeItemIds)
            ->pluck('display_name','id');
        }
        
        $response = [
            'status'=>true,
            'record'=>$records,
            'items'=>$items
        ];
        return response()->json($response, $this->stausCode);
    }

    public function estate_add(Request $request) {
        
        $messages = [
            // 'manager_id' => 'This field is required',
        ];

        $validator = Validator::make($request->all(),[
            // 'manager_id' => 'required',
            'estate_id' => 'required',
        ],$messages);

        if ($validator->passes()) {
            $model = new SendMeEstateItem();
            // $model->fill($request->all());
            $model->estate_id= $request->estate_id;
            $model->send_me_id= $request->send_me_id;
            $model->send_me_item_id= implode(',',$request->send_me_item_id);
            $available_on = [];
            if($request->available_on){
                foreach($request->available_on as $value){
                    $date = $value['year'].'/'.$value['month']['number'].'/'.$value['day'];
                    array_push($available_on,$date);
                }
            }
            $model->available_on= implode(',',$available_on);

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
                'message'=>$validator->messages()->get('estate_id')
            ];
        }       
            
        return response()->json($response, $this->stausCode);
    }

    public function estate_edit(Request $request, $id) {
        $record = SendMeEstateItem::find($id);        
        $data = $request->all();        
        
        $data['send_me_item_id']= implode(',',$request->send_me_item_id);
        $available_on = [];
        if($request->available_on){
            foreach($request->available_on as $value){
                if(is_array($value))
                    $date = $value['year'].'/'.$value['month']['number'].'/'.$value['day'];
                else
                    $date = $value;
                array_push($available_on,$date);
            }
        }
        $data['available_on']= implode(',',$available_on);

        $record->update($data);

        $response = [
            'status'=>true,
            'message'=>'Record updated successfully.'
        ];
        return response()->json($response, $this->stausCode);
    }

    public function get_estateDetails($sendMeestateId) {
        $record = SendMeEstateItem::find($sendMeestateId);
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

    public function destroyAllEstate(Request $request) {
        $ids = array_filter($request->ids);
        if($ids && SendMeEstateItem::destroy($ids)) {
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
    public function getItems(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }

        $query = SendMeEstateItem::select('*');
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->with(['send_me' => function ($q)  {
            $q->select('id','name');
        }]);        

        if($request->semd_me_id) $query->where('send_me_id', $request->semd_me_id);

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);

        $sendMeItemIds = [];
        foreach ($records as $record) {
            foreach(explode(',',$record->send_me_item_id) as $itemId){
                $sendMeItemIds[] = $itemId;
            }
        }

        $items = [];
        if(!empty($sendMeItemIds)){
            $itemRecords = SendMeItem::select('*')
            ->whereIn('id', $sendMeItemIds)
            ->get();

            foreach ($itemRecords as $value){
                $items[$value->id]=$value;
            }
        }
        
        $response = [
            'status'=>true,
            'record'=>$records,
            'items'=>$items
        ];
        return response()->json($response, $this->stausCode);
    }

    public function getBuyItems(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }

        $query = SendMeBuyItem::select('*');
        $query->with(['estate' => function ($q)  {
            $q->select('id','name');
        }]);      
        $query->with(['user' => function ($q)  {
            $q->select('id',DB::raw("CONCAT(first_name,' ',last_name) AS name"));
        }]);    

        if($request->semd_me_id) $query->where('send_me_id', $request->semd_me_id);

        $sortBy = 'id'; $orderBy = 'DESC';
        if($request->sortBy && $request->orderBy){
            $sortBy = $request->sortBy; $orderBy = $request->orderBy;
        }
        $query->orderBy($sortBy, $orderBy);
        $records = $query->paginate($this->perPage);

        // $records = [];
        // foreach ($results as $result) {
        //     if(!array_key_exists($result->user_id, $result)){
        //         $records[$result->user_id] = [];
        //     }
        //     $records[$result->user_id][] = $result;
        // }

        $response = [
            'status'=>true,
            'record'=>$records
        ];
        return response()->json($response, $this->stausCode);
    }

    public function saveBuyItems(Request $request) {
        $data = $request->all();
        if (count($data)>0) {
            $authUser = auth()->user();
            foreach ($data as $key => $value){
                $model = new SendMeBuyItem();
                $model->fill($value);
                $model->user_id = $authUser->id;
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

}
