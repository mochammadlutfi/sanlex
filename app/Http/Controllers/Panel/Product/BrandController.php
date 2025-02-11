<?php

namespace App\Http\Controllers\Panel\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Storage;
use Image;

use App\Models\Product\Brand;

class BrandController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Product/Brand');
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
                $data = new Brand();
                $data->name = $request->name;
                if($request->hasFile('image')){
                    $data->image = $this->uploadImage($request->file('image'));
                }
                $data->save();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'success' => false,
                'result' => $e,
            ], 422);
        }

        DB::commit();
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('ProductBrand/Form',[
            'editMode' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data = Brand::where('id', $id)->first();
            $data->name = $request->name;
            if($request->hasFile('image')){
                if(!empty($data->image)){
                    if(Storage::disk('public')->exists($data->image))
                    {
                        Storage::disk('public')->delete($data->image);
                    }
                }
                $data->image = $this->uploadImage($request->file('image'));
            }
            $data->save();


        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'success' => false,
                'result' => $e,
            ], 422);
        }

        DB::commit();
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            
            $data = Brand::where('id', $id)->first();
            if(isset($data->image)){
                $cek = Storage::disk('public')->exists($data->image);
                if($cek)
                {
                    Storage::disk('public')->delete($data->image);
                }
            }
            $data->delete();


        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'success' => false,
                'result' => $e,
            ], 422);
        }

        DB::commit();
        return response()->json([
            'success' => true,
        ], 200);
    }


    private function uploadImage($file){

        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();

        $imgFile = Image::make($file->getRealPath());

        $destinationPath = storage_path('app/public/brands');
        $return = '/uploads/brands/'.$file_name;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $width = 800;
        $heigth = 800;

        $imgFile->resize($width, $heigth, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($destinationPath.'/'.$file_name, 90);

        return $return;
    }

    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';

        $data = Brand::when($request->search, function($q, $search){
            return $q->where('name', 'LIKE', '%'.$search.'%');
        })
        ->withCount('product')->orderBy($sort, $sortDir);

        if($request->limit){
            $output = $data->paginate($request->limit);
        }else{
            $output = $data->get();
        }

        return response()->json($output, 200);
    }
}
