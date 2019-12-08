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

use App\Application;
use App\ErrorHandler;
use Segrax\OpenPolicyAgent\Middleware\Authorization as OpaAuthorization;
use Segrax\OpenPolicyAgent\Middleware\Distributor as OpaDistributor;
use Slim\Interfaces\RouteInterface;
use Slim\Psr7\Factory\StreamFactory;

/**
 * @var Application
 */
$app = App\Application::getInstance();

// Authorization Middleware
$app->add(new OpaAuthorization(
    [OpaAuthorization::OPT_POLICY => 'slim/api'],
    $app->getDependency('opa_client'),
    $app->getResponseFactory(),
    $app->getLogger()
));

// Distributor Middleware
$app->add(new OpaDistributor(
    [OpaDistributor::OPT_POLICY_PATH => $app->getAppRootPath() . '/opa'],
    $app->getResponseFactory(),
    new StreamFactory(),
    $app->getLogger()
));

// Authentication Middleware
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "algorithm" => ["HS256"],
    "attribute" => "token",
    "secure" => false,
    "secret" => getenv("APP_SECRET_KEY"),

    "logger" => $app->getLogger(),

    // Public Routes
    'ignore' => $app->getSetting('public_routes')
]));

$app->add(new \Tuupola\Middleware\CorsMiddleware([
    'origin' => 'localhost',
    "methods" => function ($request) {
        /** @var RouteInterface $result */
        $result = $request->getAttribute('route');
        return $result->getMethods();
    },
    'headers.allow'     =>
        ['Accept', 'Accept-Language', 'Authorization',
        'Content-Type','DNT','Keep-Alive','User-Agent',
        'X-Requested-With','If-Modified-Since','Cache-Control','Origin'
        ],
    'headers.expose' => ['Etag']
    ]));

// Routing
$app->addRoutingMiddleware();

// Error handling
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(new ErrorHandler());
