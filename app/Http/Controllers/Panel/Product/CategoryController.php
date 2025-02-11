<?php

namespace App\Http\Controllers\Panel\Product;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Helpers\BaseHelper;

use App\Models\Product\Category;
use Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return Inertia::render('Product/Category');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            $data = new Category();
            $data->parent_id = $request->parent_id;
            if($request->hasFile('image')) {
                $data->image = BaseHelper::uploadFile($request->file('image'), '/uploads/categories', true);
            }
            $data->save();

            $data->translateOrNew($request->locale)->name = $request->name;
            $data->translateOrNew($request->locale)->description = $request->description;
            $data->save();

            Category::fixTree();

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

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Category::where('id', $id)->first();
            $data->parent_id = $request->parent_id;
            if($request->hasFile('image')) {
                if(Storage::disk('public')->exists($data->image))
                {
                    Storage::disk('public')->delete($data->image);
                }
                $data->image = BaseHelper::uploadFile($request->file('image'), '/uploads/categories', true);
            }
            $data->translateOrNew($request->locale)->name = $request->name;
            $data->translateOrNew($request->locale)->description = $request->description;
            $data->save();
            
        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => $e,
            ]);
        }
        DB::commit();
        return response()->json([
            'fail' => false,
        ]);
    }

    public function edit($id){
        $data = Category::find($id);
        $respon = [
            "id" => $data->id,
            "title" => $data->translate('id')->title,
            "en_title" => $data->translate('en')->title,
            "thumbnail" => $data->thumbnail_url,
        ];
        return response()->json($respon);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $data = Category::where('id',$id)->first();
            if(Storage::disk('public')->exists($data->image))
            {
                Storage::disk('public')->delete($data->image);
            }
            $data->delete();
            
        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'pesan' => $e,
            ]);
        }
        DB::commit();
        return response()->json([
            'fail' => false,
        ]);
    }

    public function tree(Request $request)
    {
        $data = Category::latest()->defaultOrder()->get()->toTree();
        $items = $data->map(function ($data) {
            return collect($data->toArray())
                ->only(['id', 'title', 'parent_id', 'children'])
                ->all();
        });

        return response()->json($items);
    
    }

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $query = Category::when($request->id, function($query, $id){
            $query->where('product.id', '=', $id);
        })
        ->when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%'.$search.'%');
        })->withCount(['product'])->withDepth()->defaultOrder();

        if($request->limit){
            $data = ($request->limit == 1 ) ? $query->first() : $query->paginate($request->limit);
        }else{
            $data = $query->get();
        }
        
        return response()->json($data);
    }

    public function sub(Request $request)
    {
        $kategori = Category::where('parent_id', $request->category_id)->get();
        return response()->json($kategori);
    }

}
