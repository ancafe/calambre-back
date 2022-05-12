<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;

    protected $table = "hours";
    public $timestamps = false;

    protected $fillable = [
        'id', 'name'
    ];

    public function periods(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Period::class)
            ->using(HourPeriod::class)
            ->withPivot('weekday');
    }


}
