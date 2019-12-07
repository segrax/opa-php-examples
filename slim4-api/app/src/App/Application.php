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

use DI\Container;
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use RuntimeException;
use Slim\App;
use Slim\Psr7\Factory\ResponseFactory;

class Application extends App
{
    use Singleton;

    /**
     * @var string
     */
    protected $pathAppRoot;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Constructor
     *
     * @Supp
     */
    protected function __construct(?string $pAppPath = null)
    {
        if ($pAppPath === null) {
            $pAppPath = getcwd() . "/../";
        }

        $path = realpath($pAppPath);
        if (!is_string($path)) {
            throw new RuntimeException("AppPath: $pAppPath is invalid");
        }
        $this->pathAppRoot = $path;
    }

    /**
     * Prepare a container
     */
    private function prepareContainer(): Container
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(false);
        $builder->useAnnotations(false);
        return $builder->build();
    }

    /**
     * Prepare the application
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function prepare()
    {
        $env = Dotenv::create($this->pathAppRoot);
        $env->load();

        $this->settings = $this->settingsLoad();
        $container = $this->prepareContainer();
        $container->set('settings', $this->settings);

        // Construct the slim object
        parent::__construct(new ResponseFactory(), $container);

        $this->loadFiles("dependencies");
        $this->loadFiles("routes");
        $this->loadFiles("middleware");
    }

    /**
     * Load settings
     */
    protected function settingsLoad(): array
    {
        $path = $this->pathAppRoot . "/src/configuration/settings.php";
        if (!file_exists($path)) {
            throw new RuntimeException("application: Settings not found: $path");
        }
        return require $path;
    }

    /**
     * Load configuration from pPath
     */
    protected function loadFiles(string $pPath): void
    {
        $files = glob($this->pathAppRoot . "/src/configuration/$pPath/*.php");
        if ($files === false) {
            throw new RuntimeException("no configuration files found");
        }

        foreach ($files as $filename) {
            require $filename;
        }
    }

    /**
     * Get the log mechanism
     */
    public function getLogger()
    {
        return $this->getDependency("logger");
    }

    /**
     * Get all settings
     */
    public function getSettings(): array
    {
        return $this->getContainer()->get("settings");
        ;
    }

    /**
     * Get a specific setting
     */
    public function getSetting(string $pName)
    {
        return $this->getSettings()[$pName];
    }

    /**
     * Get a dependency
     */
    public function getDependency(string $pName)
    {
        return $this->getContainer()->get($pName);
    }

    /**
     * Get the root path of the app
     */
    public function getAppRootPath(): string
    {
        return $this->pathAppRoot;
    }

    /**
     * Running in debug mode
     */
    public function isDebug(): bool
    {
        return true;
    }

    /**
     * Running in production mode
     */
    public function isProduction(): bool
    {
        return !$this->isDebug();
    }
}
