<?php

namespace App\Http\Controllers\Panel\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Media;
use App\Models\Post\Post;
class PostController extends Controller
{
    /**
     * Only Authenticated users for "admin" guard
     * are allowed.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Post/Index');
    }

    public function create()
    {
        return Inertia::render('Post/Form');
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
        ];

        $pesan = [
            'title.required' => 'Judul Berita Wajib Diisi!',
            'category_id.required' => 'Kategori Berita Wajib Diisi!'
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();

            try{

                $data = new Post();
                $data->category_id = $request->category_id;

                $data->seo_keyword = $request->seo_keyword;
                $data->seo_description = $request->seo_description;
                $data->seo_tags = $request->seo_tags;
                $data->status = $request->status;
                $data->save();

                if(!empty($request->media))
                {
                    $media = Media::where('id', $request->media['id'])->first();

                    $data->attachMedia($media, 'thumbnail');
                }

                $data->translateOrNew('id')->title = $request->input('title');
                $data->translateOrNew('id')->description = $request->description;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }
            DB::commit();
            return redirect()->route('admin.post.index');
        }
    }

    public function edit($id)
    {
        $data = Post::where('id', '=', $id)->withMedia('thumbnail')->first();
        $data->translation = $data->getTranslationsArray();
        
        return Inertia::render('Post/Form',[
            'editMode' => true,
            'value' => $data
        ]);
    }

    public function update($id, Request $request)
    {
        $rules = [
            'title' => 'required',
            'category_id' => 'required',
        ];

        $pesan = [
            'title.required' => 'Judul Berita Wajib Diisi!',
            'category_id.required' => 'Kategori Berita Wajib Diisi!'
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();

            try{
                $data = Post::where('id', $id)->first();

                if(!empty($request->media))
                {
                    $media = Media::where('id', $request->media['id'])->first();
                    $data->syncMedia($media, 'thumbnail');
                }

                $data->category_id = $request->category_id;

                $data->seo_keyword = $request->seo_keyword;
                $data->seo_description = $request->seo_description;
                $data->seo_tags = $request->seo_tags;
                $data->status = $request->status;

                $data->translateOrNew('id')->title = $request->input('title');
                $data->translateOrNew('id')->description = $request->description;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }
            DB::commit();
            return redirect()->route('admin.post.index');
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $cek = Storage::disk('public')->exists($post->featured_img);
        if($cek)
        {
            Storage::disk('public')->delete($post->featured_img);
        }
        $hapus_db = Post::destroy($post->id);
        if($hapus_db)
        {
            return response()->json([
                'fail' => false,
            ]);
        }

    }

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->s;
        $company_id = auth()->user()->company_id;

        $query = Post::with(['category'])
        ->when($search, function($query, $search){
            $query->where('title', 'LIKE', '%' . $search . '%');
        });
        
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
