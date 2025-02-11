<?php

namespace App\Http\Controllers\Panel\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Product\PackagingRequest;
use Carbon\Carbon;
use Storage;
use Image;

use App\Models\Product\Packaging;

class PackagingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Product/Packaging');
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackagingRequest $request)
    {
        DB::beginTransaction();
        try{
                $data = new Packaging();
                $data->name = $request->name;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PackagingRequest $request, $id)
    {
        DB::beginTransaction();
        try{
                
            $data = Packaging::where('id', $id)->first();
            $data->name = $request->name;
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
            'success' => true
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
            
            $data = Packaging::where('id', $id)->first();
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
            'success' => true
        ], 200);    
    }

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';

        $data = Packaging::when($request->search, function($q, $search){
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
