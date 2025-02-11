<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Certificate;
class BaseController extends Controller
{
    

    public function certification(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->search;

        $query = Certificate::when($search, function($query, $search){
            $query->where('name', '=', $search);
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
