<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use App\Models\Post\Post;
use App\Models\Post\Category;

class PostController extends Controller
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
        $search = $request->s;
        $locale = ($request->locale) ? $request->locale : 'id';

        $query = Post::with(['category'])
        ->when($id, function($query, $id){
            $query->where('post.id', '=', $id);
        })
        ->when($search, function($query, $search){
            $query->where('title', 'LIKE', '%' . $search . '%');
        })
        ->translatedIn($locale);
        
        // if($limit == 1){
        //     $data = $query->first();
        // }else{
            if($request->page){
                $data = $query->paginate($limit);
            }else{
                $data = $query->get();
            }
        // }
        
        return response()->json($data, 200);
    }


    public function show(Request $request, $slug)
    {
        App::setLocale($request->locale);
        $data = Post::whereTranslation('slug', $slug)->translated()->firstOrFail();
        // if ($post->translate()->where('slug', $slug)->first()->locale != LaravelLocalization::getCurrentLocale()) {
        //     return redirect()->route('post.show', $post->translate()->slug);
        // }
        
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
