<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class HourPeriod extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "hour_period";

    protected $casts = [
        'holiday' => 'boolean'
    ];

    public function period(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Period::class, 'code', 'period_code');
    }

    public function hour(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hour::class, 'hour', 'id');
    }


}
