<?php

namespace App\Http\Controllers\Edis;

use App\Http\Controllers\Controller;
use App\Services\Edis\EdisService;
use App\Services\FixEncrypter;

abstract class AbstractEdisController extends Controller
{
    protected EdisService $edisService;
    protected FixEncrypter $fixEncrypter;

    public function __construct(EdisService $edisService, FixEncrypter $fixEncrypter)
    {
        $this->edisService = $edisService;
        $this->fixEncrypter = $fixEncrypter;
    }
}
