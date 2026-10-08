<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test root route returns the portal page successfully.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test API docs endpoint loads successfully.
     */
    public function test_api_docs_endpoint_is_accessible(): void
    {
        $response = $this->get('/api/docs');

        $response->assertStatus(200);
    }
}
