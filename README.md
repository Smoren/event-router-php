# event-router

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/smoren/event-router)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Smoren/event-router-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Smoren/event-router-php/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Smoren/event-router-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Smoren/event-router-php/?branch=master)
![Build and test](https://github.com/Smoren/event-router-php/actions/workflows/test_master.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
<!-- [![Coverage Status](https://coveralls.io/repos/github/Smoren/event-router-php/badge.svg?branch=master)](https://coveralls.io/github/Smoren/event-router-php?branch=master) -->

Router for flexible configuring of event handling behaviors.

### How to install to your project
```
composer require smoren/event-router
```

### Unit testing
```
composer install
composer test-init
composer test
```

### Usage

```php
use Smoren\EventRouter\Components\EventRouter;
use Smoren\EventRouter\Interfaces\EventInterface;
use Smoren\EventRouter\Events\Event;
use Smoren\EventRouter\Structs\EventConfig;
use Smoren\EventRouter\Loggers\ArrayLogger;

$router = new EventRouter(10, new ArrayLogger());
$router
    ->on(new EventConfig('origin1'), function(EventInterface $event) {
        return null;
    })
    ->on(new EventConfig('origin1', 'recursive_single'), function(EventInterface $event) {
        return new Event('origin2', 'test');
    })
    ->on(new EventConfig('origin1', 'recursive_multiple'), function(EventInterface $event) {
        return [
            new Event('origin1', 'recursive_single'),
            new Event('origin2', 'test'),
        ];
    })
    ->on(new EventConfig('origin2'), function(EventInterface $event) {
        return null;
    });

$router->send(new Event('origin1', 'first'));
```