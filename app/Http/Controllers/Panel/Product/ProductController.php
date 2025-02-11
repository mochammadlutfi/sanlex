<?php

namespace App\Http\Controllers\Panel\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Helpers\BaseHelper;




use Storage;
use App\Helpers\GeneralHelp;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Image;


use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\Category;
class ProductController extends Controller
{

    /**
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Product/Index');
    }

    public function create()
    {
        return Inertia::render('Product/Form');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // dd(array_column($request->packaging, 'id'));
        DB::beginTransaction();

        try{
            $data = new Product();
            $data->name = $request->name;
            $data->category_id = $request->category_id;
            $data->brand_id = $request->brand_id;
            $data->youtube = $request->youtube_url;
            if($request->hasFile('image')) {
                $data->image = BaseHelper::uploadFile($request->file('image'), '/uploads/products', true);
            }
            $data->packaging = $request->packaging ? json_encode(array_column($request->packaging, 'id')) : null;
            $data->color = $request->color ? json_encode(array_column($request->color, 'id')) : null;
            $data->translateOrNew('id')->description = $request->description;
            $data->translateOrNew('id')->spesification = json_encode($request->spesification);
            $data->save();
            
            foreach($request->variant as $v){
                $variant = new ProductVariant();
                $variant->product_id = $data->id;
                $variant->packaging_id = $v["packaging_id"];
                $variant->color_id = $v["color_id"] ?? null;
                $variant->name = $v["name"];
                $variant->code = $v["code"];
                $variant->save();
            }

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

    public function edit($id, Request $request)
    {
        $data = Product::with(['variant' => function($q){
            return $q->with(['packaging', 'color']);
        }])->where('id', $id)->first();

        return Inertia::render('Product/Form',[
            'data' => $data,
        ]);
    }

    public function update($id, Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try{
            $data = Product::where('id', $id)->first();
            $data->name = $request->name;
            $data->category_id = $request->category_id;
            $data->brand_id = $request->brand_id;
            $data->youtube = $request->youtube_url;
            if($request->hasFile('image')) {
                $data->image = BaseHelper::uploadFile($request->file('image'), '/uploads/products', true);
            }
            if($request->packaging){
                $data->packaging = implode(',', array_column(json_decode($request->packaging, true), 'id'));
            }
            if($request->color){
                $data->color = implode(',', array_column(json_decode($request->color, true), 'id'));
            }

            $data->translateOrNew($request->locale)->description = $request->description;
            $data->translateOrNew($request->locale)->spesification = $request->spesification;
            $data->save();
            
            if($request->removed_variant){
                ProductVariant::whereIn('id', json_decode($request->removed_variant,true))->delete();
            }

            if($request->variant){
                foreach(json_decode($request->variant,true) as $v){
                    if($v["id"]){
                        $variant = ProductVariant::where('id', $v["id"])->first();
                    }else{
                        $variant = new ProductVariant();
                    }
                    $variant->product_id = $data->id;
                    $variant->packaging_id = $v["packaging_id"];
                    $variant->color_id = $v["color_id"] ?? null;
                    $variant->name = $v["name"];
                    $variant->code = $v["code"];
                    $variant->save();
                }
            }

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

    public function delete($id)
    {
        $data = Product::find($id);
        $cek = Storage::disk('public')->exists($data->image);
        if($cek)
        {
            Storage::disk('public')->delete($data->image);
        }
        $hapus_db = Product::destroy($data->id);
        if($hapus_db)
        {
            return response()->json([
                'fail' => false,
            ]);
        }
    }

    
    private function uploadImage($file){

        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();

        $imgFile = Image::make($file->getRealPath());

        $destinationPath = storage_path('app/public/products');
        $return = '/uploads/products/'.$file_name;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $width = 800;
        $heigth = 800;

        $imgFile->resize($width, $heigth, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($destinationPath.'/'.$file_name, 90);

        return $return;
    }

    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';

        $query = Product::with(['brand', 'category'])
        ->when($request->id, function($query, $id){
            $query->where('product.id', '=', $id);
        })
        ->when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%' . $search . '%');
        });
        
        if($request->limit){
            $data = $query->paginate($request->limit);
        }else{
            $data = $query->get();
        }
        
        return response()->json($data);
    }
    

    public function variant($id, Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';

        $data = ProductVariant::with(['packaging', 'color'])
        ->where('product_id', $id)->get();

        // // Ambil semua varian produk berdasarkan product_id
        // $variants = ProductVariant::where('product_id', $productId)->get();

        // // Ambil harga produk yang sudah tersimpan di tabel product_prices
        // $prices = ProductPrice::where('product_id', $productId)->get()->groupBy('product_variant_id');

        // // Gabungkan data varian dana harga
        // $data = $variants->map(function ($variant) use ($prices) {
        //     return [
        //         'product_id' => $id,
        //         'product_variant_id' => $variant->id,
        //         'packaging_id' => $variant->packaging_id,
        //         'packaging' => $variant->packaging->name ?? null,
        //         'color_id' => $variant->color_id,
        //         'color' => $variant->color->name ?? null,
        //         'code' => $variant->code,
        //         'prices' => $prices->get($variant->id, collect())->map(function ($price) {
        //             return [
        //                 'branch_id' => $price->branch_id,
        //                 'price' => $price->price
        //             ];
        //         })->values(), // Ambil nilai tanpa kunci array
        //     ];
        // });
        return response()->json($data);
    }

}
