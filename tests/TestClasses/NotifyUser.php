<?php

namespace R4nkt\LaravelDtoAction\Tests\TestClasses;

use R4nkt\LaravelDtoAction\Action;

class NotifyUser extends Action
{
    public function __invoke(NotifyUserDto $dto)
    {
        // notify user identified by $dto->username...
    }
}
