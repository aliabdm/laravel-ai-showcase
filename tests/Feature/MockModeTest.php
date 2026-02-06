<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Ai\Facades\Ai;
use App\Ai\Providers\MockProvider;

class MockModeTest extends TestCase
{
    /** @test */
    public function it_works_in_mock_mode_without_api_keys()
    {
        // Test that the application loads in mock mode
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function mock_provider_is_registered()
    {
        // Test that mock provider can be instantiated
        $mockProvider = new MockProvider();
        $this->assertNotNull($mockProvider);
        $this->assertIsObject($mockProvider);
    }

    /** @test */
    public function mock_provider_has_required_methods()
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
    public function mock_provider_text_returns_string()
    {
        $mockProvider = new MockProvider();
        $response = $mockProvider->text('Hello');

        $this->assertIsString($response);
        $this->assertStringContainsString('Hello', $response);
    }

    /** @test */
    public function mock_provider_streaming_returns_generator()
    {
        $mockProvider = new MockProvider();

        $stream = $mockProvider->streamText('Hello world');
        $this->assertInstanceOf(\Generator::class, $stream);

        // Test that generator yields values
        $chunks = [];
        foreach ($stream as $chunk) {
            $chunks[] = $chunk;
        }

        $this->assertNotEmpty($chunks);
    }

    /** @test */
    public function configuration_switches_between_modes()
    {
        // Test that configuration can be switched
        config(['ai.mode' => 'real']);
        $this->assertEquals('real', config('ai.mode'));

        config(['ai.mode' => 'mock']);
        $this->assertEquals('mock', config('ai.mode'));
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
}
