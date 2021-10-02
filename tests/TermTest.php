<?php

namespace Tests;

use Rosendito\Taxonomies\Taxonomy;
use Tests\Support\Post;

class TermTest extends TestCase
{
    /**
     * Taxonomies names for testing purpose
     *
     * @var string[]
     */
    protected array $taxonomies = [
        'Category',
        'Tag'
    ];

    /**
     * Test create taxonomies
     *
     * @return void
     */
    public function testCreateTaxonomies(): void
    {
        foreach ($this->taxonomies as $taxonomy) {
            Taxonomy::create([
                'name' => $taxonomy
            ]);
        }

        $this->assertCount(2, Taxonomy::all());
    }
}
