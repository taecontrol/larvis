# Larvis

Larvis is a laravel package to push site monitoring data to Larastats.

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/taecontrol/larastats-wingman.svg?style=flat-square)](https://packagist.org/packages/taecontrol/larastats-wingman) -->

<!-- [![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/taecontrol/larvis/run-tests?label=tests)](https://github.com/taecontrol/larvis/actions?query=workflow%3Arun-tests+branch%3Amain) -->

<!-- [![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/taecontrol/larvis/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/taecontrol/larvis/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain) -->

<!-- [![Total Downloads](https://img.shields.io/packagist/dt/taecontrol/larastats-wingman.svg?style=flat-square)](https://packagist.org/packages/taecontrol/larastats-wingman) -->


## Installation

You can install the package via composer:

```bash
composer require taecontrol/larvis
```
On the `Handler.php` class, add the next code to capture all exceptions:

```php
...
/**
 * Register the exception handling callbacks for the application.
 *
 * @return void
 */
public function register()
{
    if (! app()->environment('testing')) {
        $this->reportable(function (Throwable $e) {
            /** @var Larvis $larvis */
            $larvis = app(Larvis::class);

            $larvis->captureException($e);
        });
    }
}
...
```

Define the next `.env` vars:
```dotenv
LARASTATS_DOMAIN=https://larastats.test
LARASTATS_SITE_API_TOKEN=********************
```

## Testing

```bash
composer test
```

## Credits

- [Luis Güette](https://github.com/guetteman)
- [Alexis Fraudita](https://github.com/alefram)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## TODO

- [ ] add version status
- [ ] add github test action status
- [ ] add github code style action status
- [ ] add total downloads
- [x] add License
- [x] add credits
