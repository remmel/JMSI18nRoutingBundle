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

namespace JMS\I18nRoutingBundle\Tests\Functional;

class ControllerTest extends BaseTestCase {
    public function testDefaultLocaleIsSetCorrectly() {
        $client = $this->createClient(['config' => 'host_per_locale.yml'], ['HTTP_HOST' => 'www.website.de']);
        $client->insulate();


        $routes = $client->getContainer()->get('router')->getRouteCollection()->all();
        self::assertArrayHasKey('homepage__RG__de', $routes);

        $crawler = $client->request('GET', '/');

        $this->assertEquals(1, count($locale = $crawler->filter('#locale')), substr($client->getResponse(), 0, 2000));
        $this->assertEquals('de', $locale->text());
    }

    public function testDisabledI18nRoute() {
        $client = $this->createClient(array('config' => 'host_per_locale.yml'), array(
            'HTTP_HOST' => 'localhost',
        ));
        $client->insulate();
        $crawler = $client->request('GET', '/api');
        $this->assertTrue($client->getResponse()->isSuccessful(), 'response successful');
    }
}