<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Image;

use App\Models\Certificate;
use Storage;

class CertificationController extends Controller
{

    public function index(Request $request)
    {
        return Inertia::render('Certification');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            $data = new Certificate();
            $data->name = $request->name;
            if($request->hasFile('image')) {
                $data->image = BaseHelper::uploadFile($request->file('image'), '/uploads/certificate', true);
            }
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

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data = Certificate::find($id);
            $data->name = $request->name;
            if($request->hasFile('image')) {
                $data->image = BaseHelper::uploadFile($request->file('image'), '/uploads/certificate', true);
            }
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $data = Certificate::where('id',$id)->first();
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

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $query = Certificate::when($request->search, function($query, $search){
            $query->where('name', 'LIKE', '%'.$search.'%');
        });

        if($request->limit){
            $data = ($request->limit == 1 ) ? $query->first() : $query->paginate($request->limit);
        }else{
            $data = $query->get();
        }
        
        return response()->json($data);
    }

}
