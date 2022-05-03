<?php

namespace App\Casts;

use App\Services\FixEncrypter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EncrypterCast implements CastsAttributes
{
    private FixEncrypter $fixEncrypter;

    public function __construct()
    {
        $this->fixEncrypter = new FixEncrypter();
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $this->fixEncrypter->decryptString($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $this->fixEncrypter->encryptString($value);
    }
}
