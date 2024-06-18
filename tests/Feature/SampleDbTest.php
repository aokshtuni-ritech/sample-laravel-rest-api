<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SampleDbTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_counts(): void
    {
        $this->seed();

        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('integrations', 1);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@test.local'
        ]);
        $this->assertDatabaseHas('integrations', [
            'name' => 'TrackTik',
        ]);
        $this->assertDatabaseMissing('integrations', [
            'name' => 'Abcd',
        ]);
    }
}
