<?php

namespace Tests;

use Rosendito\Taxonomies\Taxonomy;
use Rosendito\Taxonomies\Term;
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
     * SetUp test environment
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        foreach ($this->taxonomies as $name) {
            Taxonomy::create([
                'name' => $name
            ]);
        }
    }

    /**
     * Test create terms
     *
     * @return void
     */
    public function test_create_terms(): void
    {
        $taxonomy = Taxonomy::where('name', $this->taxonomies[0])->first();

        $term1 = Term::create([
            'taxonomy_id' => $taxonomy->id,
            'name' => 'Foo'
        ]);

        $term2 = Term::create([
            'taxonomy_id' => $taxonomy->id,
            'parent_id' => $term1->id,
            'name' => 'Bar'
        ]);

        // Check if the two terms are created
        $this->assertCount(2, Term::all());
        // Check if the first term has the correct taxonomy
        $this->assertEquals($taxonomy->id, $term1->taxonomy->id);
        // Check if the second term has term1 as parent
        $this->assertEquals($term2->parent->id, $term1->id);
        // Check if the first term has term2 as child
        $this->assertTrue($term1->childs->contains($term2));
    }

    /**
     * Test terms scopes
     *
     * @return void
     */
    public function test_terms_scopes(): void
    {
        $taxonomy1 = Taxonomy::first();
        $taxonomy2 = Taxonomy::latest()->first();

        $term1 = Term::create([
            'taxonomy_id' => $taxonomy1->id,
            'name' => 'Foo'
        ]);

        $term2 = Term::create([
            'taxonomy_id' => $taxonomy2->id,
            'name' => 'Bar'
        ]);

        $term3 = Term::create([
            'taxonomy_id' => $taxonomy1->id,
            'parent_id' => $term2->id,
            'name' => 'Baz'
        ]);

        // Check if scope "byTaxonomy" works correctly
        $this->assertEquals(
            $term1->id,
            Term::byTaxonomy($taxonomy1->id)->first()->id
        );
        // Check if scope "byParent" works correctly
        $this->assertEquals(
            $term3->id,
            Term::byParent($term2->name)->first()->id,
        );
    }
}
