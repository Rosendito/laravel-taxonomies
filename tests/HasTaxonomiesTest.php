<?php

namespace Tests;

use Illuminate\Database\Eloquent\Collection;
use Rosendito\Taxonomies\Taxonomy;
use Rosendito\Taxonomies\Term;
use Tests\Support\Post;

class HasTaxonomiesTest extends TestCase
{
    /**
     * Category taxonomy
     *
     * @var Taxonomy
     */
    protected Taxonomy $category;

    /**
     * Tag taxonomy
     *
     * @var Taxonomy
     */
    protected Taxonomy $tag;

    /**
     * All posts
     *
     * @var Collection
     */
    protected Collection $posts;

    /**
     * Post test subject
     *
     * @var Post
     */
    protected Post $subjectPost;

    /**
     * SetUp test environment
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seedDatabase();
    }

    /**
     * Seed database with taxonomies and terms
     *
     * @return void
     */
    protected function seedDatabase(): void
    {
        $taxonomies = [
            'Category' => [
                'Foo',
                'Bar'
            ],
            'Tag' => [
                'Baz',
                'Qux',
                'Quux',
                'Corge'
            ]
        ];
        $posts = [
            'My first article' => 'Hello world!',
            'How to create a functional, attractive and elegant code' =>
                'Indent correctly, name well your vars/functions and make tests!',
            'How to be attractive to girls'
                => "Become a programmer, write good code, earn money and thus fill that void that
                    you plan to fill with a girl. (It's a joke, go out and exercise)"
        ];

        foreach ($taxonomies as $taxonomyName => $terms) {
            $taxonomy = Taxonomy::create([
                'name' => $taxonomyName
            ]);

            foreach ($terms as $term) {
                Term::create([
                    'taxonomy_id' => $taxonomy->id,
                    'name' => $term
                ]);
            }
        }

        foreach ($posts as $title => $description) {
            Post::create([
                'title' => $title,
                'body' => $description
            ]);
        }

        $this->category = Taxonomy::with('terms')->firstWhere('name', 'Category');
        $this->tag = Taxonomy::with('terms')->firstWhere('name', 'Tag');
        $this->posts = Post::all();
        $this->subjectPost = $this->posts->first();
    }

    /**
     * Test add terms
     *
     * @return void
     */
    public function test_add_terms(): void
    {
        // Check if two terms are attached
        $this->assertCount(
            4,
            $this->subjectPost->addTerms($this->tag, $this->tag->terms)['attached'],
            'The terms were not attached with "addTerms" method'
        );

        // Check if the first category term are attached
        $this->assertEquals(
            $this->category->terms[0]->id,
            $this->subjectPost->addTerm(
                $this->category, $this->category->terms[0]
            )['attached'][0],
            'The term were not attached with "addTerm" method'
        );

        // Check if the second category term are attached detaching older term
        $this->assertEquals(
            $this->category->terms[1]->id,
            $this->subjectPost->addTermAndDetachingOlder(
                $this->category, $this->category->terms[1]
            )['attached'][0],
            'The term were not attached with "addTermAndDetachingOlder" method'
        );
    }

    /**
     * Test remove terms
     *
     * @return void
     */
    public function test_remove_terms(): void
    {
        $this->subjectPost->addTerms($this->tag, $this->tag->terms);
        $this->subjectPost->addTerm(
            $this->category,
            $this->category->terms->first()
        );

        // Check if the last two tag terms are detached
        $this->assertEquals(
            2,
            $this->subjectPost->removeTerms(
                $this->tag,
                $this->tag->terms->take(-2)
            ),
            'The terms were not detached with "removeTerms" method'
        );

        // Check if the all terms are detached
        $this->assertEquals(
            2,
            $this->subjectPost->removeTerms(
                $this->tag,
                null,
                true
            ),
            'The terms were not detached with "removeTerms" method'
        );
        $this->assertCount(
            0,
            $this->subjectPost->getTerms($this->tag),
            'The all terms were not detached with "removeTerms" method passing all parameter as true'
        );

        // Check if the first category term are detached
        $this->assertEquals(
            1,
            $this->subjectPost->removeTerm(
                $this->category,
                $this->category->terms->first()
            ),
            'The first category term were not detached with "removeTerm" method'
        );
    }
}
