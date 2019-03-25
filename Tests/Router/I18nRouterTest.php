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

namespace JMS\I18nRoutingBundle\Tests\Router;

use JMS\I18nRoutingBundle\Router\I18nLoader;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;

class I18nRouterTest extends TestCase {
    public function testGenerate() {
        $router = $this->getRouter();

        //$this->assertEquals('/welcome-on-our-website', $router->generate('welcome')); //default locale must be set

        $context = new RequestContext();
        $context->setParameter('_locale', 'en');
        $router->setContext($context);


        $this->assertEquals('//www.website.com/en/welcome', $router->generate('welcome'));
        $this->assertEquals('//www.website.de/welcome', $router->generate('welcome', array('_locale' => 'de')));

        // test homepage
        $this->assertEquals('//www.website.com/en/', $router->generate('homepage', array('_locale' => 'en')));
        $this->assertEquals('//www.website.de/', $router->generate('homepage', array('_locale' => 'de')));
    }

    public function testNonExistingLocale() {
        $router = $this->getRouter('routing_nonexistinglocale.yml');
        $this->expectException(\Exception::class);
        $router->generate('welcome'); //code throwing an exception
    }

    private function getRouter($config = 'routing.yml') {
        $container = new Container();
        $container->set('routing.loader', new YamlFileLoader(new FileLocator(__DIR__ . '/Fixture')));
        $container->set('i18n_loader', new I18nLoader(['en' => '//www.website.com/en', 'de' => '//www.website.de', 'fr' => '//www.website.fr']));

        $router = new I18nRouter($container, $config);
        $router->setI18nLoaderId('i18n_loader');

        return $router;
    }
}
