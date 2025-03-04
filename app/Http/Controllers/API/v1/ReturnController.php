<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Cart;
use App\Models\SequenceLine;
use App\Models\Return\ReturnOrder;
use App\Models\Return\ReturnOrderLine;
use App\Http\Resources\CartResource;
use App\Http\Resources\Return\ReturnOrderResource;
use App\Http\Resources\Return\ReturnOrderDetailResource;

class ReturnController extends Controller
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

        $query = ReturnOrder::select('return_order.*', 'rp.name as sales_name')
        ->leftjoin('res_users as ru', 'ru.id', '=', 'return_order.user_id')
        ->leftjoin('res_partner as rp', 'rp.id', '=', 'ru.partner_id')
        ->where('return_order.partner_id', $partner->id)
        ->orderBy('return_order.id', 'DESC');

        if($request->limit){
            $data = ReturnOrderResource::collection($query->paginate($request->limit));
        }else{
            $data = ReturnOrderResource::collection($query->get());
        }

        // $data = $query->get();

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
        $query = ReturnOrder::with(['line'])
        ->select('return_order.*', 'rp.name as sales_name')
        ->leftjoin('res_users as ru', 'ru.id', '=', 'return_order.user_id')
        ->leftjoin('res_partner as rp', 'rp.id', '=', 'ru.partner_id')
        ->where('return_order.id', $id)
        ->first();

        if(!$query){
            return response()->json([
                'success' => false,
                'message' => 'ReturnOrder Not Found'
            ]);
        }

        $data = new ReturnOrderDetailResource($query);
        // $data = $query;
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
