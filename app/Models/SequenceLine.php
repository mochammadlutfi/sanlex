<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SequenceLine extends Model
{
    use HasFactory;

    protected $connection = 'orange';
    protected $table = 'ir_sequence_date_range';

    protected $appends = [
        'number_next_actual', 'formatted_sequence'
    ];


    /**
     * Get the actual next number based on the sequence implementation.
     */
    public function getNumberNextActualAttribute()
    {
        return $this->predictNextVal();
    }

    /**
     * Predict next value from PostgreSQL sequence
     */
    public function predictNextVal()
    {
        if (!is_null($this->manualNumberNextActual)) {
            return $this->manualNumberNextActual;
        }

        $seqId = sprintf("%03d_%03d", $this->sequence->id, $this->id);
        $result = DB::connection('orange')
        ->select("SELECT nextval('ir_sequence_{$seqId}') AS next_value");
        return $result[0]->next_value ?? 1;
    }

    public function getFormattedSequenceAttribute()
    {
        // Ambil prefix dari sequence
        $prefix = $this->sequence->prefix ?? 'SO/%(range_year)s/';

        // Ganti placeholder dengan tahun sekarang
        $rangeYear = date('Y');
        $prefix = str_replace('%(range_year)s', $rangeYear, $prefix);

        // Ambil nomor urut berikutnya
        $numberNext = str_pad($this->number_next_actual, 5, '0', STR_PAD_LEFT);

        return "{$prefix}{$numberNext}";
    }

    public function sequence()
    {
        return $this->belongsTo(Sequence::class, 'sequence_id');
    }
}
