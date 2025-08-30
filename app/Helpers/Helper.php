<?php

use Illuminate\Http\Request;

const MB_IN_BYTES = 1048576;

class Helper
{
    public static function LogThrowable(Request $request, string $file, \Throwable $e): void
    {
        @DB::table('error_logs')->insert([
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

    public static function uploadFile($file, string $folder, string $disk = 'public'): ?string
    {
        if ($file) {
            $path = $file->store($folder, $disk);
            return $disk === 'public' ? "/storage/$path" : $path;
        }

        return null;
    }

    public static function deleteFile(string $path, string $disk = 'public'): void
    {
        Storage::disk($disk)->delete($disk === 'public' ? str_replace('/storage/', '', $path) : $path);
    }
}
