<?php

namespace R4nkt\LaravelDtoAction\Tests\TestClasses;

use Spatie\DataTransferObject\DataTransferObject;
use R4nkt\LaravelDtoAction\ExecutesDtoActions;

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
