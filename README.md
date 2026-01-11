# Taiwan ID Validator (for PHP)

[![PHP Test](https://github.com/guanting112/php-taiwan-id-validator/actions/workflows/php.yml/badge.svg)](https://github.com/guanting112/php-taiwan-id-validator/actions/workflows/php.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A lightweight, zero-dependency PHP package for validating Taiwan National IDs, Alien Resident Certificates (ARC), and Unified Business Numbers (UBN).

## Other Implementations

- [Golang](https://github.com/guanting112/taiwan-id-validator)
- [Ruby](https://github.com/guanting112/taiwan-id-validator-ruby)

## Features

- **Taiwan National ID** (中華民國身分證字號)
- **New Alien Resident Certificate** (新式外來人口統一證號) (Since 2021)
- **Old Alien Resident Certificate** (舊式外來人口統一證號)
- **Unified Business Number (UBN)**: Validates company tax IDs (統一編號), fully supporting the logic (including the 7th-digit rule).

## Installation

Install via Composer:

```bash
composer require guanting112/php-taiwan-id-validator
```

## Usage

```php
use TaiwanIdValidator\TaiwanIdValidator;

// General Validation (National ID or ARC)
TaiwanIdValidator::validate('A123456789'); // true (National ID)
TaiwanIdValidator::validate('A800000014'); // true (New ARC)
TaiwanIdValidator::validate('AC01234567'); // true (Old ARC)

// Specific Validation
TaiwanIdValidator::validateNationId('A123456789'); // true
TaiwanIdValidator::validateArcId('A800000014'); // true
TaiwanIdValidator::validateUbn('12345678'); // false

// Taiwan Company UBN
TaiwanIdValidator::validateUbn("84149961") // true
TaiwanIdValidator::validateUbn("22099131") // true
TaiwanIdValidator::validateUbn("!@)(#&6y01)") // false
TaiwanIdValidator::validateUbn("25317520") // false
TaiwanIdValidator::validateUbn("00000000") // false
```

### Examples

```php
require 'vendor/autoload.php';

use TaiwanIdValidator\TaiwanIdValidator;

// National ID
var_dump(TaiwanIdValidator::validate('A123456789'));         // bool(true)
var_dump(TaiwanIdValidator::validateNationId('A123456789')); // bool(true)
var_dump(TaiwanIdValidator::validateNationId('A123456700')); // bool(false)

// Taiwan Company UBN
var_dump(TaiwanIdValidator::validateUbn('84149961')); // bool(true)
var_dump(TaiwanIdValidator::validateUbn('00000000')); // bool(false)

// New ARC
var_dump(TaiwanIdValidator::validate('A800000014'));      // bool(true)
var_dump(TaiwanIdValidator::validateArcId('A800000014')); // bool(true)

// Old ARC
var_dump(TaiwanIdValidator::validate('AC01234567'));      // bool(true)
var_dump(TaiwanIdValidator::validateArcId('AC01234567')); // bool(true)
```

## Testing

Run unit tests with PHPUnit:

```bash
./vendor/bin/phpunit tests
```

## License

MIT License
