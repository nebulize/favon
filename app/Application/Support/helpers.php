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

if (function_exists('include_route_files') === false) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new \recursiveDirectoryIterator($folder);
            $it = new \recursiveIteratorIterator($rdi);
            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }
                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (function_exists('glob_recursive') === false) {

    /**
     * Recursive implementation of the glob function.
     * Checks sub-folders as well.
     *
     * @param $pattern
     * @param int $flags
     * @return array
     */
    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }
}

if (function_exists('delete_directory') === false) {

    /**
     * Recursively delete a directory.
     *
     * @param string $dir
     *
     * @return bool
     */
    function delete_directory(string $dir)
    {
        if (file_exists($dir) === false) {
            return true;
        }

        if (is_dir($dir) === false) {
            return unlink($dir);
        }

        foreach (scandir($dir, 0) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (delete_directory($dir.DIRECTORY_SEPARATOR.$item) === false) {
                return false;
            }
        }

        return rmdir($dir);
    }
}
