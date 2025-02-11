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
        $this->middleware('auth');
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
        $rules = [
            'name' => 'required',
            'address' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Wajib Diisi!',
            'address.required' => 'Alamat Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = new Contact();
                    $data->name = $request->name;
                    $data->province_id = $request->provinsi_id;
                    $data->city_id = $request->city_id;
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
                return back();
            }
            DB::commit();
            return redirect()->route('admin.project.contact.index');
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
        // dd($request->all());
        $rules = [
            'name' => 'required',
        ];

        $pesan = [
            'name.required' => 'Nama Tugas Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                    $data = Brand::where('id', $id)->first();
                    $data->name = $request->name;
                    if($request->hasFile('image')){
                        if(!empty($data->image)){
                            if(Storage::disk('public')->exists($data->image))
                            {
                                Storage::disk('public')->delete($data->image);
                            }
                        }
                        $data->image = $this->uploadImage($request->file('image'));
                    }
                    $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return back();
            }
            DB::commit();
            return redirect()->route('admin.vehicle.brand.index');
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
            
            $data = Brand::where('id', $id)->first();
            if(isset($data->image)){
                $cek = Storage::disk('public')->exists($data->image);
                if($cek)
                {
                    Storage::disk('public')->delete($data->image);
                }
            }
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            dd($e);
        }

        DB::commit();
        return redirect()->route('admin.vehicle.brand.index');
    }


    private function uploadImage($file){

        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();

        $imgFile = Image::make($file->getRealPath());

        $destinationPath = storage_path('app/public/brands');
        $return = '/uploads/brands/'.$file_name;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $width = 800;
        $heigth = 800;

        $imgFile->resize($width, $heigth, function ($constraint) {
		    $constraint->aspectRatio();
		})->save($destinationPath.'/'.$file_name, 90);

        return $return;
    }
    
    public function data(Request $request)
    {
        $page = $request->page;
        $sort = !empty($request->sort) ? $request->sort : 'id';
        $sortDir = !empty($request->sortDir) ? $request->sortDir : 'desc';
        $limit = ($request->limit) ? $request->limit : 25;
        $type = $request->type;

        $data = Contact::orderBy($sort, $sortDir);

        if($page){
            $output = $data->paginate($limit);
        }else{
            $output = $data->get();
        }

        return response()->json($output);
    }
}
