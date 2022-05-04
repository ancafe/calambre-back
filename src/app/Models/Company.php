<?php

namespace App\Models;

use App\Casts\EncrypterCast;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use UUID;

    protected $table = "companies";

    protected $fillable = [
        'user',
        'name',
        'companyId'
    ];

    protected $casts = [
        'companyId' => EncrypterCast::class,
    ];


}
