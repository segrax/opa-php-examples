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

namespace App\Controller;

use App\Application;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Base Controller
 */
class BaseController
{
    /** @var LoggerInterface */
    protected $logger;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->logger = $this->getApplication()->getLogger();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function getApplication(): Application
    {
        return Application::getInstance();
    }

    /**
     * 200 Result with a JSON payload
     */
    protected function responseJson(ResponseInterface $pResponse, array $pData)
    {
        $pResponse->getBody()->write(json_encode($pData, JSON_THROW_ON_ERROR));
        return $pResponse
                    ->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
    }
}
