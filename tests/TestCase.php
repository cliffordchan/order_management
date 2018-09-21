<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as LumenTestCase;

/**
 * Class TestCase
 * @package Test
 */
abstract class TestCase extends LumenTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
