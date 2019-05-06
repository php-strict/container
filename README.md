# Container

[![Software License][ico-license]](LICENSE.txt)
[![Build Status][ico-travis]][link-travis]
[![codecov][ico-codecov]][link-codecov]
[![Codacy Badge][ico-codacy]][link-codacy]

Interface and implementation of common container.

Strict version of [PSR-11](https://www.php-fig.org/psr/psr-11/) 
with additional methods to set/get references (instead of values) 
and get all container entries as array.

## Requirements

*   PHP >= 7.1

## Install

Install with [Composer](http://getcomposer.org):
    
```bash
composer require php-strict/container
```

## Usage

Classic usage:

```php
use PhpStrict\Container\Container

$container = new Container();
$container->set('key1', 1);

if ($container->has('key1')) {
    $var1 = $container->get('key1');
}
```

Usage to set/get references:

```php
use PhpStrict\Container\Container

$myObject = new stdClass();

$container = new Container();
$container->setByRef('key2', $myObject);

$anotherObject =& $container->getRef('key2');
```

Set container values through constructor:

```php
use PhpStrict\Container\Container

$container = new Container([
    'key1' => 1,
    'key2' => 'value 2',
    'key3' => true,
]);

if ($container->has('key2')) {
    $var2 = $container->get('key2');
}
```

Unpacking container through callback:

```php
use PhpStrict\Container\Container

class MyClass
{
    protected $field1;
    protected $field2;
    protected $field3;
    
    public function unpacker(array $entries): void
    {
        foreach ($entries as $key => $value) {
            $this->$key = $value;
        }
    }
}

$container = new Container([
    'field1' => 1,
    'field2' => 'value 2',
    'field3' => true,
]);

$myClassObject = new MyClass();

$container->unpackWith([$myClassObject, 'unpacker']);
```

## Tests

To execute the test suite, you'll need [Codeception](https://codeception.com/).

```bash
vendor\bin\codecept run
```

[ico-license]: https://img.shields.io/badge/license-GPL-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/php-strict/container/master.svg?style=flat-square
[link-travis]: https://travis-ci.org/php-strict/container
[ico-codecov]: https://codecov.io/gh/php-strict/container/branch/master/graph/badge.svg
[link-codecov]: https://codecov.io/gh/php-strict/container
[ico-codacy]: https://api.codacy.com/project/badge/Grade/05da7e110e55465bae0d54da68c4f2d1
[link-codacy]: https://www.codacy.com/app/php-strict/container?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=php-strict/container&amp;utm_campaign=Badge_Grade
