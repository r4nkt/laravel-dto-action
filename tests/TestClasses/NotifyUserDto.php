<?php

namespace R4nkt\LaravelDtoAction\Tests\TestClasses;

use R4nkt\LaravelDtoAction\ExecutesDtoActions;
use Spatie\DataTransferObject\DataTransferObject;

class NotifyUserDto extends DataTransferObject
{
    use ExecutesDtoActions;

    public string $notification;

    public string $username;

    public static function fromData(array $data): self
    {
        return new self([
            'notification' => $data['notification'],
            'username' => $data['username'],
        ]);
    }
}
