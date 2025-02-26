<?php

namespace App\Http\Controllers\Panel\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Storage;
use Image;

use App\Models\Project\Contact;

class ContactController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('ProjectContact/Index');
    }


    
    public function create()
    {
        return Inertia::render('ProjectContact/Form');
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
                $data = new Contact();
                $data->name = $request->name;
                $data->province_id = $request->province ? $request->province['id'] : null;
                $data->city_id = $request->city ? $request->city['id'] : null;
                $data->postal_code = $request->postal_code;
                $data->address = $request->address;
                $data->phone = $request->phone;
                $data->fax = $request->fax;
                $data->email = $request->email;
                $data->pic_name = $request->pic_name;
                $data->pic_phone = $request->pic_phone;
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
        DB::beginTransaction();
        try{
                $data = Contact::find($id);
                $data->name = $request->name;
                $data->province_id = $request->province ? $request->province['id'] : null;
                $data->city_id = $request->city ? $request->city['id'] : null;
                $data->postal_code = $request->postal_code;
                $data->address = $request->address;
                $data->phone = $request->phone;
                $data->fax = $request->fax;
                $data->email = $request->email;
                $data->pic_name = $request->pic_name;
                $data->pic_phone = $request->pic_phone;
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

            $data = Contact::where('id', $id)->first();
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

        $query = Contact::with(['province', 'city'])->orderBy($sort, $sortDir);

        if($request->limit){
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
