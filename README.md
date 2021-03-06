
# PPOB Package for Laravel 6.x

The purpose of this package is to allow your website to purchase 
many prepaid digital products like Token PLN, Pulsa, Paket Data and more in Indonesia.

Right now, the following providers are supported:
- MobilePulsa
- PortalPulsa
- Tripay
- IndoH2H
- DIGIFLAZZ

## Installation

To get started with PPOB, run this command or add the package to your `composer.json`

    composer require idmahbub/ppob
    

## Configuration

The PPOB package use Laravel autodiscovery so it will be loaded automatically.
Copy the `config` file with the following command:
`php artisan vendor:publish --provider="Idmahbub\PPOB\PPOBServiceProvider"`

Finally add your provider's account in the `.env` file:
```
MOBILEPULSA_USERNAME=<your-phone>
MOBILEPULSA_APIKEY=<your-api-key>

PORTALPULSA_USERNAME=<your-username>
PORTALPULSA_APIKEY=<your-apikey>
PORTALPULSA_SECRET=<your-secret>

TRIPAY_APIKEY=<your-apikey>
TRIPAY_PIN=<your-pin>

INDOH2H_USERNAME=<your-username>
INDOH2H_APIKEY=<your-apikey>

DIGIFLAZZ_USERNAME=
DIGIFLAZZ_DEV_APIKEY=
DIGIFLAZZ_PROD_APIKEY=
```

To add more accounts in a single provider, add those accounts in `config/ppob.php`

```php
...
'accounts' => [
    'account-A' => [
        'provider' => 'mobile-pulsa',
        'username' => 'usernameA',
        'apikey' => 'apikeyA'
    ],
    'account-B' => [
        'provider' => 'mobile-pulsa',
        'username' => 'usernameB',
        'apikey' => 'apikeyB'
    ],
]
...
```


## How To Use

After all sets, use the PPOB as follows:
```php

use Idmahbub\PPOB\Products\Pulsa;
use Idmahbub\PPOB\Products\TokenPLN;
use Idmahbub\PPOB\Products\GenericProduct;

// Topup Pulsa
$status = PPOB::topup(new Pulsa('082112345678', 50000), 'ref123');

// Check your deposit balance 
$balance = PPOB::balance();

// Check status of a transaction
$status = PPOB::status('ref123');

// Use another account
$status = PPOB::account('account-portalpulsa')->topup(
  new TokenPLN('no-meter', 'no-hp', 100000), 'ref456'
);

// Purchase other products
$status = PPOB::account('account-tripay')->topup(
  new GenericProduct('subscriber-id', 'no-hp', 'product-code'), 'ref789'
);

//add method Pricelist as pre or pasca
$json += array("df" => PPOB::account('account-jh2h')->pricelist($request->category));

```

## Bugs & Improvements

Feel free to report me any bug you found.
