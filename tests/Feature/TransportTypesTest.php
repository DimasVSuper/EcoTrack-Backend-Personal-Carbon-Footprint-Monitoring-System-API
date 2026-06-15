<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransportTypesTest extends TestCase
{
    use RefreshDatabase;

    public function test_transport_types_endpoint_is_public(): void
    {
        $response = $this->getJson('/api/transport-types');

        $response->assertOk();
        $response->assertJsonStructure([
            'data',
        ]);
        $response->assertJsonCount(5, 'data');
    }

    public function test_transport_logs_still_require_authentication(): void
    {
        $response = $this->getJson('/api/transport-logs');

        $response->assertUnauthorized();
    }

    public function test_electricity_logs_still_require_authentication(): void
    {
        $response = $this->getJson('/api/electricity-logs');

        $response->assertUnauthorized();
    }

    public function test_user_profile_still_require_authentication(): void
    {
        $response = $this->getJson('/api/user/profile');
        $response->assertUnauthorized();
    }

    public function test_user_logout_still_require_authentication(): void
    {
        $response = $this->postJson('/api/logout');
        $response->assertUnauthorized();
    }
}
