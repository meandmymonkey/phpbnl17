<?php

namespace Workshop;

use Symfony\Component\HttpFoundation\StreamedResponse;

class LegacyWrapper
{
    public function render($requestPath, $legacyScript)
    {
        return StreamedResponse::create(
            function () use ($requestPath, $legacyScript) {
                $_SERVER['PHP_SELF'] = $requestPath;
                $_SERVER['SCRIPT_NAME'] = $requestPath;
                $_SERVER['SCRIPT_FILENAME'] = $legacyScript;
                chdir(dirname($legacyScript));

                require $legacyScript;
            }
        );
    }

}