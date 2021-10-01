<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Rosendito\Taxonomies\TaxonomiesServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    /**
     * @param [type] $app
     * @return void
     */
    protected function getPackageProviders($app): array
    {
        return [
            TaxonomiesServiceProvider::class
        ];
    }

    /**
     * SetUp test environment
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->createDatabase();
    }

    /**
     * Load migrations for create database
     *
     * @return void
     */
    public function createDatabase(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Support/migrations');
    }
}
