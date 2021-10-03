# Taxonomies and terms for Larave;
[![Tests](https://github.com/Rosendito/laravel-taxonomies/actions/workflows/run-tests.yml/badge.svg?branch=master&event=push)](https://github.com/Rosendito/laravel-taxonomies/actions/workflows/run-tests.yml)

## Installation
You can install the package via composer:

```bash
composer require rosendito/laravel-taxonomies
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Rosendito\Taxonomies\TaxonomiesServiceProvider" --tag="migrations"
php artisan migrate
```

## Resources
- https://laravelpackage.com/ guide for create a laravel package
- https://radicalloop.medium.com/test-your-laravel-package-locally-in-your-laravel-project-dec120686695 guide for test your laravel package locally
- https://github.com/myerscode/laravel-taxonomies a reference similar package
