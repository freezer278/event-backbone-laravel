
[//]: # ([<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />]&#40;https://supportukrainenow.org&#41;)

# Event Backbone Laravel
## Package for convenient usage of Event Driven Microservices communication using Event Backbone (Apache Kafka).

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vmorozov/event-backbone-laravel.svg?style=flat-square)](https://packagist.org/packages/vmorozov/event-backbone-laravel)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/vmorozov/event-backbone-laravel/run-tests?label=tests)](https://github.com/vmorozov/event-backbone-laravel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/vmorozov/event-backbone-laravel/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/vmorozov/event-backbone-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/vmorozov/event-backbone-laravel.svg?style=flat-square)](https://packagist.org/packages/vmorozov/event-backbone-laravel)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

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

You can publish the config file with:

```bash
php artisan vendor:publish --tag="event-backbone-laravel-config"
```

## Usage

```php
$eventBackboneLaravel = new Vmorozov\EventBackboneLaravel();
echo $eventBackboneLaravel->echoPhrase('Hello, Vmorozov!');
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
