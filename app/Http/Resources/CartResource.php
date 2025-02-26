<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $branchId = auth()->user()->branch_id ?? null;
        // // dd($this);>
        $priceData = $this->variant->price->where('branch_id', $branchId)->first();

        $price = $priceData ? $priceData->price : 0;
        
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product_id,
                'name' => $this->product->name,
            ],
            'variant' => [
                'id' => $this->variant_id,
                'packaging' => $this->variant->packaging->name,
                'color' => $this->variant->color,
            ],
            'qty' => $this->qty,
            'price' => $price
        ];
    }
}
