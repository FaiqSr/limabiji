<?php

namespace Tests\Feature\Store;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_recommend_coffee_matches(): void
    {
        $response = $this->postJson('/store/quiz/recommend', [
            'brew' => 'v60',
            'flavor' => 'fruity',
            'roast' => 'light',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'recommendations' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'category',
                    'origin',
                    'roast_level',
                    'sca_score',
                    'tasting_notes',
                    'image',
                    'formatted_price',
                    'match_percentage',
                    'url',
                ],
            ],
        ]);
        $this->assertNotEmpty($response->json('recommendations'));
    }
}
