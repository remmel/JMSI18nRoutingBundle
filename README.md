# JMSI18nRoutingBundle

i18n Routing Bundle for the Symfony Framework

In that fork, the code of previous strategies has been removed; [(native in Symfony)](https://symfony.com/blog/new-in-symfony-4-1-internationalized-routing)
to keep/improve the host/prefix per locale. That feature might be [integrated in Symfony](https://github.com/symfony/symfony/issues/30617). 

For the following websites :
- website.com/fr/
- website.it
- website.be/fr-be/
- website.be/nl-be/

Configuration:
```yml
jms_i18n_routing:
    locales:
        fr: website.com
        it: website.it
        fr_BE: website.be/fr-be
        nl_BE: website.be/nl-be
```

A default locale must be set
```yml
parameters:
    locale: en
```yml

Each route will be "duplicated" for each locale listed

To disable i18n feature for specific route:
`@Route("/api", options={"i18n"=false})`
or
```yml
# app/config/routing.yml
apiendpoint:
    ...
    options: { i18n: false }
```

To generate the route for a subset of locales:
`@Route("/about", options={"i18n_locales"={"fr", "it"}})`

Translating the url :  [from Symfony 4.1]((https://symfony.com/blog/new-in-symfony-4-1-internationalized-routing))

# Installation
As it's not existing in packagist, the git repository has to be configured :
```yml
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:remmel/JMSI18nRoutingBundle.git"
    }
]
```

`composer req jms/i18n-routing-bundle @dev`

It should add in bundles.php :
`JMS\I18nRoutingBundle\JMSI18nRoutingBundle::class => ['all' => true]`