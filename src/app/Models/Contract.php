<?php

namespace App\Models;

use App\Casts\EncrypterCast;
use App\Traits\UUID;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use UUID;

    protected $fillable = [
        'atrId',
        'atrNumContract',
        'status',
        'status_code',
        'P1',
        'P2',
        'P3',
        'P4',
        'P5',
        'P6',
        'user',
        'supply',
        'company',
    ];

    protected $casts = [
        'atrId' => EncrypterCast::class,
        'atrNumContract' => 'encrypted',
        'status' => 'encrypted',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function supply(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }


}
