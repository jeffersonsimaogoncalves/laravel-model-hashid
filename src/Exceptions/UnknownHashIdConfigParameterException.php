<?php

declare(strict_types=1);

namespace Deligoez\LaravelModelHashId\Exceptions;

use Exception;

final class UnknownHashIdConfigParameterException extends Exception
{
    /**
     * Create new UnknownHashIdConfigParameterException instance.
     *
     * @param  string  $parameter
     *
     * @return static
     */
    public static function make(string $parameter): self
    {
        return new UnknownHashIdConfigParameterException("Unknown HashId config parameter: '{$parameter}'.");
    }
}
