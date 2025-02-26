<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BaseResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $branchId = auth()->user()->branch_id ?? null;
        // dd($this);>
        $priceData = $this->price->where('branch_id', $branchId)->first();
        // dd($priceData->price);
        $price = $priceData ? $priceData->price : 0;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'packaging' => $this->packaging,
            'color' => $this->color,
            'price' => $price
        ];
    }
}
