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

use JMS\I18nRoutingBundle\Util\RouteExtractor;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * This loader expands all routes which are eligible for i18n.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class I18nLoader
{
    const ROUTING_PREFIX = '__RG__';
    const URL_KEY = '//';
    protected $translator;
    protected $translationDomain;
    protected $locales;

    public function __construct(TranslatorInterface $translator, array $locales, $translationDomain = 'routes')
    {
        $this->translator = $translator;
        $this->locales = $locales;
        $this->translationDomain = $translationDomain;
    }

    public function load(RouteCollection $collection)
    {
        $i18nCollection = new RouteCollection();
        foreach ($collection->getResources() as $resource) {
            $i18nCollection->addResource($resource);
        }

        foreach ($collection->all() as $name => $route) {
            if ($this->shouldExcludeRoute($name, $route)) {
                $i18nCollection->add($name, $route);
                continue;
            }

            $patterns = $this->generateI18nPatterns($name, $route);
            foreach ($patterns as $fullpaths => $locales) {
                foreach ($locales as $locale) {
                    $localeRoute = clone $route;
                    list($host, $path) = $this->extractHostPath($fullpaths);
                    $localeRoute->setHost($host);
                    $localeRoute->setPath($path);
                    $localeRoute->setDefault('_locale', $locale);
                    $i18nCollection->add($name.I18nLoader::ROUTING_PREFIX.$locale, $localeRoute);
                }
            }
        }

        return $i18nCollection;
    }

    protected function shouldExcludeRoute($routeName, Route $route)
    {
        if ('_' === $routeName[0]) {
            return true;
        }

        if (false === $route->getOption('i18n') || 'false' === $route->getOption('i18n')) {
            return true;
        }

        return false;
    }

    public function generateI18nPatterns($routeName, Route $route) {
        $patterns = array();
        foreach ($route->getOption('i18n_locales') ?: array_keys($this->locales) as $locale) {
            //translation
            $i18nPattern = $this->translator->trans($routeName, array(), $this->translationDomain, $locale);
            if ($routeName === $i18nPattern) { // if no translation exists, we use the current pattern
                $i18nPattern = $route->getPath();
            }

            //add prefix+host
            if (in_array($locale, array_keys($this->locales))) {
                $prefix = $this->locales[$locale];
                $fullroute = $prefix.$i18nPattern;
            } else {
                throw new \Exception("not configuration for locale: $locale found for route: $routeName in configuration: ".print_r($this->locales, true));
            }
            $patterns[$fullroute][] = $locale;
        }

        return $patterns;
    }

    protected function extractHostPath(string $fullpath): array {
        $host = $path = null;
        if (substr($fullpath, 0, strlen(self::URL_KEY)) === self::URL_KEY) { //starts with //
            $posFirstSlash = strpos($fullpath, '/', strlen(self::URL_KEY));
            $host = substr($fullpath, strlen(self::URL_KEY), $posFirstSlash-strlen(self::URL_KEY));
            $path = substr($fullpath, $posFirstSlash);
        } else {
            $path = $fullpath;
        }

        return [$host, $path];
    }
}
