<?php

namespace Rosendito\Taxonomies;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class TaxonomiesServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $timestamp = date('Y_m_d_', time());
            $this->publishes([
                __DIR__ . '/../database/migrations/0_create_taxonomies_table.php' =>
                    database_path('migrations/' . $timestamp . '000000_create_taxonomies_table.php'),
                __DIR__ . '/../database/migrations/1_create_terms_table.php' =>
                    database_path('migrations/' . $timestamp . '100000_create_terms_table.php'),
                __DIR__ . '/../database/migrations/2_create_taggables_table.php' =>
                    database_path('migrations/' . $timestamp . '200000_create_taggables_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            //
        ];
    }
}
