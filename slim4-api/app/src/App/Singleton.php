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

trait Singleton
{

    /**
     * @var static A reference to our object
     */
    private static $instance = null;

    /**
     * Prevent creation of the class from outside
     */
    final private function __construct()
    {
        $this->init();
    }

    /**
     * Prevent cloning
     */
    final private function __clone()
    {
    }

    /**
     * block unserializing
     */
    final private function __wakeup()
    {
    }

    /**
      * Called by __construct (for overloading)
      */
    protected function init()
    {
    }

    /**
     * Get the existing object, or create one
     *
     * @return static
     */
    public static function getInstance($pParameter = null)
    {

        if (self::$instance === null) {
            self::$instance = new static($pParameter);
        }

        return self::$instance;
    }
}
