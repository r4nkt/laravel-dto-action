<?php

namespace R4nkt\LaravelDtoAction\Tests;

use Error;
use Orchestra\Testbench\TestCase;
use R4nkt\LaravelDtoAction\Tests\TestClasses\NotifyUser;
use R4nkt\LaravelDtoAction\Tests\TestClasses\NotifyUserDto;
use R4nkt\LaravelDtoAction\Tests\TestClasses\NotifyUserExplicit;

class ActionTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideDtoActionTypes
     */
    public function it_returns_expected_dto($actionClass, $dtoClass)
    {
        $data = [
            'notification' => 'some.data',
            'username' => 'john.doe',
        ];

        $dto = $actionClass::dto($data);

        $this->assertInstanceOf($dtoClass, $dto);
        $this->assertSame('some.data', $dto->notification);
        $this->assertSame('john.doe', $dto->username);
    }

    public function provideDtoActionTypes()
    {
        return [
            [NotifyUser::class, NotifyUserDto::class],
            [NotifyUserExplicit::class, NotifyUserDto::class],
        ];
    }

    /**
     * @test
     * @dataProvider provideDtoActionTypes
     */
    public function it_returns_expected_dto_using_static_constructor($actionClass, $dtoClass)
    {
        $data = [
            'notification' => 'some.data',
            'username' => 'john.doe',
        ];

        $dto = $actionClass::dtoFromData($data);

        $this->assertInstanceOf($dtoClass, $dto);
        $this->assertSame('some.data', $dto->notification);
        $this->assertSame('john.doe', $dto->username);
    }

    /**
     * @test
     * @dataProvider provideDtoActionTypes
     */
    public function it_can_execute_action_via_dto($actionClass, $dtoClass)
    {
        $data = [
            'notification' => 'some.data',
            'username' => 'john.doe',
        ];

        $dto = $actionClass::dto($data);

        $this->partialMock($actionClass, function ($mock) use ($dto) {
            $mock
                ->shouldReceive('execute')
                ->once()
                ->with($dto);
        });

        $dto->execute();
    }

    /**
     * @test
     * @dataProvider provideDtoActionTypes
     */
    public function it_can_execute_action_via_dto_using_static_constructor($actionClass, $dtoClass)
    {
        $data = [
            'notification' => 'some.data',
            'username' => 'john.doe',
        ];

        $dto = $actionClass::dtoFromData($data);

        $this->partialMock($actionClass, function ($mock) use ($dto) {
            $mock
                ->shouldReceive('execute')
                ->once()
                ->with($dto);
        });

        $dto->execute();
    }

    /**
     * @test
     * @dataProvider provideDtoActionTypes
     */
    public function it_throws_an_exception_when_using_an_unknown_static_constructor($actionClass, $dtoClass)
    {
        $data = [
            'notification' => 'some.data',
            'username' => 'john.doe',
        ];

        $this->expectException(Error::class);

        $dto = $actionClass::dtoFromSomeUnknownStaticConstructor($data);
    }
}
