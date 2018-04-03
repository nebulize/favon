<?php

use Illuminate\Support\Str;

if (function_exists('asset_path') === false) {

    /**
     * Get the path to a webpack asset (css/js).
     *
     * @param  string  $path
     *
     * @return string
     *
     * @throws \Exception
     */
    function asset_path($path)
    {
        if (Str::startsWith($path, '/') === false) {
            $path = "/{$path}";
        }

        if (file_exists(public_path('hmr'))) {
            return "//localhost:3000{$path}";
        }

        return $path;
    }
}
