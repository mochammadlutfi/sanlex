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

use App\Models\Product\Product;
use App\Models\Product\Color;
use App\Models\Product\ColorGroup;

class PaletteController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($productId)
    {
        $product = Product::where('id', $productId)->first();

        return Inertia::render('Product/Palette', [
            'product' => $product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($productId, Request $request)
    {
        // dd($request->all());
        $rules = [
            'color_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $team = Product::findOrFail($productId);
                $team->color()->attach($request->color_id);

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.product.palette.index', ['productId' => $productId]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId, $id)
    {
        DB::beginTransaction();
        try{
            
            $data = Product::findOrFail($productId);
            $data->color()->detach($id);

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
        }

        DB::commit();
        return redirect()->route('admin.product.palette.index', ['productId' => $productId]);
    }

    
    public function data($productId, Request $request)
    {
        $page = $request->page;
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $type = $request->type;

        $data = Color::whereHas('product', function($q) use($productId){
            return $q->where('id', $productId);
        })->orderBy($sort, $sortDir);

        // $test = Product::withCount('color')->where('id', 1)->first();
        // dd($data->get());

        if($page){
            $output = $data->paginate($limit);
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
