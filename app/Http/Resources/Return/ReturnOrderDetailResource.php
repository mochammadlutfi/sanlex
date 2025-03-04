<?php

namespace App\Http\Resources\Return;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Return\ReturnOrderLineResource;

class ReturnOrderDetailResource extends JsonResource
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
            'sales_name' => $this->sales_name,
            'date_order' => $this->date_order,
            'date_done' => $this->date_done,
            'reason' => $this->reason_rto,
            'state' => $this->state,
            'amount_total' =>  $this->amount_total,
            'lines' => ReturnOrderLineResource::collection($this->line)
        ];
    }
}
