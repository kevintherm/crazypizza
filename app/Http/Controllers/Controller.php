<?php

namespace App\Http\Controllers;

use DB;
use Helper;
use Throwable;
use App\Http\Responses\ApiResponse;

abstract class Controller
{
    protected function safe(callable $callback)
    {
        try {
            DB::beginTransaction();

            $result = $callback();

            DB::commit();
            return $result;
        } catch (Throwable $e) {
            DB::rollBack();
            Helper::LogThrowable(request(), __FILE__, $e);
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
