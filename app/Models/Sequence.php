<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    use HasFactory;

    protected $connection = 'orange';
    protected $table = 'ir_sequence';


    
    public function line(){
        return $this->hasMany(SequenceLine::Class, 'sequence_id');
    }
}
