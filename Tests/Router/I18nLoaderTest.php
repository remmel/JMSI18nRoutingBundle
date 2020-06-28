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
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;

class I18nLoaderTest extends TestCase {
    public function testLoad() {
        $col = new RouteCollection();
        $col->add('contact', new Route('/contact'));
        $i18nCol = $this->getLoader()->load($col);

        $this->assertEquals(2, count($i18nCol->all()));

        $de = $i18nCol->get('contact__RG__de');
        $this->assertEquals('/kontakt', $de->getPath());
        $this->assertEquals('de', $de->getDefault('_locale'));
        $this->assertEquals('website.de', $de->getHost());

        $en = $i18nCol->get('contact__RG__en');
        $this->assertEquals('/en/contact', $en->getPath());
        $this->assertEquals('en', $en->getDefault('_locale'));
        $this->assertEquals('website.com', $en->getHost());
    }


    public function testLoadNoI18n() {
        $col = new RouteCollection();
        $col->add('api_no_i18n', new Route('/api', array(), array(), array('i18n' => false)));
        $i18nCol = $this->getLoader()->load($col);

        $this->assertEquals(1, count($i18nCol->all()));
        $this->assertNull($i18nCol->get('api_no_i18n')->getDefault('_locale'));

        $api = $i18nCol->get('api_no_i18n');
        $this->assertEquals('/api', $api->getPath());
        $this->assertEmpty($api->getHost());
    }

    private function getLoader() {
        $translator = new Translator('en');
        $translator->addLoader('yml', new YamlFileLoader());
        $translator->addResource('yml', __DIR__ . '/Fixture/routes.de.yml', 'de', 'routes');

        return new I18nLoader($translator, ['en' => '//website.com/en', 'de' => '//website.de']);
    }

    public function testExtractHostPath() {
        $actual = I18nLoader::extractHostPath('//www.website.com/en/');
        $this->assertEquals(['www.website.com', '/en/'], $actual);

        $actual = I18nLoader::extractHostPath('/site');
        $this->assertEquals([null, '/site'], $actual);
    }
}
