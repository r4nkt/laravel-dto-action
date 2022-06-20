<?php

namespace R4nkt\LaravelDtoAction\Tests\TestClasses;

use R4nkt\LaravelDtoAction\Action;

class NotifyUserExplicit extends Action
{
    protected static $dtoClass = NotifyUserDto::class;

    public function __invoke(NotifyUserDto $dto)
    {
        // notify user identified by $dto->username...
    }
}
