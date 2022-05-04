<?php

namespace App\Services\Edis;

use Edistribucion\EdisClient;
use Edistribucion\EdisError;

class EdisService
{
    private ?EdisClient $edis;

    public function __construct(?EdisClient $edis)
    {
        if ($edis){
            $this->edis = $edis;
        }
    }

    /**
     * @throws \Exception
     */
    public function check() : bool
    {
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


}
