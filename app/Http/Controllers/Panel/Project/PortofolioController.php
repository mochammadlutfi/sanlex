<?php

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


use App\Models\Project\Portofolio;
use App\Models\Media;
class PortofolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return Inertia::render('Project/Portofolio/Index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Project/Portofolio/Form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();

            try{

                $data = new Portofolio();
                $data->save();

                if(!empty($request->media))
                {
                    $media = Media::where('id', $request->media['id'])->first();
                    $data->attachMedia($media, 'thumbnail');
                }

                $data->translateOrNew('id')->title = $request->title;
                $data->translateOrNew('id')->description = $request->description;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }
            DB::commit();
            return redirect()->route('admin.project.portofolio.index');
        }
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
    public function edit($id, Request $request)
    {
        // $locale = $request->locale;
        $data = Portofolio::where('id', $id)->first();
        // dd($data->toArray());
        $data->translation = $data->getTranslationsArray();
        // dd($data);
        // if($locale == 'en'){}
        return Inertia::render('Project/Portofolio/Form',[
            'editMode' => true,
            'value' => $data,
        ]);
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
        // dd($request->all());
        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();

            try{
                $locale = ($request->locale) ?? 'id';
                $data = Portofolio::where('id', $id)->first();
                $data->save();

                if(!empty($request->media))
                {
                    $media = Media::where('id', $request->media['id'])->first();
                    $data->attachMedia($media, 'thumbnail');
                }

                $data->translateOrNew($locale)->title = $request->title;
                $data->translateOrNew($locale)->description = $request->description;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }
            DB::commit();
            return redirect()->route('admin.project.portofolio.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $data = Portofolio::withMedia()->where('id',$id)->first();
            $data->detachMedia($data->media);
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
            'pesan' => 'Data Berhasil Dihapus!'
        ]);
    }

    
    public function data(Request $request)
    {
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;

        $id = $request->id;
        $search = $request->s;
        $company_id = auth()->user()->company_id;

        $query = Portofolio::when($search, function($query, $search){
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
        
        return response()->json($data);
    }
}
