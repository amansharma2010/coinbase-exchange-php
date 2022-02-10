# Coinbase Exchange API for PHP

A PHP library for communicating with the Coinbase Exchange.

**WARNING: This is a work in progress. Some parts may not work as expected.**

## Installation

### Composer Install

Require the library in your `composer.json`. ([What is Composer?](https://getcomposer.org/))

    "require": {
        "amansharma2010/coinbase-exchange": ">=0.1"
    }

### Manual Install

Download the [latest release](https://github.com/amansharma2010/coinbase-exchange-php/releases) and require `lib/CoinbaseExchange.php`.

    require_once('lib/CoinbaseExchange.php');

## Usage

Detailed usage can be found in [lib/CoinbaseExchange/CoinbaseExchange.php](lib/CoinbaseExchange/CoinbaseExchange.php).

### Public endpoints

Public endpoints do not require authentication.

    $exchange = new CoinbaseExchange();
    print_r($exchange->getTicker(), 1);
    print_r($exchange->getTime(), 1);

### Private endpoints for sandbox

Private endpoints require authentication for sandbox. Create an Sandbox API key at [https://public.sandbox.exchange.coinbase.com/profile/api](https://public.sandbox.exchange.coinbase.com/profile/api).

    $exchange = new CoinbaseExchange();
    $exchange->auth('key', 'passphrase', 'secret', TRUE);
    $exchange->placeOrder('sell', '1200.01', '.25', 'BTC-USD');

### Private endpoints

Private endpoints require authentication. Create an API key at [https://exchange.coinbase.com/profile/api](https://exchange.coinbase.com/profile/api).

    $exchange = new CoinbaseExchange();
    $exchange->auth('key', 'passphrase', 'secret', FALSE);
    $exchange->placeOrder('sell', '1200.01', '.25', 'BTC-USD');

## Tests

Tests can be run with:

    ./test/runner.sh

## TODO

- [x] Implement public endpoints.
- [x] Implement private trade enpoints.
- [ ] Add tests (started).
- [ ] Implement transfer endpoint.
