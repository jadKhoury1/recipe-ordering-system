<?php


namespace App\Base;

use Illuminate\Http\JsonResponse;

class BaseResponse extends JsonResponse
{
    /**
     * Added the const variables that will be needed in the class
     */
    const OK     = 'OK';
    const FAILED = 'FAILED';


    /**
     * This method is used to return success response
     *
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function statusOk($data = [], $statusCode = 200, $headers = [])
    {
        return self::createResponse($data, self::OK, $statusCode, $headers);
    }

    /**
     * This method is used to return Failed response
     *
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function statusFail($data = [], $statusCode = 200, $headers = [])
    {
        return self::createResponse($data, self::FAILED, $statusCode, $headers);
    }

    /**
     * This method is used to return Forbidden response
     *
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return JsonResponse
     */
    public static function forbidden($data = [], $statusCode = 403, $headers = [])
    {
        return self::createResponse($data, self::FAILED, $statusCode, $headers);
    }

    /**
     * This method is used to return unauthorized response
     *
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return JsonResponse
     */
    public static function unauthorized($data = [], $statusCode = 401, $headers = [])
    {
        return self::createResponse($data, self::FAILED, $statusCode, $headers);
    }

    /**
     * Build the JSON response
     *
     * @param array $data
     * @param string $status
     * @param int $statusCode
     * @param array $headers
     *
     * @return JsonResponse
     */
    private static function createResponse($data, $status, $statusCode, $headers = [])
    {
        $dataToReturn = [];

        $dataToReturn['status']   = $status;
        $dataToReturn['response'] = $data;
        return parent::fromJsonString(json_encode($dataToReturn), $statusCode, $headers);
    }
}