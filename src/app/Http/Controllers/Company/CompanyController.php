<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Supply;
use App\Services\API_Response\APISuccess;

class CompanyController extends Controller
{
    public function getAll()
    {
        return response()->json(new APISuccess(Company::all()->toArray()));

    }
}
