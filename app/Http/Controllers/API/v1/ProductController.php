<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Product\Brand;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';

        $id = $request->id;
        $search = $request->s;
        $locale = $request->locale  ?? 'id';

        $query = Product::with(['brand', 'category'])
        ->when($request->id, function($query, $id){
            $query->where('product.id', '=', $id);
        })
        ->when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%');
        });
        
        if($request->limit){
            if($request->limit == 1){
                $data = $query->first();
            }else{
                $data = $query->paginate($request->limit);
            }
        }else{
            $data = $query->get();
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil',
            'payload' => $data
        ], 200);
    }


    public function show(Request $request, $slug)
    {
        App::setLocale($request->locale);
        $data = Product::
        where('slug', $slug)
        ->with(['color', 'brand', 'category'])
        ->first();
        
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function category(Request $request)
    {
        
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->search;
        $tree = $request->tree;
        $parentonly = $request->parentonly;
        // dd($request->all());
        App::setLocale($request->locale);

        $query = Category::when($id, function($query, $id){
            $query->where('id', '=', $id);
        })
        ->when($search, function($query, $search){
            $query->where('product.name', '=', $search);
        })
        ->when($parentonly, function($query, $parentonly){
            $query->whereNull('parent_id');
        })
        ->withCount(['product']);

        if($limit == 1){
            $data = $query->first();
        }else{
            if($request->page){
                $data = $query->paginate($limit);
            }elseif($tree == 1){
                $q2 = $query->get()->toTree();
                $data = $q2->map(function ($d) {
                    return collect($d->toArray())
                        ->only(['id', 'title', 'slug', 'parent_id', 'children'])
                        ->all();
                });
            }else{
                $data = $query->get();
            }
        }
        
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function brand(Request $request)
    {
        
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->search;

        $query = Brand::when($id, function($query, $id){
            $query->where('id', '=', $id);
        })
        ->when($search, function($query, $search){
            $query->where('product.name', '=', $search);
        })
        ->withCount(['product']);

        if($limit == 1){
            $data = $query->first();
        }else{
            if($request->page){
                $data = $query->paginate($limit);
            }else{
                $data = $query->get();
            }
        }
        
        return response()->json($data);
    }
}
