<?php

namespace App\Http\Controllers\Edis;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Services\API_Response\APISuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SetLoginInformationController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws ApiError|\Throwable
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new ApiError([ErrorDtoFactory::validation($validator->errors()->toArray())]);
        }

        auth()->user()->setAttribute("edisUsername", $request->get('username'));
        auth()->user()->setAttribute("edisPassword", $request->get('password'));

        if (!auth()->user()->saveOrFail()){
            throw new ApiError([ErrorDtoFactory::cantStorageInformation($validator->errors()->toArray())]);
        }

        Log::info('EDIS Login info stored for user '. auth()->user()->id);
        return response()->json(new APISuccess('Login info saved'));

    }
}
