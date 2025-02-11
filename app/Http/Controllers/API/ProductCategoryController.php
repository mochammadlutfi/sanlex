<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use App\Models\Product\Product;
use App\Models\Product\Category;
class ProductCategoryController extends Controller
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
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->search;
        $parent_id =  ($request->parent_id) ? $request->parent_id : null;
        $parent = $request->parent;
        $locale = $request->locale ??  \LaravelLocalization::getCurrentLocale();
        App::setLocale($locale);

        $query = Category::when($id, function($query, $id){
            $query->where('product.id', '=', $id);
        })
        ->when($search, function($query, $search){
            $query->where('product.name', '=', $search);
        })
        // ->when($parent_id, function($query, $parent_id){
        //     $query->where('parent_id', '=', $parent_id);
        // })
        // ->when($parent, function($query){
        //     $query->where('parent_id', '=', null);
        // })
        ->withCount(['product'])
        ->withDepth()
        ->defaultOrder();

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


    public function show(Request $request, $slug)
    {
        App::setLocale($request->locale);
        $data = Product::
        where('slug', $slug)
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
        
        $data = Category::latest()->defaultOrder()->get()->toTree();
        $items = $data->map(function ($data) {
            return collect($data->toArray())
                ->only(['id', 'title', 'parent_id', 'children'])
                ->all();
        });

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
