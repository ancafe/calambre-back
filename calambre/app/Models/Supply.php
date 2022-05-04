<?php

namespace App\Models;

use App\Traits\UUID;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use UUID;
    use EncryptableDbAttribute;

    protected $fillable = [
        'user',
        'edis_id',
        'cups',
        'provisioning_address',
        'main'
    ];

    protected $encryptable = [
        'edis_id',
        'cups',
        'provisioning_address'
    ];

    protected $casts = [
        'main' => 'boolean'
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
