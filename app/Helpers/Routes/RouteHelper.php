<?php

namespace App\Helpers\Routes;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteHelper
{
    /**
     * Iterate through the $folder folder recursively
     * @param string $folder
     * @return void
     */
    public static function includeRouteFiles(string $folder): void
    {
        $dirIterator = new RecursiveDirectoryIterator($folder);

        /** @var RecursiveDirectoryIterator | RecursiveIteratorIterator $it */
        $it = new RecursiveIteratorIterator($dirIterator);

        // require the file in each iteration
        while ($it->valid()) {
            if (
                !$it->isDot()
                && $it->isFile()
                && $it->isReadable()
                && $it->current()->getExtension() === 'php'
            ) {
                require $it->key();
            }
            $it->next();
        }
    }
}
