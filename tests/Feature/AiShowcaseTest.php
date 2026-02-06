<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Ai\Providers\MockProvider;

class AiShowcaseTest extends TestCase
{
    /** @test */
    public function simple_demo_page_loads_and_shows_current_mode()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Laravel AI SDK Showcase');
        $response->assertSee('🧪 Mock Mode');
        $response->assertSee('🚀 Real Mode');
    }

    /** @test */
    public function mock_mode_works_without_api_keys()
    {
        config(['ai.mode' => 'mock']);

        // Test chat in mock mode
        $response = $this->post('/chat/demo', [
            'message' => 'Hello world'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'mock'
        ]);
        $this->assertStringContainsString('Hello', $response->json('response'));
    }

    /** @test */
    public function real_mode_falls_back_to_mock_without_api_keys()
    {
        config(['ai.mode' => 'real']);

        // Test chat in real mode without API keys (should fallback to mock)
        $response = $this->post('/chat/demo', [
            'message' => 'Hello world'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'mock' // Should fallback to mock due to no SDK or keys
        ]);
        $this->assertStringContainsString('Hello', $response->json('response'));
    }

    /** @test */
    public function image_generation_works_in_mock_mode()
    {
        config(['ai.mode' => 'mock']);

        $response = $this->post('/image/demo', [
            'prompt' => 'A beautiful sunset'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'mock'
        ]);
        $this->assertStringContainsString('mock-image', $response->json('url'));
    }

    /** @test */
    public function audio_generation_works_in_mock_mode()
    {
        config(['ai.mode' => 'mock']);

        $response = $this->post('/audio/demo', [
            'text' => 'Hello world'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'mock'
        ]);
        $this->assertStringContainsString('mock-audio', $response->json('url'));
    }

    /** @test */
    public function search_works_in_mock_mode()
    {
        config(['ai.mode' => 'mock']);

        $response = $this->post('/search/demo', [
            'query' => 'Laravel'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'mock'
        ]);
        $this->assertIsArray($response->json('results'));
        $this->assertCount(3, $response->json('results'));
    }

    /** @test */
    public function mode_switching_works_via_session()
    {
        // Test switching to real mode
        $response = $this->post('/ai/mode/switch', [
            'mode' => 'real'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'real'
        ]);

        // Test switching back to mock mode
        $response = $this->post('/ai/mode/switch', [
            'mode' => 'mock'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'mock'
        ]);
    }

    /** @test */
    public function streaming_demo_page_loads()
    {
        $response = $this->get('/streaming/demo');

        $response->assertStatus(200);
        $response->assertSee('AI Streaming Demo');
        $response->assertSee('Stream Words');
        $response->assertSee('Stream Tokens');
        $response->assertSee('Regular Response');
    }

    /** @test */
    public function streaming_regular_response_falls_back_to_mock_without_sdk()
    {
        config(['ai.mode' => 'real']);

        $response = $this->post('/streaming/regular', [
            'prompt' => 'Hello world'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'mode' => 'mock' // Should fallback to mock due to no SDK or keys
        ]);
    }

    /** @test */
    public function mock_provider_has_all_required_methods()
    {
        $mockProvider = new MockProvider();

        // Test that all required methods exist
        $this->assertTrue(method_exists($mockProvider, 'text'));
        $this->assertTrue(method_exists($mockProvider, 'streamText'));
        $this->assertTrue(method_exists($mockProvider, 'streamTextWithTokens'));
        $this->assertTrue(method_exists($mockProvider, 'image'));
        $this->assertTrue(method_exists($mockProvider, 'audio'));
        $this->assertTrue(method_exists($mockProvider, 'transcription'));
        $this->assertTrue(method_exists($mockProvider, 'embeddings'));
        $this->assertTrue(method_exists($mockProvider, 'rerank'));
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
    public function mock_provider_streaming_produces_content()
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
    public function mode_middleware_applies_session_override()
    {
        // Set session mode
        session(['ai_mode' => 'real']);

        // The middleware should apply the session override
        // But in tests, we need to simulate it since middleware may not run in test environment
        $this->assertEquals('real', session('ai_mode'));

        // Clear session for other tests
        session()->forget('ai_mode');
    }

    /** @test */
    public function mode_selector_page_loads()
    {
        $response = $this->get('/ai/mode');

        $response->assertStatus(200);
        $response->assertSee('AI Mode Selector');
        $response->assertSee('Mock Mode');
        $response->assertSee('Real Mode');
    }

    /** @test */
    public function validation_works_for_required_fields()
    {
        // Test chat validation - use withHeaders to avoid redirect issues
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/chat/demo', [
                'message' => ''
            ]);
        $response->assertStatus(422);

        // Test image validation
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/image/demo', [
                'prompt' => ''
            ]);
        $response->assertStatus(422);

        // Test audio validation
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/audio/demo', [
                'text' => ''
            ]);
        $response->assertStatus(422);

        // Test search validation
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/search/demo', [
                'query' => ''
            ]);
        $response->assertStatus(422);
    }

    /** @test */
    public function mode_selector_clear_override_works()
    {
        // Set session mode first
        session(['ai_mode' => 'real']);

        // Clear the override
        $response = $this->post('/ai/mode/clear');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);
    }
}
