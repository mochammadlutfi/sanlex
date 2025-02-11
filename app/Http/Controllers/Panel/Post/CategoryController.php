<?php

namespace App\Http\Controllers\Panel\Post;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


use App\Models\Post\Category;
use Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        return Inertia::render('Post/Category');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'locale' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Kategori Wajib Diisi!',
            'locale.required' => 'Bahasa Wajib Diisi!'
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $locale = $request->locale;

                $data = new Category();
                $data->parent_id = $request->parent_id;
                if(!empty($request->thumbnail))
                {
                    $thumbPath = 'c/thumbnail/';
                    $thumbName = $thumbPath . uniqid() . '.jpg';
                    $image_parts = explode(";base64,", $request->thumbnail);
                    $thumb = base64_decode($image_parts[1]);
                    $p = Storage::disk('umum')->put($thumbName, $thumb);
                    $data->thumbnail = $thumbName;
                }
                $data->save();

                $data->translateOrNew($locale)->title = $request->name;
                $data->translateOrNew($locale)->description = $request->description;
                $data->save();

                Category::fixTree();
            }catch(\QueryException $e){
                DB::rollback();
                // return back()->withErrors($validator->errors());
                dd($e);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'title' => 'required',
            'en_title' => 'required',
        ];

        $pesan = [
            'title.required' => 'Nama Kategori Wajib Diisi!',
            'en_title.required' => 'Category Name Must Be Filled!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }else{
            DB::beginTransaction();
            try{
                $data = Category::find($request->kategori_id);
                $data->translateOrNew('id')->title = $request->input('title');
                $data->translateOrNew('en')->title = $request->input('en_title');
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
            
            $data = Category::where('id', $id)->first();
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
        }

        DB::commit();
        return redirect()->route('admin.post.category.index');
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

        $id = $request->id;
        $search = $request->search;

        $query = Category::when($id, function($query, $id){
            $query->where('post.id', '=', $id);
        })
        ->when($search, function($query, $search){
            $query->where('post.title', '=', $search);
        })->withCount(['post'])->withDepth()->defaultOrder();
        
        // $traverse = function ($categories, $prefix = '-') use (&$traverse) {
        //     foreach ($categories as $category) {
        //         echo PHP_EOL.$prefix.' '.$category->title .'<br/>';
        
        //         $traverse($category->children, $prefix.'-');
        //     }
        // };

        // $data = $traverse($query);

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

    public function sub(Request $request)
    {
        $kategori = Category::where('parent_id', $request->category_id)->get();
        return response()->json($kategori);
    }

}
