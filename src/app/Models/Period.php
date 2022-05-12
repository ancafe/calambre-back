<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $table = "period";

    public $timestamps = false;

    protected $primaryKey = "code";
    protected $keyType = 'string';
    public $incrementing = false;


    protected $fillable = [
        'code', 'name'
    ];

    public function hours(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Hour::class)
            ->using(HourPeriod::class)
            ->withPivot('weekday');
    }

}
