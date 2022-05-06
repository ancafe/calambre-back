<?php

namespace App\Models;

use App\Casts\EncrypterCast;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

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


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contract::class, 'supply', 'id');
    }

    public function measure():  \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Measure::class, 'supply', 'id');
    }


}
