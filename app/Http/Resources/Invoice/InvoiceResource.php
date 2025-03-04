<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'date_invoie' => $this->date_invoice,
            'date_due' => $this->date_due,
            'origin' => $this->origin,
            'discount_1' => $this->discount_1,
            'discount_2' => $this->discount_2,
            'discount_3' => $this->discount_3,
            'discount_4' => $this->discount_4,
            'amount_untaxed' =>  $this->amount_untaxed_signed,
            'amount_discount_1' => $this->amount_discount_1,
            'amount_discount_2' => $this->amount_discount_2,
            'amount_discount_3' => $this->amount_discount_3,
            'amount_discount_4' => $this->amount_discount_4,
            'amount_tax' => $this->amount_tax,
            'amount_total' =>  $this->amount_total_signed,
            'residual' =>  $this->residual_signed
        ];
    }
}
