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

namespace App;

use Throwable;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\ErrorHandlerInterface;

/**
 * Catch any errors which occur
 */
class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * @var ?LoggerInterface
     */
    private $logger;

    /**
     * Class Setup
     */
    public function __construct(LoggerInterface $pLogger = null)
    {
        $this->logger = $pLogger;
    }

    /**
     * Catch the error
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameters)
     */
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $errorId = base64_encode(random_bytes(10));

        $payload = ['errors' => [[  'id' => $errorId,
                                    'code' => $exception->getCode(),
                                    'message' => $exception->getMessage(),
                                    'file' => $exception->getFile(),
                                    'line' => $exception->getLine(),
                                    'trace' => $exception->getTraceAsString()]]];

        if (Application::getInstance()->isProduction()) {
            $payload = ['errors' => [[  'id' => $errorId,
                                        'message' => 'Internal Error']]];
        }

        $response = Application::getInstance()->getResponseFactory()->createResponse(500);
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)
        );
        return $response->withHeader('Content-Type', 'application/json');
    }
}
