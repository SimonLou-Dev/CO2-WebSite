<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseTest extends TestCase
{


    public function test_get_health(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200);
    }

    public function test_get_csrf(): void
    {
        $response = $this->get('/api/csrf');

        $response->assertStatus(204);
    }

}
