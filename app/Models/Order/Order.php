<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'orange';
    protected $table = 'sale_order';
    protected $primaryKey = 'id';

    protected $fillable = [
        "origin", 
        "create_date", 
        "write_uid", 
        "team_id", 
        "client_order_ref", 
        "date_order", 
        "partner_id", 
        "note", 
        "procurement_group_id", 
        "amount_untaxed", 
        "message_last_post", 
        "company_id", 
        "amount_tax", 
        "state", 
        "pricelist_id", 
        "project_id", 
        "create_uid", 
        "confirmation_date", 
        "validity_date", 
        "payment_term_id", 
        "write_date", 
        "partner_invoice_id", 
        "fiscal_position_id", 
        "amount_total", 
        "invoice_status", 
        "name", 
        "partner_shipping_id", 
        "user_id", 
        "picking_policy", 
        "incoterm", 
        "warehouse_id", 
        "discount_2", 
        "discount_3", 
        "discount_1", 
        "street", 
        "discount_1x", 
        "approval_id", 
        "ref", 
        "amount_discount_4", 
        "amount_discount_2", 
        "amount_discount_3", 
        "amount_discount_1", 
        "date_approval", 
        "state_sfa", 
        "po_num", 
        "coverage_id", 
        "ppn", 
        "amt_pso", 
        "pso_id", 
        "reasson_pso", 
        "area_id", 
        "arrival_date", 
        "schedule_delivery", 
        "amount_discount_5"
    ];

    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'write_date';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->user_id = 1959;
            $order->create_uid = 1959;
            $order->write_uid = 1959;
        });
    }
    
    public function line(){
        return $this->hasMany(OrderLine::Class, 'order_id');
    }
}
