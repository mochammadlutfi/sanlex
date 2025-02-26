<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Product\ProductVariantResource;
class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $branchId = auth()->user()->branch_id ?? null;
        // dd($this);
        // Filter harga berdasarkan branch_id user
        $filteredPrices = $this->prices->where('branch_id', $branchId);

        // Dapatkan harga terendah dan tertinggi
        $minPrice = $filteredPrices->min('price') ?? 0;
        $maxPrice = $filteredPrices->max('price') ?? 0;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image_url,
            'category' => $this->category ? [
                'id' => $this->category_id,
                'name' => $this->category->name,
            ] : null,
            'brand' => $this->brand ? [
                'id' => $this->brand_id,
                'name' => $this->brand->name,
            ] : null,
            'price_min' => $minPrice,
            'price_max' => $maxPrice,
            'color' => $this->color,
            'pacakaging' => $this->pacakaging,
            'spesification' => $this->spesification,
            'description' => $this->description,
            'variant' => ProductVariantResource::collection($this->variant),
        ];
    }
}
