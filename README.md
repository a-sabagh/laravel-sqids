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
- Route model binding via `HasSqidRouting` trait
- Fully configurable alphabet, min length, and blocklist

---

## Installation

```bash
composer require a-sabagh/laravel-sqids
