<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;
use App\Models\Project\Portofolio;
use App\Models\Project\Contact;
class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->s;
        $locale = \LaravelLocalization::getCurrentLocale();

        $query = Product::with(['brand', 'category'])
        ->when($id, function($query, $id){
            $query->where('product.id', '=', $id);
        })
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
        
        return response()->json($data, 200);
    }


    public function show(Request $request, $slug)
    {
        App::setLocale($request->locale);
        $data = Product::
        where('slug', $slug)
        ->first();

        
        return response()->json($data, 200);
    }

    public function contact(Request $request)
    {
        App::setLocale($request->locale);
        $data = Contact::
        orderBy('name', 'ASC')
        ->get();

        
        return response()->json($data, 200);
    }

}
