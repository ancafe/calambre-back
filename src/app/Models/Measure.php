<?php

namespace App\Models;

use App\Traits\UUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use UUID;

    const P1COLOR = '#f2970f';
    const P2COLOR = '#96b633';
    const P3COLOR = '#c4dd8c';



    protected $fillable = [
        'user',
        'supply',
        'date',
        'hour',
        'hournum',
        'startAt',
        'endAt',
        'invoiced',
        'real',
        'value',
        'obtainingMethod'
    ];

    protected $casts = [
        'real' => 'boolean',
        'invoiced' => 'boolean',
    ];

    protected $appends = ['period'];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supply(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }

    public function getPeriodAttribute()
    {
        $weekday = (new Carbon($this->date))->dayOfWeek;
        $isHoliday = count(PublicHoliday::where('date', $this->date)->get());

        $situation = HourPeriod::where("weekday", $weekday)
            ->where('hour_id', $this->hournum)
            ->where('holiday', $isHoliday )
            ->first();

        return Period::find($situation->period_code)->code;
    }




}
