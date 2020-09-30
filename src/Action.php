<?php

namespace R4nkt\LaravelDtoAction;

use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObject;

abstract class Action
{
    protected static $dtoClass;

    public static function dto(array $parameters = []): DataTransferObject
    {
        $dtoClass = self::getDtoClass();

        /**
         * Create the DTO using the default constructor and then return it, but
         * not before explicitly setting the DTO action class.
         */
        return (new $dtoClass($parameters))->setDtoActionClass(static::class);
    }

    public static function __callStatic($name, $args)
    {
        if (! Str::startsWith($name, 'dto')) {
            return;
        }

        $dtoMethod = Str::after($name, 'dto');

        $dtoClass = self::getDtoClass();

        /**
         * Create the DTO using the custom static constructor and then return
         * it, but not before explicitly setting the DTO action class.
         */
        return $dtoClass::$dtoMethod(...$args)->setDtoActionClass(static::class);
    }

    protected static function getDtoClass()
    {
        if (static::$dtoClass) {
            return static::$dtoClass;
        }

        return static::class . 'Dto';
    }
}
