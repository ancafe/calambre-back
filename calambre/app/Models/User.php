<?php

namespace App\Models;

use App\Casts\EncrypterCast;
use App\Traits\UUID;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    use HasFactory;
    use Notifiable;
    use UUID;

    protected $fillable = [
        'name',
        'email',
        'password',
        'edisUser',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'edisPassword',
    ];

    protected $casts = [
        'email' => EncrypterCast::class,
        'email_verified_at' => 'datetime',
        'name' => 'encrypted',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email
        ];
    }

    public function getEncryptKeyAttribute()
    {
        return auth()->payload()->get('encrypt_key') ?: null;
    }

    public function supplies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Supply::class, 'user', 'id');
    }

    public function edis(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EdisInfo::class, 'user', 'id');
    }

}
