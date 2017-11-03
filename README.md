# Laravel QuickSlack

A simple package to quickly send messages to Slack channels through Laravel. Tested with Laravel 5.4 and 5.5.

## Installation

Use Composer to install the package:

```bash
composer require "joelhinz/laravel-quick-slack=0.*"
```

This package supports auto-discovery, so if you're using Laravel 5.5, you're all set.

For Laravel 5.4, or if you don't want to use auto-discovery, add the provider and optionally the facade alias to your `config/app.php` file:

```php
'providers' => [
    // ...
    JoelHinz\LaravelQuickSlack\ServiceProvider::class,
],

'aliases' => [
    // ...
    'QuickSlack' => JoelHinz\LaravelQuickSlack\Facade::class,
],
```

## Basic usage

Before sending messages, all you need to do is go to Slack and [create an incoming webhook](https://www.slack.com/services/new/incoming-webhook), then copy the webhook url.

```php
use QuickSlack;

QuickSlack::to($webhook)->send('My hovercraft is full of eels.');
```

The webhook url can be remembered for subsequent calls during the same script execution by passing a boolean as the second argument to the `to()` method:

```php
# Remember webhook for next call
QuickSlack::to($webhook, true)->send('My nipples explode with delight!');

# No need for the to() method this time
QuickSlack::send('I cannot wait till lunchtime.');

# Set a new webhook - the new webhook will now be remembered instead
QuickSlack::to($webhook)->send('Drop your panties, Sir William!');

# Set a new webhook but stop remembering
QuickSlack::to($webhook, false)->send('Bouncy-bouncy');

# Stop remembering without sending a message
QuickSlack::forgetRecipient();
```

Since QuickSlack is fluent, you can just keep chaining more messages if you want to. Please note that this requires a remembered webhook url, or a default webhook (see configuration options below).

```php
QuickSlack::send('first message')->send('second message')->send('third message');
```

Don't like how long the webhook urls get? No worries, you can just skip the first part of them. Instead of `https://hooks.slack.com/services/[rest of url]`, just enter everything after `services/` instead. QuickSlack will handle the rest automatically.

## Configuration

QuickSlack can be used without any configuration, but you can export the configuration to get features such as a default webhook and named webhooks.

```bash
php artisan vendor:publish --provider="JoelHinz\LaravelQuickSlack\ServiceProvider"
```

This will create a file `config/quick-slack.php` where you can set your configuration options as follows:

```php
<?php

return [
    /**
     * Set names for your webhooks.
     */
    'webhooks' => [
        'my-webhook' => 'some-webhook-url',
    ],

    /**
     * Set a default webhook to use if no other url is given explicitly.
     * This can be either a webhook url, or the name of a named webhook above.
     */
    'default' => '',
];
```

Again, you can enter either full urls or just the webhook-specific part of them.

By using named webhooks, you don't have to remember their full addresses when you send messages:

```php
QuickSlack::to('my-webhook')->send('I will not buy this record, it is scratched.');
```

If you only or mainly send to just one endpoint, you can put that as the default and omit the `to()` method. The default value can be overwritten by using the `to()` method, and the overwriting url can be remembered as per above.

Webhook names are recursive, so that you can use different names for the same endpoint:

```php
'webhooks' => [
    'email bounces' => 'https://hooks.slack.com/services/...',
    'email complaints' => 'email bounces',
]
```

They are also nestable if you prefer to organise things that way:

```
'webhooks' => [
    'email' => [
        'bounces' => 'https://hooks.slack.com/services/...',
        'complaints' => 'email.bounces',
    ]
]
```

## Webhook precedence

When sending a message, QuickSlack will determine which endpoint to use in the following order of precedence:

1. The webhook given in the last `to()` call if no message has been sent since then.
2. The webhook given in the last `to()` call if the remember option is set to true.
3. The default webhook in the configuration file.

## TODO

Planned functionality includes

* Automated tests and Travis integration
* Style CI integration
* Support for custom emojis and usernames
* Support for changing channel ad-hoc
* Support for saved teams in the configuration
* Support for additional styling as per Slack's payload documentation
* Custom response and exception handling
* Global helper `quickslack($message[, $endpoint])`

## License

Standard MIT License (MIT), available in [LICENSE](LICENSE).