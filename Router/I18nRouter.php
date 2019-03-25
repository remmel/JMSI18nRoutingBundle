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
    private $i18nLoaderId;
    private $container;
    private $defaultLocale;

    /**
     * Constructor.
     *
     * The only purpose of this is to make the container available in the sub-class
     * since it is declared private in the parent class.
     *
     * The parameters are not listed explicitly here because they are different for
     * Symfony 2.0 and 2.1. If we did list them, it would make this class incompatible
     * with one of both versions.
     */
    public function __construct() {
        call_user_func_array(array(Router::class, '__construct'), func_get_args());
        $this->container = func_get_arg(0);
    }

    public function setI18nLoaderId($id) {
        $this->i18nLoaderId = $id;
    }

    public function setDefaultLocale($locale) {
        $this->defaultLocale = $locale;
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
            $locale = $this->defaultLocale;
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
        return $this->container->get($this->i18nLoaderId)->load($collection);
    }
}
