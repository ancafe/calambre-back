<?php

namespace App\Services\Edis;

use Edistribucion\EdisClient;
use Edistribucion\EdisError;

class CheckIfLoginIsSuccessService
{
    private EdisClient $edis;

    public function __construct(EdisClient $client)
    {
        $this->edis = $client;
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


}
