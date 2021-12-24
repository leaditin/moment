# Moment

A simple PHP API for Time, DateTime and DateRange.

[![Build Status][ico-build]][link-build]
[![Code Quality][ico-code-quality]][link-code-quality]
[![Code Coverage][ico-code-coverage]][link-code-coverage]
[![Latest Version][ico-version]][link-packagist]
[![PDS Skeleton][ico-pds]][link-pds]

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org/). Run the following command to install the latest version of a package and add it to your project's `composer.json`:

```bash
composer require Leaditin-education/moment
```

## Usage

**Instantiate Time object**
```php
use \Leaditin\Moment\Time;

$time = Time::fromSeconds(7200);
$time = Time::fromString('12:25');
```

**Instantiate DateTime object**
 ```php
use \Leaditin\Moment\DateTime;

$now = new DateTime();
$today = new DateTime('now', null, DateTime::TYPE_DATE);
$past = new DateTime('26 Jul 1984', null, DateTime::TYPE_DATE);
```

**Instantiate DateRange object**
```php
use \Leaditin\Moment\{DateTime, DateRange};

$range = new DateRange(new DateTime('2016-01-01 00:00:00'), new DateTime('2016-12-31 23:59:59'));
$infinityLeft = new DateRange(null, new DateTime());
$infinityRight = new DateRange(new DateTime(), null);
$infinity = new DateRange();
```

## Credits

- [All Contributors][link-contributors]

## License

Released under MIT License - see the [License File](LICENSE) for details.


[ico-version]: https://img.shields.io/packagist/v/Leaditin-education/moment.svg
[ico-build]: https://travis-ci.org/Leaditin-education/moment.svg?branch=master
[ico-code-coverage]: https://img.shields.io/scrutinizer/coverage/g/Leaditin-education/moment.svg
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Leaditin-education/moment.svg
[ico-pds]: https://img.shields.io/badge/pds-skeleton-blue.svg

[link-packagist]: https://packagist.org/packages/Leaditin-education/moment
[link-build]: https://travis-ci.org/Leaditin-education/moment
[link-code-coverage]: https://scrutinizer-ci.com/g/Leaditin-education/moment/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Leaditin-education/moment
[link-pds]: https://github.com/php-pds/skeleton
[link-contributors]: ../../contributors
