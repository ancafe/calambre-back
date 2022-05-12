<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = "date";
    protected $keyType = 'date';
    public $incrementing = false;

    protected $fillable = [
        "date", "name"
    ];
}
