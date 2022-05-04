<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use UUID;

    protected $fillable = [
        'user',
        'supply',
        'date',
        'hour',
        'hourCCH',
        'startAt',
        'endAt',
        'invoiced',
        'real',
        'value',
        'obtainingMethod'
    ];

    protected $casts = [
        'real' => 'boolean',
        'invoiced' => 'boolean',
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supply(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }


}
