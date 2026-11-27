<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** проверяет автогенерацию БД сидером, отображение главной страницы, светофор совместимости и защиту последнего админа */
    public function test_pc_builder_seeded_portal_works(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('PC Builder');
        $response->assertSee('RTX 5090');
    }
}
