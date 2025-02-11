<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use App\Models\Customer;
class CustomerController extends Controller
{
    

    /**
     * Menampilkan Index Kategori
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Customer/Index');
    }

    public function create()
    {
        return Inertia::render('Customer/Form');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            $data = new Customer();
            $data->name = $request->name;
            $data->province_id = $request->province_id;
            $data->city_id = $request->city_id;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->lat = $request->lat;
            $data->lng = $request->lng;
            $data->key = $request->key;
            $data->code = $request->code;
            $data->ref = $request->ref;
            $data->server = $request->server_link;
            $data->save();

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

            $data = Customer::where('id', $id)->first();
            $data->name = $request->name;
            $data->province_id = $request->province_id;
            $data->city_id = $request->city_id;
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->lat = $request->lat;
            $data->lng = $request->lng;
            $data->key = $request->key;
            $data->code = $request->code;
            $data->ref = $request->ref;
            $data->server = $request->server_link;
            $data->save();

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

    public function edit($id){
        $data = Customer::where('id', $id)->first();

        return Inertia::render('Customer/Form',[
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            $data = Customer::where('id', $id)->first();
            $data->delete();

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

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $query = Customer::when($request->search, function($q, $search){
            $query->where('name', 'like', '%' . $search . '%');
        });
        
        if($request->page){
            if($request->limit == 1){
                $data = $query->first();
            }else{
                $data = $query->paginate($request->limit);
            }
        }else{
            $data = $query->get();
        }
        
        return response()->json($data);
    }
}
