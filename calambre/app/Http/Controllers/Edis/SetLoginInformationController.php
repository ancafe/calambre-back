<?php

namespace App\Http\Controllers\Edis;

use App\Exceptions\ErrorDtoFactory;
use App\Exceptions\Type\ApiError;
use App\Http\Controllers\Controller;
use App\Models\EdisInfo;
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

        $edisInfo = EdisInfo::updateOrCreate([
            'user' => auth()->user()->id
        ],[
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'user' => auth()->user()->id
        ]);


        if (!$edisInfo->saveOrFail()) {
            throw new ApiError([ErrorDtoFactory::cantStorageInformation($validator->errors()->toArray())]);
        }

        Log::info('EDIS Login info stored for user ' . auth()->user()->id);
        return response()->json(new APISuccess('Login info saved'));

    }
}
