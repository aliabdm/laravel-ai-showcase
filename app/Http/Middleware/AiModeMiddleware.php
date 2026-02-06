<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AiModeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $mode = $request->session()->get('ai_mode');

        if (! is_string($mode) || ! in_array($mode, ['mock', 'real'], true)) {
            return $next($request);
        }

        config(['ai.mode' => $mode]);

        if ($mode === 'mock') {
            config([
                'ai.default' => 'mock',
                'ai.default_for_images' => 'mock',
                'ai.default_for_audio' => 'mock',
                'ai.default_for_transcription' => 'mock',
                'ai.default_for_embeddings' => 'mock',
                'ai.default_for_reranking' => 'mock',
            ]);
        } else {
            config([
                'ai.default' => 'gemini',
                'ai.default_for_images' => 'gemini',
                'ai.default_for_audio' => 'eleven',
                'ai.default_for_transcription' => 'openai',
                'ai.default_for_embeddings' => 'gemini',
                'ai.default_for_reranking' => 'cohere',
            ]);
        }

        return $next($request);
    }
}
