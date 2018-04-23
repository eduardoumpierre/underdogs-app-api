<?php

use Illuminate\Support\Facades\Artisan as Artisan;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();
        $this->createApplication();
        $this->artisanMigrateRefresh();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    protected function artisanMigrateRefresh()
    {
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }
}