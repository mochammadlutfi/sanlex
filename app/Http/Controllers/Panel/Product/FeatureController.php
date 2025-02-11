<?php

namespace App\Http\Controllers\Panel\Product;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Image;

use App\Models\Product\Feature;
use Storage;

class FeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return Inertia::render('Product/Features');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'name_en' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Fitur Wajib Diisi!',
            'name_en.required' => 'Nama Fitur Inggris Wajib Diisi!'
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

                $data = new Feature();
                if($request->hasFile('image')){
                    $data->image = $this->uploadImage($request->file('image'));
                }
                $data->translateOrNew('id')->name = $request->name;
                $data->translateOrNew('en')->name = $request->name_en;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                // return back()->withErrors($validator->errors());
                dd($e);
            }
            DB::commit();
            return response()->json([
                'fail' => false,
                'pesan' => 'Kategori Berhasil Diperbaharui!'
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
                $data = Feature::find($request->kategori_id);
                if(!empty($request->thumbnail))
                {
                    $cek = Storage::disk('umum')->exists($data->thumbnail);
                    if($cek)
                    {
                        Storage::disk('umum')->delete($data->thumbnail);
                    }
                    $thumbPath = 'c/thumbnail/';
                    $thumbName = $thumbPath . uniqid() . '.jpg';
                    list($baseType, $thumb) = explode(';', $request->thumbnail);
                    list(, $thumb) = explode(',', $thumb);
                    $thumb = base64_decode($thumb);
                    $p = Storage::disk('umum')->put($thumbName, $thumb);
                    $data->thumbnail = $thumbName;
                }
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
            $data = Feature::where('id',$id)->first();
            // if(Storage::disk('public')->exists($data->image)){
            //     Storage::disk('public')->delete($data->image);
            // }
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
            'pesan' => 'Fitur Produk Berhasil Dihapus!'
        ]);
    }

    public function tree(Request $request)
    {
        $data = Category::latest()->defaultOrder()->get()->toTree();
        $items = $data->map(function ($data) {
            return collect($data->toArray())
                ->only(['id', 'title', 'parent_id', 'children'])
                ->all();

            // return Collect([
            //     'value' => $data->id,
            //     'text' => $data->title,
            //     'parent_id' => $data->parent_id,
            //     'children' => $data->children,
            // ]);
        });
        // $elq = $items->paginate(25);

        return response()->json($items);
    
    }

    private function uploadImage($file){

        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();

        $imgFile = Image::make($file->getRealPath());

        $destinationPath = storage_path('app/public/features');
        $return = '/uploads/features/'.$file_name;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $width = 600;
        $heigth = 600;

        $imgFile->resize($width, $heigth, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($destinationPath.'/'.$file_name, 90);

        return $return;
    }

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->search;

        $query = Feature::when($id, function($query, $id){
            $query->where('product.id', '=', $id);
        })
        ->when($search, function($query, $search){
            $query->where('product.name', '=', $search);
        })->withCount(['product']);
        
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
