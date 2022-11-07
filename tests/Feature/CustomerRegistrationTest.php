<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\Mock;
use Tests\TestCase;

class CustomerRegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        Mock::DB();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
