[![Build Status](https://travis-ci.com/remmel/i18n-routing-bundle.svg?branch=master)](https://travis-ci.com/remmel/i18n-routing-bundle)

# Full prefix per locale

That bundle allows you to configure host+prefix per locale.

For the following websites:
- www.website.com
- www.website.de
- www.website.be/fr/
- www.website.be/nl/

## Minimum configuration
The configuration will be:
```yml
# jms_i18n_routing.yaml
jms_i18n_routing:
    locales:
        en: //www.website.com
        de: //www.website.de
        fr_BE: //www.website.be/fr
        nl_BE: //www.website.be/nl
```

Default locale must be set in
```yml
# services.yaml
parameters:
    locale: "%kernel.default_locale%"
```

## Translation configuration
To translate the route named "contact_page" in german :

```yml
# translations/routes.de.yml
contact_page: /kontakt
```

Thus the route named _contact_page_ will be used when calling:
 - www.website.com/en/contact
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
`composer req remmel/i18n-routing-bundle`

## Loaded in your project

Add the following line in _bundles.php_ to load the extension :
`JMS\I18nRoutingBundle\JMSI18nRoutingBundle::class => ['all' => true]`

# Symfony demo app
[Full Symfony application example](https://github.com/remmel/i18n-routing-demo)

# JMSI18nRoutingBundle vs Symfony i18n routing vs that extension
That code is based on [JMSI18nRoutingBundle](https://github.com/schmittjoh/JMSI18nRoutingBundle).  
That fork has been simplified to only keep the code related to the prefix/host per locale.  
Symfony 4.1 now handle the [translation of route and the prefix](https://symfony.com/blog/new-in-symfony-4-1-internationalized-routing).  
That bundle will be useless when that [feature will be implemented](https://github.com/symfony/symfony/issues/30617) and translations externalized in routes.??.yml files. 