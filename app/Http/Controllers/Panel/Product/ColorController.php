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

use App\Models\Product\Color;
use App\Models\Product\ColorGroup;

class ColorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Product/Color');
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
                $data = new Color();
                $data->code = $request->code;
                $data->name = $request->name;
                $data->hex = $request->hex;
                $data->is_special = $request->is_special;
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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
                $data = Color::where('id', $id)->first();
                $data->code = $request->code;
                $data->name = $request->name;
                $data->hex = $request->hex;
                $data->is_special = $request->is_special;
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
            
            $data = Color::where('id', $id)->first();
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

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';

        $data = Color::when($request->search, function($q, $search){
            return $q->where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('code', 'LIKE', '%'.$search.'%');
        })
        ->withCount('product')->orderBy($sort, $sortDir);

        if($request->limit){
            $output = $data->paginate($request->limit);
        }else{
            $output = $data->get();
        }

        return response()->json($output);
    }

    private function importfix()
    {
        DB::beginTransaction();
        try{
            
            $db = DB::table('hiyoto_color_collection')
            ->where('color_collection_set_id', 2)
            ->get();
            foreach($db as $d){
                $data = new ColorGroup();
                $data->product_id = 1;
                $data->color_id = $d->color_code_id;
                $data->save();
            }

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
        }

        DB::commit();
        // return redirect()->route('admin.product.color.index');
        dd("Done");
    }
}
