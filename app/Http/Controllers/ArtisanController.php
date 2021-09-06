<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;

use App\Model\Artisan;
use App\Model\ArtisanLinkedCategory;
use App\Model\Estate;
use App\Model\ArtisanRating;

class ArtisanController extends Controller {
   
    public $stausCode = 200;
    public $uploadDir = 'artisans';

    public function __construct() {
        parent::__construct();
    }
        
    public function index(Request $request) {
        if($request->perPage && $request->perPage>0){
            $this->perPage = $request->perPage;
        }
        $query = Artisan::with(['artisanLinkedCategory.artisanCategory' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->withCount(['artisanRating' => function($query) {
            $query->select(DB::raw('ROUND(coalesce(avg(rating),0),2)'));
        }]);
        if($request->name)  $query->where("name", 'LIKE', '%' .$request->name. '%');
        if($request->status) $query->where('status', '=', $request->status);
        if($request->artisan_category_id) 
            $query->whereHas('artisanLinkedCategory.artisanCategory', function ($q) use($request) {
                $q->whereIn('id', $request->artisan_category_id);
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
            $model = new Artisan();
            $model->fill($request->all());
            $model->created_id = $loggedIn->id;

            if ($file = $request->file('photo')){
                $photo_name = $this->str_random(6).$request->file('photo')->getClientOriginalName();
                $file->move('assets/uploads/'.$this->uploadDir,$photo_name);
                $model['photo'] = $this->uploadDir."/".$photo_name;
            }

            if($model->save()){
                $artisanId = $model->id;
                if($request->artisan_category_id) {
                    $artisanId = $model->id;
                    $artisanCategoryId = explode(',',$request->artisan_category_id);
                    foreach ($artisanCategoryId as $arCatid) {
                        $alcModel = new ArtisanLinkedCategory();
                        $catData = [
                            'created_id'=>$loggedIn->id,
                            'artisan_id'=>$artisanId,
                            'artisan_category_id'=>$arCatid,
                        ];
                        $alcModel->fill( $catData );
                        $alcModel->save();
                    }
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
        $loggedIn = auth()->user();
        $query = Artisan::with(['artisanLinkedCategory.artisanCategory' => function ($q)  {
            $q->select('id','name');
        }]);
        $query->withCount(['artisanRating' => function($query) {
            $query->select(DB::raw('ROUND(coalesce(avg(rating),0),2)'));
        }]);
        $record = $query->where('slug','=',$slug)->get()->first();

        $myRating = ArtisanRating::where([
            ['user_id','=',$loggedIn->id],
            ['artisan_id','=',$record->id],
        ])->get()->first();

        if($record) 
            $response = [
                'status'=>true,
                'record'=>$record,
                'myRating'=>$myRating
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
        $loggedIn = auth()->user();
        $record = Artisan::findBySlug($slug);        
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
        $alcRecords = ArtisanLinkedCategory::where('artisan_id',$record->id)->get()->toArray();
        if($alcRecords) {
            $alcids = array_column($alcRecords, 'id');
            ArtisanLinkedCategory::destroy($alcids);
        }
        if($request->artisan_category_id) {
            $artisanCategoryId = explode(',',$request->artisan_category_id);
            foreach ($artisanCategoryId as $arCatid) {
                $catData = [
                    'created_id'=>$loggedIn->id,
                    'artisan_id'=>$record->id,
                    'artisan_category_id'=>$arCatid,
                ];
                $alcModel = new ArtisanLinkedCategory();
                $catData['created_id'] = $loggedIn->id;
                $alcModel->fill( $catData );
                $alcModel->save();
            }
        }
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
            Artisan::whereIn('id',$ids)->update($data);
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
        $record = Artisan::findOrFail($id);
        if($record->photo){
            $oldFilePath = config('site_vars.publicPath').$record->photo;
            if(file_exists($oldFilePath)){
                @unlink($oldFilePath);
            }
        }
        $alcRecords = ArtisanLinkedCategory::where('artisan_id',$record->id)->get()->toArray();
        if($alcRecords) {
            $alcids = array_column($alcRecords, 'id');
            ArtisanLinkedCategory::destroy($alcids);
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
        if($ids && Artisan::destroy($ids)) {
            $alcRecords = ArtisanLinkedCategory::whereIn('artisan_id',$record->id)->get()->toArray();
            if($alcRecords) {
                $alcids = array_column($alcRecords, 'id');
                ArtisanLinkedCategory::destroy($alcids);
            }
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

    public function groupLists(Request $request) {
        $query = Artisan::select('id','name');
        $query->with(['artisanLinkedCategory.artisanCategory' => function ($q)  {
            $q->select('id','name');
        }]);
        if($request->status) $query->where('status', '=', $request->status);
        $query->orderBy('name', 'ASC');
        $record['artisans'] =$query->get();    
        
        $query = Estate::select('id','name');
        if($request->status) $query->where('status', '=', $request->status);
        $query->orderBy('name', 'ASC');
        $record['estates'] = $query->get();  

        $response = [
            'status'=>true,
            'record'=>$record
        ];
        return response()->json($response, $this->stausCode);
    }

}
