<?php

/*
Copyright (c) 2019 Robert Crossfield
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
/**
 * @see       https://github.com/segrax/opa-php-examples
 * @license   https://www.opensource.org/licenses/mit-license.php
 */

use DI\Container;
use Segrax\OpenPolicyAgent\Client;

$app = App\Application::getInstance();

/** @var Container */
$container = $app->getContainer();

$container->set('opa_client', function () use ($container): Client {
    return new Client(
        [Client::OPT_AGENT_URL => getenv('OPA_BASEURL'),
        Client::OPT_AUTH_TOKEN => 'MyToken'],
        $container->get("logger")
    );
});

// monolog
$container->set('logger', function () use ($app): \Monolog\Logger {
    $settings = $app->getSetting('logger');
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
});
