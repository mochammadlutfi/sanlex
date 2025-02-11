<?php

namespace App\Http\Controllers\Panel\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductVariant;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return Inertia::render('Product/Price',[
            'data' => Product::find($id)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data($productId, Request $request)
    {

        // Ambil data variant beserta harga
        $data = ProductVariant::where('product_id', $productId)
        ->with(['packaging', 'color', 'price' => function ($query) use ($request) {
            $query->where('branch_id', $request->branch_id);
        }])
        ->get()->map(function ($variant) use($request) {
            return [
                'id' => count($variant->price) ? $variant->price[0]->id : null,
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
                'branch_id' => $request->branch_id,
                'name' => $variant->name,
                'packaging_id' => $variant->packaging_id,
                'packaging' => $variant->packaging,
                'color' => $variant->color_id,
                'color' => $variant->color,
                'code' => $variant->code,
                'price' => count($variant->price) ? $variant->price[0]->price : null,
            ];
        });
        return response()->json($data,200);
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

            foreach($request->priceList as $d){
                $data = ProductPrice::firstOrNew([
                    'branch_id' =>  $request->branch_id,
                    'product_id' =>  $d['product_id'],
                    'variant_id' =>  $d['variant_id'],
                ]);
                $data->price = $d['price'];
                $data->save();
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
