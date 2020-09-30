# A (somewhat opinionated) Laravel package that makes working with actions and data transfer objects a little easier.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/r4nkt/laravel-dto-action.svg?style=flat-square)](https://packagist.org/packages/r4nkt/laravel-dto-action)
[![Tests](https://github.com/r4nkt/laravel-dto-action/workflows/run%20tests/badge.svg)](https://github.com/r4nkt/laravel-dto-action/actions?query=workflow%3A"run+tests")
[![Quality Score](https://img.shields.io/scrutinizer/g/r4nkt/laravel-dto-action.svg?style=flat-square)](https://scrutinizer-ci.com/g/r4nkt/laravel-dto-action)
[![Total Downloads](https://img.shields.io/packagist/dt/r4nkt/laravel-dto-action.svg?style=flat-square)](https://packagist.org/packages/r4nkt/laravel-dto-action)

This package is inspired by a few people. Please check out the following to see where the inspiration came from:
- [Brent](https://twitter.com/brendt_gd)'s articles on [working with data](https://stitcher.io/blog/laravel-beyond-crud-02-working-with-data) and [actions](https://stitcher.io/blog/laravel-beyond-crud-03-actions) in Laravel
- [Freek](https://twitter.com/freekmurze)'s [video](https://freek.dev/1545-how-to-avoid-large-function-signatures-by-using-pending-objects) about pending objects with actions
- and [Mohamed](https://twitter.com/themsaid)'s [video](https://divinglaravel.com/when-does-php-call-__destruct) on using `__destruct()` in similar situations

It should also be noted that it's a variation of [my own](https://twitter.com/traviselkins) [pending actions package](https://github.com/telkins/laravel-pending-action) that I hope will prove to be more useful.

Also important to note is the fact that this package is built around [Spatie's](https://spatie.be) [data transfer object package](https://github.com/spatie/data-transfer-object). In fact, it's required.

## Introduction

From time to time you may need to perform tasks or actions in your application or different parts of your code where there isn't an out-of-the-box Laravel solution. Most likely, this is or is related to your business logic. An oft-used example is creating an invoice. This is an action that may need to take place in one of your applications. It will likely require parameters, and it will likely be made up of smaller actions.

The goal of this package is to provide a (somewhat opinionated) way to make, define, and use actions along with data transfer objects (DTOs). You can create `Action` classes and then, when you want to execute that action, you "prep" that class: `$myAction = MyAction::dto();`. You get back a pending action object, which you define, that allows you to provide the parameters necessary for your action to be carried out. When ready, you call `execute()`.

Here's an example:

```php
$data = [
    'email' => 'me@mydomain.com',
    'list' => $emailList,
    'attributes' => $attributes,
    'confirm' => false,
    'send_welcome_mail' => false,
];

// create the DTO for the CreateSubscriber action and then execute the action
CreateSubscriber::dto($data)->execute();
```

## Installation

You can install the package via composer:

```bash
composer require r4nkt/laravel-dto-action
```

## Usage

For now, one must manually create your `Action` and `DataTransferObject` classes. A future release will include the ability to create `Action` and `DataTransferObject` classes via `php artisan`.

### Create an Action

``` php
use R4nkt\LaravelDtoAction\Action;

class CreateSubscriber extends Action
{
    public function execute(CreateSubscriberDto $dto)
    {
        // code to perform action, using $dto as needed...
    }

    // supporting methods, if needed...
}
```

#### DTO Class Naming Conventions

By default, each `Action` class will look for a DTO class that has the same FQCN with `Dto` appended. So, for our example `CreateSubscriber` class, it will look for `CreateSubscriberDto`.

To override this behavior, you can specify the DTO class name in your `Action` class like so:

``` php
use R4nkt\LaravelDtoAction\Action;
use My\Custom\Namespace\UnconventionalCreateSubscriberDto;

class CreateSubscriber extends Action
{
    // override default DTO class
    protected static $dtoClass = UnconventionalCreateSubscriberDto::class;

    // ...
}
```

### Create a DTO

To create a DTO class, refer to the [documentation](https://github.com/spatie/data-transfer-object). Once created, simply add the `ExecutesDtoActions` trait:

``` php
use App\EmailList;
use R4nkt\LaravelDtoAction\ExecutesDtoActions;
use Spatie\DataTransferObject\DataTransferObject;

class CreateSubscriberDto extends DataTransferObject
{
    use ExecutesDtoActions;

    public string $email;
    public EmailList $list;
    public array $attributes = [];
    public bool $confirm = true;
    public bool $send_welcome_mail = true;
}
```

### "Prep" Your Action, Carry it Out

Once you have built your action and DTO classes, then you can begin to use them. There are three main steps to preparing your action and executing it:
1. Call the static `dto()` method on your `Action` class. This returns the "action-aware" DTO.
2. Optionally, make any additional changes to your DTO.
3. Finally, call the `execute()` method on the pending action object.

Here is an example of preparing and executing an action all at once:

```php
UpdateLeaderboard::dto($data)->execute();
```

Here is an example of using the DTO to provide different parameters to carry out the action for different scenarios:

```php
$data = [
    'player' => $peter,
    'score' = $petersScore,
];

$updateLeaderboardDto = UpdateLeaderboard::dto($data);

$updateLeaderboardDto->execute();

$updateLeaderboardDto->player = $paul;
$updateLeaderboardDto->score = $paulsScore;
$updateLeaderboardDto->execute();
```

### Add a Custom Static Contructor

You can also take advantage of Spatie's DTO support for custom static constructors. Simply add a custom static constructor to your DTO per the documentation and then call it via the action by prepending `dto` to its name.

Here is an example of how you can do this:

``` php
class UpdateLeaderboardDto extends DataTransferObject
{
    // ...

    public static function fromRequest(Request $request): self
    {
        return new self([
            'player' => Player::find($request->input('player_id')),
            'score' => $request->input('score'),
        ]);
    }
}
```

You can then use it like so:

```php
UpdateLeaderboard::dtoFromRequest($request)->execute();
```

**NOTE: This functionality is not IDE-friendly and the developer will be responsible for passing the right types of arguments.**

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please use the issue tracker.

## Credits

- [Travis Elkins](https://github.com/telkins)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
