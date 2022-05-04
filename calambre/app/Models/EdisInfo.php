<?php

namespace App\Models;

use App\Casts\EncrypterCast;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class EdisInfo extends Model
{
    use UUID;

    protected $table = 'edis';

    protected $fillable = [
        'user',
        'username',
        'password',
        'visibility',
        'main'
    ];


    protected $casts = [
        'username' => 'encrypted',
        'password' => 'encrypted',
        'name' => 'encrypted',
        'visibility' => EncrypterCast::class,
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
