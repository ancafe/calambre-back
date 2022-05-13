<?php

namespace App\Models;

use App\Casts\EncrypterCast;
use App\Traits\UUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Supply extends Model
{
    use UUID;

    protected $fillable = [
        'user',
        'edis_id',
        'cups',
        'provisioning_address',
        'main'
    ];

    protected $casts = [
        'edis_id' => EncrypterCast::class,
        'main' => 'boolean',
        'cups' => 'encrypted',
        'provisioning_address' => 'encrypted',
    ];

    protected $appends = ['last_data'];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contract::class, 'supply', 'id');
    }

    public function measure(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Measure::class, 'supply', 'id');
    }

    public function getLastDataAttribute(): Carbon|null
    {

        $latest = Measure::where("value", ">", 0)
            ->where("supply", "=", $this->id)
            ->orderBy("date", "DESC")
            ->orderBy("hournum", "DESC")
            ->first();
        
        return ($latest) ? new Carbon($latest->date . " " . $latest->hournum . ":00:00") : null;
    }


}
