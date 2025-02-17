<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

use App\Models\Daerah\Provinsi;
use App\Models\Daerah\Kota;
use App\Models\Daerah\Kecamatan;
use App\Models\Daerah\Kelurahan;
class BaseController extends Controller
{


    public function daerah(Request $request)
    {
        $id = $request->id;
        if($request->type == 'provinsi'){
            $data = Provinsi::select('id', 'name')->orderBy('name', 'ASC')->get();
        }elseif($request->type == 'kota'){
            $data = Kota::select('id', 'name')->orderBy('name', 'ASC')
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })
            ->when($request->parent, function($query, $parent){
                $query->where('prov_id', '=', $parent);
            })->get();
            
        }elseif($request->type == 'kecamatan'){
            $data = Kecamatan::select('id', 'name')->orderBy('name', 'ASC')
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })
            ->when($request->parent, function($query, $parent){
                $query->where('kota_id', '=', $parent);
            })->get();

        }else{
            $data = Kelurahan::select('id', 'name')->orderBy('name', 'ASC')
            ->when($request->id, function($query, $id){
                $query->where('id', $id);
            })
            ->when($request->parent, function($query, $parent){
                $query->where('kec_id', '=', $parent);
            })->get();
        }

        return response()->json($data);
    }

}
