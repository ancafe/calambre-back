<?php

namespace App\Services\Measure;

use App\Models\Measure;
use App\Models\Supply;
use App\Models\User;
use App\Services\FixEncrypter;
use DateInterval;
use DateTime;

class StorageMeasureService
{
    private FixEncrypter $fixEncrypter;

    public function __construct(FixEncrypter $fixEncrypter)
    {
        $this->fixEncrypter = $fixEncrypter;
    }

    public function storage(array $measures, User $user, Supply $supply)
    {
        if (!array_key_exists("data",$measures)){
            throw new \Exception();
        }

        if (!array_key_exists("lstData",$measures['data'])){
            throw new \Exception();
        }

        foreach ($measures['data']['lstData'] as $day){
            foreach ($day as $point) {
                $this->save($point, $user, $supply);
            }

        }

    }

    /**
     * @throws \Exception
     */
    public function save(array $point, User $user, Supply $supply): void
    {
        $date = DateTime::createFromFormat('d/m/Y H:i:s', $point['date']. " 00:00:00");

        $startAt = clone $date;
        $endAt = clone $date;
        $hour = $point['hourCCH'];
        $startAt->add(new DateInterval("PT".($hour-1)."H"));
        $endAt->add(new DateInterval("PT".($hour)."H"));

        $value = (double) (array_key_exists('valueDouble',$point)) ? $point['valueDouble'] : 0;

        Measure::updateOrCreate([
            'date' => $date->format('Y-m-d'),
            'supply' => $supply->id,
            'hour' => $point['hour'],
            'hournum' => $point['hourCCH'],
        ],[
            'date' => $date->format('Y-m-d'),
            'startAt' => $startAt->format('Y-m-d H:i:s'),
            'endAt' => $endAt->format('Y-m-d H:i:s'),
            'real' => $point['real'],
            'invoiced' => $point['invoiced'],
            'value' => $value,
            'hour' => $point['hour'],
            'hournum' => $point['hourCCH'],
            'supply' => $supply->id,
            'user' => $user->id,
        ]);
    }

}
