<?php

namespace App\Models;

use App\Casts\EncrypterCast;
use App\Services\FixEncrypter;
use App\Traits\EmailSignatureTrait;
use App\Traits\UUID;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
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
    use EncryptableDbAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'edisUser',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'edisPassword',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email' => EncrypterCast::class,
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be encrypted
     *
     * @var array|string[]
     */
    protected array $encryptable = [
        'name',
        'edisUsername',
        'edisPassword',
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
        return $this->hasMany(Supply::class);
    }

}
