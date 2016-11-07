# PHP2WSDL

[![Latest Stable Version](https://poser.pugx.org/professionalweb/php2wsdl/v/stable)](https://packagist.org/packages/professionalweb/php2wsdl)
[![Build Status](https://travis-ci.org/SergioMadness/php2wsdl.svg?branch=dev)](https://travis-ci.org/SergioMadness/php2wsdl)
[![Code Climate](https://codeclimate.com/github/SergioMadness/php2wsdl/badges/gpa.svg)](https://codeclimate.com/github/SergioMadness/php2wsdl)
[![Coverage Status](https://coveralls.io/repos/github/SergioMadness/php2wsdl/badge.svg?branch=dev)](https://coveralls.io/github/SergioMadness/php2wsdl?branch=dev)
[![License](https://poser.pugx.org/professionalweb/php2wsdl/license)](https://packagist.org/packages/professionalweb/php2wsdl)
[![Latest Unstable Version](https://poser.pugx.org/professionalweb/php2wsdl/v/unstable)](https://packagist.org/packages/professionalweb/php2wsdl)

Create WSDL files form PHP classes.

## Install

Via Composer

``` bash
$ composer require professionalweb/php2wsdl
```

## Usage

``` php
$class = "Vendor\\MyClass";
$serviceURI = "http://www.myservice.com/soap";
$serviceName = 'testService';
$wsdlGenerator = new PHP2WSDL\PHPClass2WSDL($class, $serviceURI, $serviceName);
$wsdlGenerator->addClass(AnotherClass::class);
// Generate thw WSDL from the class adding only the public methods that have @soap annotation.
$wsdlGenerator->generateWSDL();
$wsdlXML = $wsdlGenerator->dump();
```

## Testing

``` bash
$ phpunit
```

## Security

If you discover any security related issues, please email instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
