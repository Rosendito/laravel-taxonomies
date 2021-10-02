<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Rosendito\Taxonomies\TaxonomiesServiceProvider;

class TestCase extends Orchestra
{
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
        '/Support/migrations/0000_00_00_000001_create_posts_table.php'
            => 'CreatePostsTable'
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
     * Teastdown test environment
     *
     * @return void
     */
    public function tearDown(): void
    {
        $this->destroyDatabase();
        parent::tearDown();
    }

    /**
     * Create database
     *
     * @return void
     */
    public function createDatabase(): void
    {
        foreach ($this->migrations as $file => $className) {
            include_once __DIR__ . $file;

            (new $className)->up();
        }
    }

    /**
     * Destroy database
     *
     * @return void
     */
    public function destroyDatabase(): void
    {
        $this->artisan('db:wipe');
    }
}
