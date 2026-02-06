<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModeController extends Controller
{
    /**
     * Show the mode selection page
     */
    public function index(Request $request)
    {
        $currentMode = config('ai.mode', 'mock');
        $sessionMode = $request->session()->get('ai_mode');
        $effectiveMode = $sessionMode ?: $currentMode;

        // Check API keys availability
        $hasGeminiKey = !empty(env('GEMINI_API_KEY'));
        $hasOpenAIKey = !empty(env('OPENAI_API_KEY'));
        $hasElevenLabsKey = !empty(env('ELEVENLABS_API_KEY'));
        $hasAnyKeys = $hasGeminiKey || $hasOpenAIKey || $hasElevenLabsKey;

        return view('mode-selector', [
            'currentMode' => $currentMode,
            'sessionMode' => $sessionMode,
            'effectiveMode' => $effectiveMode,
            'hasGeminiKey' => $hasGeminiKey,
            'hasOpenAIKey' => $hasOpenAIKey,
            'hasElevenLabsKey' => $hasElevenLabsKey,
            'hasAnyKeys' => $hasAnyKeys,
        ]);
    }

    /**
     * Switch AI mode
     */
    public function switch(Request $request)
    {
        $validated = $request->validate([
            'mode' => ['required', 'in:mock,real'],
        ]);

        // Store mode in session
        $request->session()->put('ai_mode', $validated['mode']);

        return response()->json([
            'success' => true,
            'mode' => $validated['mode'],
            'message' => "Switched to {$validated['mode']} mode!",
        ]);
    }

    /**
     * Clear mode override
     */
    public function clear(Request $request)
    {
        $request->session()->forget('ai_mode');

        return response()->json([
            'success' => true,
            'message' => 'Mode override cleared. Using default mode from .env',
        ]);
    }
}
