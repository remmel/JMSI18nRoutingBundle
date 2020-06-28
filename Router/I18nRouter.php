<?php

/*
 * Copyright 2012 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\I18nRoutingBundle\Router;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * I18n Router implementation.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class I18nRouter extends Router {

    /** @var I18nLoader */
    private $i18nLoader;

    /** @var string */
    private $defaultLocaleI18n;

    public function setI18nLoader(I18nLoader $i18nLoader) {
        $this->i18nLoader = $i18nLoader;
    }

    public function setDefaultLocale($locale) {
        $this->defaultLocaleI18n = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = array(), $referenceType = self::ABSOLUTE_PATH) {
        // determine the most suitable locale to use for route generation
        $currentLocale = $this->context->getParameter('_locale');
        if (isset($parameters['_locale'])) {
            $locale = $parameters['_locale'];
        } else if ($currentLocale) {
            $locale = $currentLocale;
        } else {
            // no custom locale in the context, use project default locale
            $locale = $this->defaultLocaleI18n;
        }

        $generator = $this->getGenerator();
        try {
            return $generator->generate($name . I18nLoader::ROUTING_PREFIX . $locale, $parameters, $referenceType);
        } catch (RouteNotFoundException $ex) {
        }
        return $generator->generate($name, $parameters, $referenceType);

    }

    public function getRouteCollection() {
        $collection = parent::getRouteCollection();
        return $this->i18nLoader->load($collection);
    }
}
