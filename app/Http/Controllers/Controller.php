<?php

namespace App\Http\Controllers;

use DB;
use Helper;
use Throwable;
use App\Http\Responses\ApiResponse;

abstract class Controller
{
    protected function safe(callable $callback, string $file = __FILE__)
    {
        try {
            DB::beginTransaction();

            $result = $callback();

            DB::commit();
            return $result;
        } catch (Throwable $e) {
            DB::rollBack();
            Helper::LogThrowable(request(), $file, $e);
            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
