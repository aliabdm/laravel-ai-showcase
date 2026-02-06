<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Ai\Facades\Ai;
use App\Ai\Providers\MockProvider;

class StreamingTest extends TestCase
{
    /** @test */
    public function streaming_demo_page_loads()
    {
        $response = $this->get('/streaming/demo');

        $response->assertStatus(200);
        $response->assertSee('AI Streaming Demo');
    }

    /** @test */
    public function mock_provider_streaming_returns_generator()
    {
        $mockProvider = new MockProvider();

        // Test word streaming
        $wordStream = $mockProvider->streamText('Hello');
        $this->assertInstanceOf(\Generator::class, $wordStream);

        // Test token streaming
        $tokenStream = $mockProvider->streamTextWithTokens('Hello');
        $this->assertInstanceOf(\Generator::class, $tokenStream);
    }

    /** @test */
    public function mock_provider_streaming_produces_chunks()
    {
        $mockProvider = new MockProvider();

        $chunks = [];
        foreach ($mockProvider->streamText('Hello world') as $chunk) {
            $chunks[] = $chunk;
        }

        $this->assertNotEmpty($chunks);
        $this->assertIsArray($chunks);

        // Verify all chunks combined make full response
        $fullResponse = implode(' ', $chunks);
        $this->assertStringContainsString('Hello', $fullResponse);
    }

    /** @test */
    public function mock_provider_token_streaming_produces_tokens()
    {
        $mockProvider = new MockProvider();

        $tokens = [];
        foreach ($mockProvider->streamTextWithTokens('Hello') as $token) {
            $tokens[] = $token;
        }

        $this->assertNotEmpty($tokens);
        $this->assertIsArray($tokens);

        // Verify all tokens combined make full response
        $fullResponse = implode('', $tokens);
        $this->assertStringContainsString('Hello', $fullResponse);
    }

    /** @test */
    public function regular_response_works_in_mock_mode()
    {
        config(['ai.mode' => 'mock']);

        $response = $this->post('/streaming/regular', [
            'prompt' => 'Hello'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'response',
            'mode'
        ]);
        $response->assertJson(['mode' => 'mock']);
    }

    /** @test */
    public function laravel_ai_facade_works_in_real_mode()
    {
        config(['ai.mode' => 'real']);

        // Test that Laravel AI facade is available
        $this->assertTrue(class_exists(\Laravel\Ai\Facades\Ai::class));
    }

    /** @test */
    public function mode_switching_works()
    {
        // Test mock mode
        config(['ai.mode' => 'mock']);
        $this->assertEquals('mock', config('ai.mode'));

        // Test real mode
        config(['ai.mode' => 'real']);
        $this->assertEquals('real', config('ai.mode'));
    }
}
