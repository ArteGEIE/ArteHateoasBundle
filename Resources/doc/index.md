ArteHateoasBundle
=================

This bundle generates cached classes that highly improve the serialization
performance of the
[BazingaHateoasBundle](http://github.com/willdurand/BazingaHateoasBundle).


Installation
------------

Require [`arte/hateoas-bundle`](https://packagist.org/packages/arte/hateoas-bundle)
into your `composer.json` file:


``` json
{
    "require": {
        "arte/hateoas-bundle": "@stable"
    }
}
```

Register the bundle in `app/AppKernel.php`:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle(),
        new Arte\Bundle\HateoasBundle\ArteHateoasBundle(),
    );
}
```


Reference Configuration
-----------------------

``` yaml
# app/config/config*.yml

arte_hateoas:
    adder:
        always_generate:      false
        cache:                file
        file_cache:
            dir:                  %kernel.cache_dir%/hateoas_adders
```
