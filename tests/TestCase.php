<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Rosendito\Taxonomies\TaxonomiesServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    /**
     * Migrations to be executed
     *
     * @var string[]
     */
    protected array $migrations = [
        '/../database/migrations/000000_create_taxonomies_table.php'
            => 'CreateTaxonomiesTable',
        '/../database/migrations/000001_create_terms_table.php'
            => 'CreateTermsTable',
        '/../database/migrations/000002_create_taggables_table.php'
            => 'CreateTaggablesTable',
    ];

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
     * Create database
     *
     * @return void
     */
    public function createDatabase(): void
    {
        $this->runTestsMigrations();
    }

    /**
     * Run migrations
     *
     * @return void
     */
    public function runTestsMigrations(): void
    {
        foreach ($this->migrations as $file => $className) {
            include_once __DIR__ . $file;

            (new $className)->up();
        }
    }
}
