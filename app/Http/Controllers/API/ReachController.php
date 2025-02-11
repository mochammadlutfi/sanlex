<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BranchOffice;
use App\Models\Retail;
class ReachController extends Controller
{

    public function branch(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->s;

        $query = BranchOffice::when($search, function($query) use ($search){
            $query->where('name', 'like', '%' . $search . '%');
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
    
    public function retail(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->s;

        $query = Retail::when($search, function($query) use ($search){
            $query->where('name', 'like', '%' . $search . '%');
        });;
        
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