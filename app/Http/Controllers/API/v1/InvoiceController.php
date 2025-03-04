<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Cart;
use App\Models\SequenceLine;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceLine;
use App\Http\Resources\CartResource;
use App\Http\Resources\Invoice\InvoiceResource;
use App\Http\Resources\Invoice\InvoiceDetailResource;
class InvoiceController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = auth()->user();

        $partner = DB::connection('orange')->table('res_partner')
        ->where('company_id', $auth->branch->code)
        ->where('ref', $auth->ref)
        ->first();

        $query = Invoice::where('partner_id', $partner->id)
        ->orderBy('id', 'DESC');

        if($request->limit){
            $data = InvoiceResource::collection($query->paginate($request->limit));
        }else{
            $data = InvoiceResource::collection($query->get());
        }

        return response()->json($data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::connection('orange')->beginTransaction();
        try {
            $auth = auth()->user();
            

            DB::connection('orange')->commit();
            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
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
        $query = Invoice::with(['line'])->where('id', $id)
        ->orderBy('id', 'DESC')
        ->first();

        if(!$query){
            return response()->json([
                'success' => false,
                'message' => 'Invoice Not Found'
            ]);
        }

        $data = new InvoiceDetailResource($query);

        return response()->json([
            'success' => true,
            'result' => $data
        ]);
    }

    /**
     * Cancel the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        //
    }
}
