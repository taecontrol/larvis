# Introduction - Getting to Know Larvis

Larvis is a PHP package for Laravel that allows a Laravel app to send and report information to MoonGuard or Krater.

Currently, you can use Larvis in the following scenarios:

- To report exceptions from your application to MoonGuard (ideal for production sites that require tracking).

- To report and send messages, exceptions, database queries, and HTTP requests from a Laravel application to Krater (ideal for a development environment where different information needs to be debugged).

> 💡 **Important:** This version is compatible only with MoonGuard 1.0.0.

## What is MoonGuard?

- MoonGuard is an open source Filament plugin that aims to provide utilities to monitor your Laravel apps in production.

- MoonGuard helps you track important features like SSL certificates status, uptime, and exceptions on your applications.

For more information visit the [MoonGuard]( https://moonguard.dev/) official site.

## What is Krater?

- Krater is a desktop application for debugging Laravel apps, it provides an intuitive and friendly interface to debug exceptions, queries, requests and more.

For more information visit the [Krater](https://moonguard.dev/krater) official site.

# Installation

You can install Larvis via composer with the following command:

```bash
composer require taecontrol/larvis
```

After installation, we recommend publishing the configuration to customize Larvis' behavior according to your needs.

```bash
php artisan vendor:publish --tag larvis-config
```

That’s it! Larvis is installed in your project and the configuration file must be available at `config/larvis.php`, now you must decide how Larvis should work and behave:

1. Setup Larvis to work with MoonGuard.
2. Setup Larvis to work with Krater.

> 💡 **Important:** The default behavior of Larvis is to work with Krater.

## Setup Larvis to work with MoonGuard

To allow Larvis to capture and report exceptions, it is necessary to add three variables on the app `.env` file:

```php
MOONGUARD_DOMAIN=https://mymoonguard.com
MOONGUARD_SITE_API_TOKEN=LDUxazsuq6aYi9bvSMqc6vkMXOjsD7JdrIN2FkWtA4UVNhaPE02gMS23FIp0
KRATER_DEBUG=false
```

| Variable                | Description                                        |
| ----------------------- | -------------------------------------------------- |
| MOONGUARD_DOMAIN        | The domain where the MoonGuard is located.         |
| MONGUARD_SITE_API_TOKEN | The app site API Token.                            |
| KRATER_DEBUG            | Enables or disables Larvis Debug Mode with Krater. |

You can obtain the API token at the MoonGuard admin panel (Site administration). This token is unique to your site and is used to authenticate the site in every request to MoonGuard.

![Image](https://github.com/taecontrol/larvis/assets/41251063/211a0b25-1cbf-4f41-bf9c-454f92030267)

No extra steps needed. With this setup Larvis can report your app exceptions to MoonGuard.

> 💡 **Important:** Reporting exceptions to MoonGuard only works when the app environment is `production`.

## Setup Larvis to work with Krater

Larvis default behavior is to work and debug with Krater. We have develop several utilities to catch and report data as messages, exceptions, queries and requests easily to Krater.

### Watchers

Watchers are components that monitor and record different aspects of your application, such as requests, database queries, and exceptions. Larvis includes the following watchers:

1. QueryWatcher: Detects and reports all queries that are made to the app database.
2. ExceptionWatcher: Detects and reports any exception that have occurred in the app.
3. RequestWatcher: Detects and report any request that have been made to the app.

All the watchers can be enabled or disabled from the Larvis configuration file.

> 💡 **Important**: RequestWatcher and QueryWatcher are not compatible with MoonGuard.

## Sending Messages to Krater

Larvis understand a message as the data you want to send to Krater, similar to when you use `dd($arg)` in your projects. In this case, Larvis and Krater use different strategies to format and make the debugging of the data flexible.

There is a global function called `larvis($args)` that allows you to send any type of data to Krater as a message. Here are some examples of how to use it:

### Sending an array of strings

```php
larvis(["hello","i'm Larvis"]);
```

### Sending a string

```php
larvis("Hello");
```

### Sending an array of numbers

```php
larvis([1, 2, 3, 4, 5]);
```

### Sending a null value

```php
larvis(null);
```

### Sending a collection

```php
$collection = collect([1, 2, 3, 4, 5]);
larvis($collection);
```

### Sending an object

```php
$user = new User();
$user->name = 'John Doe';
$user->email = 'johndoe@example.com';
$user->age = 30;
larvis($user);
```

## Watching and Sending Queries to Krater

In order to watch and send queries to Krater the QueryWatcher must be enabled, as we mentioned earlier, this watcher allows you to detect and report all queries that are made to the app database.

QueryWatcher can be enabled in Larvis configuration:

```php
[
    'watchers' => [
        'queries' => [
            'enabled' => true,
        ],
    ],
];
```

To record isolated queries, you can use the `startQueryWatch()` and `stopQueryWatch()` as follow:

```php
larvis()->startQueryWatch();
User::all();
larvis()->stopQueryWatch();
```

- The `startQueryWatch()`: starts the QueryWatcher, any query after this line will be recorded and reported.
- The `stopQueryWatch()`: stops the QueryWatcher, any query after this line will not be logged or reported.

## Watching and Sending Exceptions to Krater

In order to watch and send exceptions to Krater the ExceptionWatcher must be enabled, this watcher is enabled by default.

```php
[
    'watchers' => [
        'exceptions' => [
            'enabled' => true,
        ],
    ],
];
```

## Watching and Sending Request to Krater

In order to watch and send request to Krater the RequestWatcher must be enabled, is enables the ability to monitor HTTP requests in your Laravel application and send the relevant information to Krater.

```php
[
    'watchers' => [
        'request' => [
            'enabled' => true,
        ],
    ],
];
```

## Readers

A reader is a utility we have developed to process PHP objects in a practical way. Its versatility allows you to specify the properties that need to be processed from an object, you can modify this in the Larvis configuration.

Currently, this property specification is only compatible with the following Illuminate classes:

- `Illuminate\Database\Eloquent\Model`
- `Illuminate\Support\Collection`

In the configuration file, you will find something like:

```php
[
    'readers' => [
        'model' => [
            'props' => [
                'connection',
                'table',
                'primaryKey',
                'keyType',
                'incrementing',
                'with',
                'withCount',
                //...
            ],
        ],
    ],
];
```

Here you can modify the properties you need to debug an Illuminate model according to your preferences.

You can do the same with an Collection from Illuminate:

```php
[
    'collection' => [
        'props' => [
            'items',
            'escapeWhenCastingToString',
        ],
    ],
];

```

## Commands

Larvis allows you to execute commands for different purposes.

- **`CheckHardwareHealthCommand`**

    You can check your last 5 minutes of CPU Load, memory usage, total disk space, free disk space, and send this information to the MoonGuard Filament Plugin to monitor these hardware variables. To use the command, you need to register it in your `console/Kernel.php` file:

the MoonGuard team recommends a frequency of less than 5 minutes:

```php

protected function schedule(Schedule $schedule): void
{
    $schedule->command(CheckHardwareHealthCommand::class)
        ->everyFiveMinutes();
}
```

Then, you can use it as:

```bash
php artisan schedule:work
```

## Contributing

Thanks for consider contributing to Larvis, The contribution guide can be
found in the [MoonGuard documentation](https://docs.moonguard.dev/contributions).

## Credits

- [Luis Güette](https://github.com/guetteman)
- [Ronald Pereira](https://github.com/rpereira-tae)
- [Wilson Velasco](https://github.com/w1ls0nv3l)
- [Alexis Fraudita](https://github.com/alefram)

## License

The Larvis package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
