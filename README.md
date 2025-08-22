# Laravel Sqids

[![Packagist](https://img.shields.io/packagist/v/a-sabagh/laravel-sqids.svg)](https://packagist.org/packages/a-sabagh/laravel-sqids)
[![License](https://img.shields.io/github/license/a-sabagh/laravel-sqids.svg)](LICENSE)

Laravel adapter for [sqids-php](https://github.com/sqids/sqids-php).  
Generate short, unique, non-sequential IDs for your models, routes, validation, and more.

---

## Features

- Generate short, unique, non-sequential IDs
- Easy helpers: `sqid($id)` / `unsqid($hash)`
- Facade: `Sqids::encode()` / `decode()`
- Validation rule: `sqid`
- Fully configurable alphabet, min length, and blocklist

---

## Installation

```bash
composer require a-sabagh/laravel-sqids
```

## Simple Encode & Decode

The `Sqids` service allows you to **convert integers into short, URL-safe strings** and decode them back.

> [!IMPORTANT]
> Sqids require either the [`bcmath`](https://secure.php.net/manual/en/book.bc.php) or [`gmp`](https://secure.php.net/manual/en/book.gmp.php) extension in order to work.

### Usage

```php
use ASabagh\LaravelSqids\Facades\Sqids;

$id = 123;

$encodeString = Sqids::encode([$id]); // e.g. "Lqj8a0"

$decodeNumber = Sqids::decode($encodeString); // [123]
```

### Encode and Decode Single Integer

```php
$id = 456;

$encodeString = Sqids::encodeInteger($id);

$decodeInteger = Sqids::decodeInteger($encodeString);
```

### Decode and get a Laravel Collection

```php
$decodeCollection = Sqids::decodeCollect($encodeString);
```

Notes:

- `encode` always expects an array of integers.

- `decode` returns an array.

- `encodeInteger` / `decodeInteger` work with single integers.

- `decodeCollect` returns a `Collection` for easier chaining with Laravel collections.

## Configuration

Laravel Sqids allows you to customize its behavior via a configuration file. Publishing the configuration lets you modify settings such as the alphabet, minimum length, and blocklist.

### Publish the Config File

Use the following Artisan command to publish the configuration to your application:

```bash
php artisan vendor:publish --tag=laravel-sqids
```

Here are each of the drivers setup for your application. Example configuration has been included, but you may add as many drivers as you would like.

```php
'drivers' => [
  'default' => [
    'pad' => env('SQIDS_DEFAULT_PAD', ''),
    'length' => env('SQIDS_DEFAULT_LENGTH', 6),
    'blocklist' => env('SQIDS_DEFAULT_BLOCK_LIST', []),
    'alphabet' => env('SQIDS_DEFAULT_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
  ],
]
```

Laravel Sqids allows you to publish the configuration and define multiple drivers, each with its own settings.

### Driver Setup

The configuration file includes a `drivers` array. You can configure one or more drivers for different encoding strategies.

You can add additional drivers with their own configuration:

```php
'drivers' => [
    'default' => [ /* default config */ ],

    'short_ids' => [
        'pad'       => '0',
        'length'    => 4,
        'blocklist' => ['0', 'O', 'I', '1'],
        'alphabet'  => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',
    ],
],
```

Then, you can use a specific driver when encoding/decoding:

```php
$encoded = Sqids::driver('short_ids')->encode([$id]);
$decoded = Sqids::driver('short_ids')->decode($encoded);
```

Once a driver has been registered and configured in your `config/sqids.php`, you can access it directly using a **camelCase method** on the `Sqids` Facade.

```php
$shortId = Sqids::shortIds()->encode([$id]);
$defaultId = Sqids::default()->encode([$id]);
```

#### Check if an Encoded String is Valid

```php
$isValid = Sqids::encodedStringValid($randomString);
```

## Sqids Custom Validation Rule

Laravel Sqids provides a **custom validation rule** to ensure that a given input is a valid Sqid string. You can use it in form requests, inline validation, and with custom drivers.

```php
$encoded = Sqids::encodeInteger($id);

$validator = Validator::make(
    ['endpoint' => $encoded],
    ['endpoint' => [new SqidsValidationRule]]
);

if ($validator->passes()) {
    // Valid Sqid
}
```