<?php

namespace Tests;

use Tests\Support\Post;

class TermTest extends TestCase
{
    public function testTrueIsTrue(): void
    {
        Post::create([
            'title' => 'My first blogpost',
            'body' => 'Hello world'
        ]);

        $this->assertCount(1, Post::all());
    }
}
