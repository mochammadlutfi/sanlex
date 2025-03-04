<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceLineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'product_id' => $this->product_id,
            'price_unit' => $this->price_unit,
            'qty' => $this->quantity,
            'ppn' => $this->ppn,
            'price_promo' => $this->price_promo,
            'discount_1' => $this->discount_1,
            'discount_2' => $this->discount_2,
            'discount_3' => $this->discount_3,
            'discount' => $this->discount_4,
            'amount_discount_1' => $this->amount_discount_1,
            'amount_discount_2' => $this->amount_discount_2,
            'amount_discount_3' => $this->amount_discount_3,
            'amount_discount' => $this->amount_discount,
            'price_subtotal' => $this->price_subtotal
        ];
    }
}
