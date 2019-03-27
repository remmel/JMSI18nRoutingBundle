[![Build Status](https://travis-ci.com/remmel/JMSI18nRoutingBundle.svg?branch=master)](https://travis-ci.com/remmel/JMSI18nRoutingBundle)

# Full prefix per locale

That bundle allows you to configure host+prefix per locale.

For the following websites:
- www.website.com/fr/
- www.website.it
- www.website.be/fr-be/
- www.website.be/nl-be/

## Minimum configuration
The configuration will be:
```yml
jms_i18n_routing:
    locales:
        fr: //www.website.com
        it: //www.website.it
        fr_BE: //www.website.be/fr-be
        nl_BE: //www.website.be/nl-be
```If the route are translate

Default locale must be set:
```yml
parameters:
    locale: en
```

## Translation configuration
To translate the route named "contact" in german :

```yml
# routes.de.yml (in translation folder, next to messages.??.yml files
contact: /kontakt
```



## Optional configuration

To disable i18n feature for specific route:
`@Route("/api", options={"i18n"=false})`
or
```yml
# app/config/routing.yml
apiendpoint:
    ...
    options: { i18n: false }
```

To enable the routes only for a subset of locales:
`@Route("/about", options={"i18n_locales"={"fr", "it"}})` 

# Installation
## No packagist
That bundle has not been published in packagist, thus to be used in composer, it has to be configured in that way:
```yml
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:remmel/JMSI18nRoutingBundle.git"
    }
]
```
## Composer req
`composer req jms/i18n-routing-bundle @dev`

## Loaded in your project

Add the following line in _bundles.php_ to load the extension :
`JMS\I18nRoutingBundle\JMSI18nRoutingBundle::class => ['all' => true]`

# JMSI18nRoutingBundle vs Symfony i18n routing vs that extension
That code is based on [JMSI18nRoutingBundle](https://github.com/schmittjoh/JMSI18nRoutingBundle).  
That fork has been simplified to only keep the code related to the prefix/host per locale.  
Symfony 4.1 now handle the [translation of route and the prefix](https://symfony.com/blog/new-in-symfony-4-1-internationalized-routing).  
That bundle will be useless when that [feature will be implemented](https://github.com/symfony/symfony/issues/30617). 