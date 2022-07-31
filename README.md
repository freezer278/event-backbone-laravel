
[//]: # ([<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />]&#40;https://supportukrainenow.org&#41;)

# Event Backbone Laravel
[//]: # ([![Latest Version on Packagist]&#40;https://img.shields.io/packagist/v/vmorozov/event-backbone-laravel.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/vmorozov/event-backbone-laravel&#41;)

[//]: # ([![GitHub Tests Action Status]&#40;https://img.shields.io/github/workflow/status/vmorozov/event-backbone-laravel/run-tests?label=tests&#41;]&#40;https://github.com/vmorozov/event-backbone-laravel/actions?query=workflow%3Arun-tests+branch%3Amain&#41;)

[//]: # ([![GitHub Code Style Action Status]&#40;https://img.shields.io/github/workflow/status/vmorozov/event-backbone-laravel/Fix%20PHP%20code%20style%20issues?label=code%20style&#41;]&#40;https://github.com/vmorozov/event-backbone-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain&#41;)

[//]: # ([![Total Downloads]&#40;https://img.shields.io/packagist/dt/vmorozov/event-backbone-laravel.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/vmorozov/event-backbone-laravel&#41;)

Package for convenient usage of Event Driven Microservices communication using Event Backbone (for now only Apache Kafka is supported).

[//]: # (## Support us)

[//]: # ()
[//]: # ([<img src="https://github-ads.s3.eu-central-1.amazonaws.com/event_backbone_laravel.jpg?t=1" width="419px" />]&#40;https://spatie.be/github-ad-click/event_backbone_laravel&#41;)

[//]: # ()
[//]: # (We invest a lot of resources into creating [best in class open source packages]&#40;https://spatie.be/open-source&#41;. You can support us by [buying one of our paid products]&#40;https://spatie.be/open-source/support-us&#41;.)

[//]: # ()
[//]: # (We highly appreciate you sending us a postcard from your hometown, mentioning which of our package&#40;s&#41; you are using. You'll find our address on [our contact page]&#40;https://spatie.be/about-us&#41;. We publish all received postcards on [our virtual postcard wall]&#40;https://spatie.be/open-source/postcards&#41;.)

## Installation

You can install the package via composer:
```bash
composer require vmorozov/event-backbone-laravel
```

You will have to publish the config file with:
```bash
php artisan vendor:publish --tag="event-backbone-laravel-config"
```

## Usage
### Producing events
To start producing events you have to create your first event that implements `Vmorozov\EventBackboneLaravel\Producer\ExternalEvent` interface.
```php
<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vmorozov\EventBackboneLaravel\Producer\ExternalEvent;

class UserCreated implements ExternalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getTopic(): string
    {
        return 'users';
    }

    public function getName(): string
    {
        return 'user_created';
    }

    public function getKey(): string
    {
        return $this->user->getKey();
    }

    public function getPayload()
    {
        return $this->user->toJson();
    }

    public function getHeaders(): array
    {
        return [];
    }
}

```
Then you have to fire event created in previous step.
```php
use App\Events\UserCreated;

// some code here
event(new UserCreated($user));
```
### Consuming events
To start consuming events you have to create your first event that implements `Vmorozov\EventBackboneLaravel\Consumer\ExternalConsumedEvent` interface and create Laravel Event Listener for it.  
Here is the example for consuming event produced in "Producing events" section.  
Create event:
```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Vmorozov\EventBackboneLaravel\Consumer\ExternalConsumedEvent;

class UserCreatedConsumedEvent implements ExternalConsumedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function getId()
    {
        return $this->payload['id'];
    }

    public function getName(): string
    {
        return $this->payload['name'];
    }

    public function getEmail(): string
    {
        return $this->payload['email'];
    }
}
```
Create listener:
```php
<?php

namespace App\Listeners;

use App\Events\UserCreatedConsumedEvent;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreatedConsumedEvent $event): void
    {
        if (User::query()->find($event->getId())) {
            return;
        }

        User::unguard();
        User::query()->create([
            'id' => $event->getId(),
            'name' => $event->getName(),
            'email' => $event->getEmail(),
        ]);
    }
}
```
Register your listener in `EventServiceProvider`:
```php
protected $listen = [
    \App\Events\UserCreatedConsumedEvent::class => [
        \App\Listeners\UserCreatedListener::class,
    ],
];
```
To start consuming events run following artisan command:
```php
php artisan event_backbone:consume
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/vmorozov/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vladimir Morozov](https://github.com/vmorozov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
