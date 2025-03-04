<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Cart;
use App\Models\SequenceLine;
use App\Models\Payment\Payment;
use App\Models\Payment\PaymentLine;
use App\Http\Resources\CartResource;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Payment\PaymentDetailResource;
class PaymentController extends Controller
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

        $query = Payment::where('partner_id', $partner->id)
        ->orderBy('id', 'DESC');

        if($request->limit){
            $data = PaymentResource::collection($query->paginate($request->limit));
        }else{
            $data = PaymentResource::collection($query->get());
        }

        $data = $query->get();

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
        $query = Payment::with(['line'])->where('id', $id)
        ->orderBy('id', 'DESC')
        ->first();

        if(!$query){
            return response()->json([
                'success' => false,
                'message' => 'Payment Not Found'
            ]);
        }

        $data = new PaymentDetailResource($query);

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
