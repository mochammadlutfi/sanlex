<?php

namespace App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{

    protected $fillable = ['description', 'application', 'technical', 'spesification'];
    public $timestamps = false;

    public function getSpesificationAttribute($value)
    {
        if($value){
            return json_decode($value, true);
        }
    }
}
