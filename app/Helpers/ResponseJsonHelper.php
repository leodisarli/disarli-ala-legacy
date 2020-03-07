<?php

namespace App\Helpers;

use App\Helpers\UuidHelper;
use Illuminate\Http\JsonResponse;

class ResponseJsonHelper
{
    public static $hasProfiler = false;
    
    public static $data;
    public static $profiler;
    public static $requestId;
    public static $token;

    /**
     * Start profile count
     * @return void
     */
    public static function setStartProfiler()
    {
        $uuid = new UuidHelper();
        self::$requestId = $uuid::uuid4();
        self::$profiler['request_id'] = self::$requestId;
        self::$profiler['start'] = microtime(true);
        self::$hasProfiler = true;
    }

    /**
     * Set token to profile
     * @param string $token token to set
     * @param string $validUntil validation date
     * @return void
     */
    public static function setTokenToProfiler(
        string $token,
        string $validUntil
    ) {
        self::$profiler['token'] = $token;
        self::$profiler['token_valid_until'] = $validUntil;
    }
    
    /**
     * Finish profile count
     * @return void
     */
    public static function setFinishProfiler()
    {
        self::$profiler['finish'] = microtime(true);
        self::$profiler['process'] = (self::$profiler['finish'] - self::$profiler['start']);
    }
    
    /**
     * Prepare JSON response
     * @param array $data data
     * @param array $paginator data
     * @return JsonObject response
     */
    public static function response(
        array $data = [],
        array $paginator = null,
        int $code = 200
    ) {
        $info = config('version.info');
        self::$data['success'] = true;
        self::$data['data'] = $data;
        
        if (self::$hasProfiler) {
            self::setFinishProfiler();
            self::$data['profile'] = self::$profiler;
        }
        
        if ($paginator) {
            self::$data['paginator'] = $paginator;
        }
        self::$data['version'] = $info['version'];
        return response()->json(self::$data, $code);
    }

        /**
     * Prepare 204 delete response
     * @return Response response
     */
    public static function responseDelete()
    {
        return response('', 204);
    }
    
    /**
     * Prepare JSON errors responses
     * @param string $code error code
     * @param mixed $msg error message
     * @param int $statusCode http status code
     * @return JsonObject response
     */
    public static function error(
        string $code,
        $msg,
        int $statusCode = 500
    ) {
        $info = config('version.info');
        self::$data['success'] = false;
        self::$data['code'] = $code;
        self::$data['details'] = $msg;
        
        if (self::$hasProfiler) {
            self::setFinishProfiler();
            self::$data['profiler'] = self::$profiler;
        }
        self::$data['version'] = $info['version'];
        return response()->json(self::$data, $statusCode);
    }
}
