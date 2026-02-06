<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ai\Providers\MockProvider;

class SimpleDemoController
{
    private function getMockProvider()
    {
        return new MockProvider();
    }

    private function hasApiKeys()
    {
        return !empty(env('GEMINI_API_KEY')) || !empty(env('OPENAI_API_KEY'));
    }

    private function useRealAi()
    {
        // Check if Laravel AI SDK is available and has API keys
        return class_exists('Laravel\Ai\Facades\Ai') && $this->hasApiKeys();
    }

    private function shouldUseMock()
    {
        // Use mock mode if:
        // 1. Config is set to 'mock'
        // 2. Laravel AI SDK is not available
        // 3. No API keys are available
        return config('ai.mode') === 'mock' || !class_exists('Laravel\Ai\Facades\Ai') || !$this->hasApiKeys();
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        try {
            if ($this->shouldUseMock()) {
                $response = $this->getMockProvider()->text($request->message);
            } else {
                // Use Laravel AI SDK if available
                if (class_exists('Laravel\Ai\Facades\Ai')) {
                    $response = \Laravel\Ai\Facades\Ai::text($request->message);
                } else {
                    throw new \Exception('Laravel AI SDK not available');
                }
            }

            return response()->json([
                'success' => true,
                'response' => (string) $response,
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'mode' => config('ai.mode')
            ], 500);
        }
    }

    public function image(Request $request)
    {
        $request->validate(['prompt' => 'required|string|max:1000']);

        try {
            if ($this->shouldUseMock()) {
                $image = $this->getMockProvider()->image($request->prompt);
                $url = $image->url;
            } else {
                // Use Laravel AI SDK if available
                if (class_exists('Laravel\Ai\Facades\Ai')) {
                    $image = \Laravel\Ai\Facades\Ai::image($request->prompt)->generate();
                    $url = $image->storePublicly('generated-images', 'public');
                } else {
                    throw new \Exception('Laravel AI SDK not available');
                }
            }

            return response()->json([
                'success' => true,
                'url' => $url,
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ], 500);
        }
    }

    public function audio(Request $request)
    {
        $request->validate(['text' => 'required|string|max:5000']);

        try {
            if ($this->shouldUseMock()) {
                $audio = $this->getMockProvider()->audio($request->text);
                $url = $audio->url;
            } else {
                // Use Laravel AI SDK if available
                if (class_exists('Laravel\Ai\Facades\Ai')) {
                    $audio = \Laravel\Ai\Facades\Ai::audio($request->text)->generate();
                    $url = $audio->storePublicly('generated-audio', 'public');
                } else {
                    throw new \Exception('Laravel AI SDK not available');
                }
            }

            return response()->json([
                'success' => true,
                'url' => $url,
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $request->validate(['query' => 'required|string|max:1000']);

        try {
            if ($this->shouldUseMock()) {
                // Mock search results
                $results = [
                    ['title' => 'Mock Result 1: ' . $request->input('query'), 'content' => 'This is a mock search result'],
                    ['title' => 'Mock Result 2: Related to ' . substr($request->input('query'), 0, 20), 'content' => 'Another mock search result'],
                    ['title' => 'Mock Result 3: ' . substr($request->input('query'), 0, 15) . '...', 'content' => 'Third mock result']
                ];
            } else {
                throw new \Exception('Laravel AI SDK not available');
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ], 500);
        }
    }
}
