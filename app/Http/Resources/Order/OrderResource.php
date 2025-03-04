<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $state = '';
        if(in_array($this->state, ['draft', 'wait'])){
            $state = 'pending';
        }else if(in_array($this->state, ['approve', 'done'])){
            $state = 'done';
        }else{
            $state = 'cancel';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $state,
            'amount_total' =>  $this->amount_total,
            'invoice_status' => $this->invoice_status
            // 'shipping_s
        ];
    }
}
