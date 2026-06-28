<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

trait RethrowsAuthorizationFailures
{
    protected function rethrowAuthorizationFailures(Throwable $e): void
    {
        if ($e instanceof HttpException || $e instanceof AuthorizationException) {
            throw $e;
        }
    }
}
