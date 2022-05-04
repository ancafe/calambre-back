<?php

namespace App\Services;

use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;

class FixEncrypter extends Encrypter
{
    private string $iv;

    public function __construct()
    {
        $this->iv = base64_decode(config('app.fix_iv_for_email'));
        $key = base64_decode(Str::after(config('app.key'), 'base64:'));

        parent::__construct($key, config('app.cipher'));
    }

    public function encrypt($value, $serialize = true): string
    {
        $iv = $this->iv;

        $value = \openssl_encrypt(
            $serialize ? serialize($value) : $value,
            $this->cipher, $this->key, 0, $iv
        );

        if ($value === false) {
            throw new EncryptException('Could not encrypt the data.');
        }

        $mac = $this->hash($iv = base64_encode($iv), $value);

        $json = json_encode(compact('iv', 'value', 'mac'), JSON_UNESCAPED_SLASHES);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new EncryptException('Could not encrypt the data.');
        }

        return base64_encode($json);
    }
}
