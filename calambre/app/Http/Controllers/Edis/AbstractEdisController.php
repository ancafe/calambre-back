<?php

namespace App\Http\Controllers\Edis;

use App\Http\Controllers\Controller;
use App\Services\Edis\EdisService;

abstract class AbstractEdisController extends Controller
{
    protected EdisService $edisService;

    public function __construct(EdisService $edisService)
    {
        $this->edisService = $edisService;
    }
}
