<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthRoutesTest extends TestCase
{
    public function test_register_route_is_available(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
}
