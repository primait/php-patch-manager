Patch Manager
=============
[![PHP Version](https://img.shields.io/packagist/php-v/prima-assicurazioni/patch-manager/dev-master)](https://packagist.org/packages/prima-assicurazioni/patch-manager)
[![Build Status](https://img.shields.io/github/actions/workflow/status/primait/php-patch-manager/ci.yml?branch=master)](https://github.com/primait/php-patch-manager/actions)
[![Packagist Version](https://img.shields.io/packagist/v/prima-assicurazioni/patch-manager)](https://packagist.org/packages/prima-assicurazioni/patch-manager)
[![Packagist Downloads](https://img.shields.io/packagist/dt/prima-assicurazioni/patch-manager)](https://packagist.org/packages/prima-assicurazioni/patch-manager)

A php library to manage PATCH requests in a standardized (and elegant) way

## Install

Install with composer

```shell
composer require prima-assicurazioni/patch-manager
```

The idea for this library comes from this blog post: [Please. Don't Patch Like An Idiot.](http://williamdurand.fr/2014/02/14/please-do-not-patch-like-an-idiot/) by [William Durand](https://github.com/willdurand)

> It lets you patch resources in an *expressive way*

**PATCH /users/1**
``` json
{ "op": "data", "property": "username", "value": "new username" }
```

And let you **patch entire collections** with multiple operations

**PATCH /books**
``` json
[{ "op": "set_as_read" }, { "op": "return_to_library", "address": "221 B Baker St, London, England"}]
```

> it includes also a **Symfony bundle**

Still interested? [Head over to the wiki...](https://github.com/matteosister/patch-manager/wiki) for documentation

## Useful commands for development

- `composer format`: runs php-cs-fixer
- `composer analyse`: runs phpstan for static analysis
- `composer test`: runs phpunit