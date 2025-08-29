<?php

use Illuminate\Http\Request;

class Helper
{
    public static function LogThrowable(Request $request, string $file, \Throwable $e): void
    {
        DB::table('error_logs')->insert([
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'user_id' => $request->user()?->id,
            'message' => $e->getMessage(),
            'stack_trace' => $e->getTraceAsString(),
            'file' => $file,
            'line' => $e->getLine(),
            'url' => $request->fullUrl(),
            'created_at' => now(),
        ]);
    }
}
