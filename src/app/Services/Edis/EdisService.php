<?php

namespace App\Services\Edis;

use App\Jobs\ReadMeasureFromEDISAndStore;
use Edistribucion\EdisClient;
use Edistribucion\EdisError;

class EdisService
{
    private ?EdisClient $edis;

    public function __construct(?EdisClient $edis)
    {
        $this->edis = null;
        if ($edis) {
            $this->edis = $edis;
        }
    }

    /**
     * @throws \Exception
     */
    public function check(): bool
    {

        if (!$this->edis) {
            return false;
        }

        try {
            $this->edis->login();
        } catch (EdisError $e) {
            return false;
        }
        return true;
    }

    public function getSupplies()
    {
        return $this->edis->get_cups();
    }

    public function getLoginInfo()
    {
        return $this->edis->get_login_info();
    }

    public function getMeasure(string $internalId): array|string
    {
        return $this->edis->get_measure($internalId);
    }

    public function getMeasureInterval(string $internalId, \DateTime $startDate, \DateTime $endDate): array|string
    {
        return $this->edis->get_meas_interval($internalId, $startDate, $endDate);
    }

    public function getCUPSDetail(string $cupsID)
    {
        return $this->edis->get_cups_detail($cupsID);
    }


}
