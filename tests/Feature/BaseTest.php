<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_app_healthy(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_get_health(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200);
    }

}
