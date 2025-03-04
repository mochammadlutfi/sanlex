<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Cart;
use App\Models\SequenceLine;
use App\Models\Order\Order;
use App\Models\Order\OrderLine;
use App\Http\Resources\CartResource;
use App\Http\Resources\Order\OrderResource;
class OrderController extends Controller
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

        $query = Order::where('partner_id', $partner->id)
        ->when($request->state == 'pending', function($q){
            return $q->whereIn('state', ['draft', 'wait']);
        })
        ->when($request->state == 'process', function($q){
            return $q->whereIn('state', ['approve', 'done']);
        })
        ->orderBy('id', 'DESC');

        if($request->limit){
            $data = OrderResource::collection($query->paginate($request->limit));
        }else{
            $data = OrderResource::collection($query->get());
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

            // Validasi request ids harus array
            $ids = json_decode($request->ids, true);
            if (!is_array($ids) || empty($ids)) {
                return response()->json(['success' => false, 'message' => 'Invalid product selection'], 400);
            }

            // Ambil Sequence
            $sequence = SequenceLine::where('date_from', '<=', Carbon::now())
                ->where('date_to', '>=', Carbon::now())
                ->whereHas('sequence', function ($query) use ($auth) {
                    $query->where('company_id', $auth->branch->code)
                        ->where('code', 'sale.order');
                })
                ->first();

            if (!$sequence) {
                return response()->json(['success' => false, 'message' => 'Sequence not found'], 404);
            }

            // Ambil Data Partner
            $partner = DB::connection('orange')->table('res_partner as rp')
                ->select('rp.id', 'rp.street', 'rp.ref', 'rp.rute_kirim_id', 'rp.area_id', 'rp.coverage_id', 'tc.name as coverage',
                    'ta.name as area', 'rp.top_id', 'rk.senin', 'rk.selasa', 'rk.rabu', 'rk.kamis', 'rk.jumat', 'rk.sabtu')
                ->leftJoin('teritory_coverage as tc', 'tc.id', '=', 'rp.coverage_id')
                ->leftJoin('teritory_area as ta', 'ta.id', '=', 'rp.area_id')
                ->leftJoin('res_rute_kirim as rk', 'rk.id', '=', 'rp.rute_kirim_id')
                ->where('rp.ref', $auth->ref)
                ->where('rp.company_id', $auth->branch->code)
                ->first();

            if (!$partner) {
                return response()->json(['success' => false, 'message' => 'Partner not found'], 404);
            }

            // Menentukan jadwal kirim
            $jadwal_kirim = '';
            if ($partner->rute_kirim_id) {
                if ($partner->senin) $jadwal_kirim .= 'Senin';
                if ($partner->selasa) $jadwal_kirim .= ' Selasa';
                if ($partner->rabu) $jadwal_kirim .= ' Rabu';
                if ($partner->kamis) $jadwal_kirim .= ' Kamis';
                if ($partner->jumat) $jadwal_kirim .= ' Jumat';
                if ($partner->sabtu) $jadwal_kirim .= ' Sabtu';
            }
            // dd($jadwal_kirim);
            // Ambil Warehouse
            $warehouse = DB::connection('orange')->table('stock_warehouse')
                ->where('company_id', $auth->branch->code)
                ->first();

            if (!$warehouse) {
                return response()->json(['success' => false, 'message' => 'Warehouse not found'], 404);
            }

            // Ambil Data Cart
            $cart = Cart::with(['product', 'variant'])
                ->where('customer_id', $auth->id)
                ->whereIn('id', $ids)
                ->get();

            if ($cart->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
            }

            // Proses perhitungan order line
            $lines = [];
            $amount_untaxed = 0;
            $discount = 7.50; // 7.5%
            
            foreach ($cart as $c) {
                // Ambil harga varian
                $priceData = $c->variant->price->where('branch_id', $auth->branch_id)->first();
                $price = $priceData ? $priceData->price : 0;

                // Ambil produk dari Odoo
                $oProduct = DB::connection('orange')->table('product_product as pp')
                    ->select('pp.id', 'pt.name', 'pt.product_code', 'pp.product_tmpl_id')
                    ->leftJoin('product_template as pt', 'pt.id', '=', 'pp.product_tmpl_id')
                    ->where('pt.product_code', $c->variant->code)
                    ->first();

                if (!$oProduct) {
                    return response()->json(['success' => false, 'message' => 'Product not found in Odoo'], 404);
                }

                $discount_price = $price * $discount / 100;
                $price_reduce = $price - $discount_price;
                $price_total = $price_reduce * $c->qty;

                $amount_untaxed += $price_total;

                $lines[] = [
                    'product_id' => $oProduct->id,
                    'product_uom_qty_before_approval' => $c->qty,
                    'product_uom' => 1,
                    'product_uom_qty' => number_format($c->qty, 3, '.', ''),
                    'price_unit' => $price,
                    'price_reduce' => $price_reduce,
                    'price_reduce_taxinc' => $price_reduce,
                    'price_reduce_taxexcl' => $price_reduce,
                    'price_total' => $price_total,
                    'price_subtotal' => $price_total,
                    'currency_id' => 13,
                    'price_tax' => '0.0',
                    'qty_to_invoice' => '0.000',
                    'customer_lead' => '0',
                    'company_id' => $auth->branch->code,
                    'state' => 'wait',
                    'order_partner_id' => $partner->id,
                    'qty_invoiced' => '0.000',
                    'qty_delivered' => '0.000',
                    'invoice_status' => 'no',
                    'name' => $oProduct->name,
                    'discount' => '7.50',
                    'price_promo' => '0.0',
                ];
            }

            // Buat Order
            $amount_total = $amount_untaxed;
            $order = Order::create([
                'name' => $sequence->formatted_sequence,
                'partner_id' => $partner->id,
                'street' => $partner->street,
                'coverage_id' => $partner->coverage,
                'ref' => $partner->ref,
                'area_id' => $partner->area,
                'date_order' => now()->format('Y-m-d H:i:s'),
                'team_id' => 1,
                'company_id' => $auth->branch->code,
                'state' => 'wait',
                'partner_invoice_id' => $partner->id,
                'partner_shipping_id' => $partner->id,
                'pricelist_id' => 1,
                'validity_date' => now()->format('Y-m-d'),
                'payment_term_id' => $partner->top_id,
                'invoice_status' => 'no',
                'picking_policy' => 'direct',
                'warehouse_id' => $warehouse->id,
                'ppn' => 'f',
                'schedule_delivery' => $jadwal_kirim,
                'amount_untaxed' => $amount_untaxed,
                'amount_total' => $amount_total,
            ]);

            // Simpan order line
            $order->line()->createMany($lines);

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
        $query = Order::with(['line'])->where('id', $id)
        ->orderBy('id', 'DESC')
        ->first();

        if(!$query){
            return response()->json([
                'success' => false,
                'message' => 'Sale Order Not Found'
            ]);
        }

        $data = $query;

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
