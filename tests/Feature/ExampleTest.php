<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function testMainPage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
