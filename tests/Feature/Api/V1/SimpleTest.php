<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    protected function seedTestData(): void
    {
        // Skip heavy seeding
    }

    public function test_simple(): void
    {
        $this->assertTrue(true);
    }
}
