# Slow Query Notifier for Laravel

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/thomasjohnkane/slow-query-notifier.svg?style=flat-square)](https://packagist.org/packages/thomasjohnkane/slow-query-notifier) -->

Get notified if your app ever runs an objectively slow database call. We set a default threshold, but you can configure it based on your needs & expectations.

## Installation

You can install the package via composer:

```bash
composer require thomasjohnkane/slow-query-notifier
```
## Usage
### Set an email address
```php
// app/Providers/AppServiceProvider.php

use SlowQueryNotifier\SlowQueryNotifierFacade as SlowQueryNotifier;

public function boot()
{
    SlowQueryNotifier::toEmail('admin@example.com');
}
```
### Test it works (in Production)
If you are using this in production (as intendend) make sure it is working correctly:
```bash
php artisan sqn:test
```
This command will test two things:

- We can detect slow queries in your app
- We can send an email to you if a slow query runs

## Configuration

In general, we setup all of the configuration for you with sensible defaults. However, you can change the default settings if you'd like. To learn why we chose these defaults, <a href="#">read the blog post</a>.

### Threshold

The default is 99ms. Set a different `threshold` in milliseconds in your configuration:
```php
SlowQueryNotifier::threshold(200)->toEmail('admin@example.com');
```
### Enable/Disable

The package is enabled by default. Set this value to `false` in your `.env` to bypass the listener.
```bash
SLOW_QUERY_NOTIFIER_ENABLED=false
```
## Testing

``` bash
phpunit
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email thomasjohnkane@gmail.com instead of using the issue tracker.

## Credits

- [Thomas Kane](https://github.com/thomasjohnkane)
- Thanks to Marcel Pociot for the original inspiration
- Thanks to Caleb Porzio for the guidance
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
