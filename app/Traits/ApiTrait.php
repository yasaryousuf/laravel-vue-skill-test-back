<?php


namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

trait ApiTrait
{
    public function successApiResponseSaveData($message){
        $response['success'] = true;
        $response['message'] = $message;
        return response()->json($response, Response::HTTP_OK);
    }

    public function successApiResponseArray($response, $message){
        $response['success'] = true;
        $response['message'] = $message;
        return response()->json($response, Response::HTTP_OK);
    }

    public function successApiResponse($response, $message){
        if (is_array($response)) {
            $response['success'] = true;
            $response['message'] = $message;
            return response()->json($response, Response::HTTP_OK);
        }
        return $response->additional([
            'success' => true,
            'message' => $message
        ])->response()->setStatusCode(Response::HTTP_OK);
    }

    public function failureApiResponse($e){
        if ($e instanceof ValidationException) {
            $response['data'] = array();
            $response['success'] = false;
            $response['message'] = $e->validator->errors()->first();
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $response['data'] = array();
        $response['success'] = false;
        $response['message'] = $e->getMessage();
        return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function failureApiResponseSaveData($message){
        $response['success'] = false;
        $response['message'] = $message;
        return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function getFailureApiResponseArray($message){
        $response['data'] = array();
        $response['success'] = false;
        $response['message'] = $message;
        return $response;
    }
}
