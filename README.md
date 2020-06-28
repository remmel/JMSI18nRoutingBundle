[![Build Status](https://travis-ci.com/remmel/i18n-routing-bundle.svg?branch=master)](https://travis-ci.com/remmel/i18n-routing-bundle)

# Full prefix per locale

That bundle allows you to configure host+prefix per locale.

For the following websites:
- www.website.com (english)
- www.website.de (german)
- www.website.be/fr/ (french of Belgium)
- www.website.be/nl/ (dutch of Belgium)

## Minimum configuration
The configuration will be:
```yml
# config/packages/jms_i18n_routing.yaml
jms_i18n_routing:
    locales:
        en: //www.website.com
        de: //www.website.de
        fr_BE: //www.website.be/fr
        nl_BE: //www.website.be/nl
```

## Translation configuration
To translate the route named "contact_page" in german :

```yml
# translations/routes.de.yml
contact_page: /kontakt
```

Thus the route named _contact_page_ will be used when calling:
 - www.website.com/contact
 - www.website.de/kontakt


## Optional configuration

To disable i18n feature for specific route:
`@Route("/api", options={"i18n"=false})`
or
```yml
# app/config/routing.yaml
apiendpoint:
    ...
    options: { i18n: false }
```

To enable the routes only for a subset of locales:
`@Route("/about", options={"i18n_locales"={"en", "de"}})`
Thoses locales must be configured in jms_i18n_routing.locales

# Installation

## Download the Bundle
`composer req remmel/i18n-routing-bundle`

## Enable the Bundle - For applications that don't use Symfony Flex : 

```php
// config/bundles.php

return [
    // ...
    JMS\I18nRoutingBundle\JMSI18nRoutingBundle::class => ['all' => true],
];
```

## Create configuration
Simple configuration with 2 "folders" :
```yml
# config/packages/jms_i18n_routing.yaml
jms_i18n_routing:
    locales:
        en: /eng
        de: /deu
```

When updating that config, it might be needed to clear cache: `bin/console cache:clear`  
Check that configuration is fine running `bin/console debug:router`  

# Symfony demo app
[Full Symfony application example](https://github.com/remmel/i18n-routing-demo)

# JMSI18nRoutingBundle vs Symfony i18n routing vs that extension
## JMSI18nRoutingBundle
That code is based on [JMSI18nRoutingBundle](https://github.com/schmittjoh/JMSI18nRoutingBundle).  
That fork has been simplified to only keep the code related to the prefix/host per locale.

## Symfony
Symfony 4.1 now handle the [translation of route and the prefix](https://symfony.com/blog/new-in-symfony-4-1-internationalized-routing).
Symfony 5.1 now handle the [different host per locale](https://symfony.com/blog/new-in-symfony-5-1-different-hosts-per-locale).
Thus, that extension is usefull if you want to externalize route translations (eg in routes.xx.yml).      
That bundle will be useless when that [feature will be implemented](https://github.com/symfony/symfony/issues/30617) and translations externalized in routes.??.yml files. 