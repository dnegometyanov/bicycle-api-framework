<?php

namespace Bicycle\Core;

/**
 * Core controller class
 *
 * Class Controller
 * @package Bicycle\Core
 */
class Controller
{
    /**
     * Checks basic auth
     *
     * @param Request $request
     * @param $authBasicToken
     * @return bool
     */
    public function checkBasicAuthToken(Request $request, $authBasicToken): bool
    {
        $authHeader = $request->getHeader('Authorization');

        return $authHeader == sprintf('Basic %s', $authBasicToken);
    }

    protected function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}