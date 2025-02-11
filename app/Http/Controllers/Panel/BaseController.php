<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

use App\Models\Provinsi;
use App\Models\Kota;
class BaseController extends Controller
{


    public function daerah(Request $request)
    {
        $id = $request->id;
        if($request->type == 'provinsi'){
            $data = Provinsi::orderBy('name', 'ASC')->get();
        }else{
            $data = Kota::orderBy('name', 'ASC')
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })
            ->when($request->parent, function($query, $parent){
                $query->where('province_id', '=', $parent);
            })->get();
            
        }

        return response()->json($data);
    }

}
