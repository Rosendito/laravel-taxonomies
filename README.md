# Taxonomies and terms for Laravel
> Basic package to categorize models by taxonomies and terms similar to wordpress

[![Tests](https://github.com/Rosendito/laravel-taxonomies/actions/workflows/run-tests.yml/badge.svg?branch=master&event=push)](https://github.com/Rosendito/laravel-taxonomies/actions/workflows/run-tests.yml)

## Features

* Polymorphic, you can categorize all your models
* Support for virtual many-to-one relationships to make your models only have one term per taxonomy
* Terms nestable with parent-child relationship

## Installation

You can install the package via composer:
```bash
composer require rosendito/laravel-taxonomies
```

Publish and run the migrations:
```bash
php artisan vendor:publish --provider="Rosendito\Taxonomies\TaxonomiesServiceProvider" --tag="migrations"
php artisan migrate
```

## Basic usage
Make your model taggable adding the `HasTaxonomies` trait:
```php

use Rosendito\Taxonomies\HasTaxonomies;

class Post extends model
{
    use HasTaxonomies;

    ...
}
```

Create some taxonomies and terms:
```php
// database\seeders\CategoriesSeeder.php

use Rosendito\Taxonomies\Taxonomy;
use Rosendito\Taxonomies\Term;

$taxonomy = Taxonomy::create([
    'name' => 'Category'
]);

$term = Term::create([
    'taxonomy_id' => $taxonomy->id,
    'name' => 'Packages'
]);
```

Add terms to model:
```php
$post = $this->addTerm('Category', 'Packages');

// You can pass the taxonomy and term as name, id or model
$term1 = Term::first();
$term2 = Term::latest()->first();

$post->addTerms('Category', [$term1->id, $term2]);
```

Query your models by term:
```php
$posts = Post::hasTerm('Category', 'Packages')->get();
```

## Resources
- https://laravelpackage.com/ guide for create a laravel package
- https://radicalloop.medium.com/test-your-laravel-package-locally-in-your-laravel-project-dec120686695 guide for test your laravel package locally
- https://github.com/myerscode/laravel-taxonomies a similar reference package (I )
