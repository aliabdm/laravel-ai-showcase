<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ai\Providers\MockProvider;

class StreamingController extends Controller
{
    private function getMockProvider()
    {
        return new MockProvider();
    }

    private function hasApiKeys()
    {
        return !empty(env('GEMINI_API_KEY')) || !empty(env('OPENAI_API_KEY'));
    }

    private function shouldUseMock()
    {
        // Use mock mode if:
        // 1. Config is set to 'mock'
        // 2. Laravel AI SDK is not available
        // 3. No API keys are available
        return config('ai.mode') === 'mock' || !class_exists('Laravel\Ai\Facades\Ai') || !$this->hasApiKeys();
    }

    /**
     * Demonstrate word-based streaming
     */
    public function streamWords(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        try {
            if ($this->shouldUseMock()) {
                $provider = $this->getMockProvider();
            } else {
                $provider = null;
            }

            return response()->stream(function () use ($request, $provider) {
                if ($provider) {
                    // Use mock provider streaming
                    foreach ($provider->streamText($request->prompt) as $chunk) {
                        echo "data: " . json_encode(['chunk' => $chunk]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                } else {
                    // Use Laravel AI SDK streaming
                    if (class_exists('Laravel\Ai\Facades\Ai')) {
                        $stream = \Laravel\Ai\Facades\Ai::stream($request->prompt);
                        foreach ($stream as $chunk) {
                            echo "data: " . json_encode(['chunk' => (string) $chunk]) . "\n\n";
                            ob_flush();
                            flush();
                        }
                    } else {
                        throw new \Exception('Laravel AI SDK not available');
                    }
                }

                echo "data: " . json_encode(['done' => true]) . "\n\n";
                ob_flush();
                flush();
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Demonstrate token-based streaming
     */
    public function streamTokens(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        try {
            if ($this->shouldUseMock()) {
                $provider = $this->getMockProvider();
            } else {
                $provider = null;
            }

            return response()->stream(function () use ($request, $provider) {
                if ($provider) {
                    // Use mock provider token streaming
                    foreach ($provider->streamTextWithTokens($request->prompt) as $token) {
                        echo "data: " . json_encode(['token' => $token]) . "\n\n";
                        ob_flush();
                        flush();
                    }
                } else {
                    // Use Laravel AI SDK streaming
                    if (class_exists('Laravel\Ai\Facades\Ai')) {
                        $stream = \Laravel\Ai\Facades\Ai::stream($request->prompt);
                        foreach ($stream as $token) {
                            echo "data: " . json_encode(['token' => (string) $token]) . "\n\n";
                            ob_flush();
                            flush();
                        }
                    } else {
                        throw new \Exception('Laravel AI SDK not available');
                    }
                }

                echo "data: " . json_encode(['done' => true]) . "\n\n";
                ob_flush();
                flush();
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Demonstrate regular non-streaming response
     */
    public function regular(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        try {
            if ($this->shouldUseMock()) {
                $response = $this->getMockProvider()->text($request->prompt);
            } else {
                if (class_exists('Laravel\Ai\Facades\Ai')) {
                    $response = \Laravel\Ai\Facades\Ai::text($request->prompt);
                } else {
                    throw new \Exception('Laravel AI SDK not available');
                }
            }

            return response()->json([
                'success' => true,
                'response' => $response,
                'mode' => $this->shouldUseMock() ? 'mock' : 'real'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Demo page for streaming
     */
    public function demo()
    {
        return view('streaming-demo');
    }
}
