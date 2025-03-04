<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;


    protected $connection = 'orange';
    protected $table = 'sale_order_line';
    protected $primaryKey = 'id';

    protected $fillable = [
        "product_uom", "price_unit", "product_uom_qty", "price_subtotal", "write_uid", "currency_id", "price_reduce_taxexcl", "create_uid", "price_tax", "qty_to_invoice", "customer_lead", "layout_category_sequence", "company_id", "state", "order_partner_id", "order_id", "qty_invoiced", "sequence", "discount", "price_reduce", "qty_delivered", "layout_category_id", "product_id", "price_reduce_taxinc", "price_total", "invoice_status", "name", "salesman_id", "product_packaging", "route_id", "discount_2", "discount_3", "price_total2", "discount2", "discount_1", "amount_discount_3", "price_promo", "amount_discount_2", "amount_discount_1", "amount_discount", "subtotal_amt_pso", "pso_qty", "qty_pso", "products", "pso_id", "amount_discount_5", "product_uom_qty_before_approval"
    ];

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'write_date';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->salesman_id = 1959;
            $order->create_uid = 1959;
            $order->write_uid = 1959;
        });
    }

    
    public function order(){
        return $this->belongsTo(Order::Class, 'order_id');
    }
}
