# JMSI18nRoutingBundle

i18n Routing Bundle for the Symfony Framework

Documentation: 
[Resources/doc](http://jmsyst.com/bundles/JMSI18nRoutingBundle)
    

Code License:
[Resources/meta/LICENSE](https://github.com/schmittjoh/JMSI18nRoutingBundle/blob/master/Resources/meta/LICENSE)


Documentation License:
[Resources/doc/LICENSE](https://github.com/schmittjoh/JMSI18nRoutingBundle/blob/master/Resources/doc/LICENSE)


In that fork, a new strategy has been added to be able to define multiple host with multiple locales, eg : 
- website.com/fr/
- website.it
- website.be/fr-be/
- website.be/nl-be/

```
jms_i18n_routing:
    default_locale: 'fr'
    locales: ['fr','it', 'fr-be', 'nl-be']
    strategy: prefix_per_locale
    use_cookie: false
    prefix: ['fr', 'fr-be', 'nl-be']
    hosts:
        fr: website.com
        it: website.it
        fr-be: website.be
        nl-be: website.be
    redirect_to_host: true
```


## Note
Currently the jms plugin doesn't set the host when generating the route `I18nLoader`. It matches the host later in `I18nRouter.matchI18n`.
It should be also better to simplier the configuration to have something like :
Code inspired from https://github.com/MichaelKubovic/JMSI18nRoutingBundle  
Diff: https://github.com/schmittjoh/JMSI18nRoutingBundle/compare/2.0...MichaelKubovic:master

```
jms_i18n_routing:
    locales:
        fr:
            prefix: true
            host: website.com
        it:
            prefix: false
            host: website.it
        fr-be:
            prefix: true
            host: website.be
        nl-be:
            prefix: true
            host: website.be
```

