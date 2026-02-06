<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Facades\Ai;
use App\Ai\Providers\MockProvider;

class AudioController extends Controller
{
    private function getMockProvider()
    {
        return new MockProvider();
    }

    public function synthesize(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:5000',
            'voice' => 'in:male,female',
        ]);

        try {
            if (config('ai.mode') === 'mock') {
                $audio = $this->getMockProvider()->audio($request->text, [
                    'voice' => $request->voice ?? 'female',
                ]);
                $path = $audio->url;
            } else {
                $audio = Ai::audio($request->text)
                    ->{$request->voice ?? 'female'}()
                    ->generate();
                $path = $audio->storePublicly('generated-audio', 'public');
            }

            return response()->json([
                'success' => true,
                'url' => $path,
                'text' => $request->text,
                'voice' => $request->voice ?? 'female',
                'mode' => config('ai.mode'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function transcribe(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,m4a,ogg|max:10240',
            'diarize' => 'boolean',
        ]);

        try {
            if (config('ai.mode') === 'mock') {
                $result = $this->getMockProvider()->transcription($request->file('audio'));
            } else {
                $transcription = Ai::transcribe($request->file('audio'));

                if ($request->boolean('diarize')) {
                    $transcription = $transcription->diarize();
                }

                $result = $transcription->generate();
            }

            return response()->json([
                'success' => true,
                'text' => (string) $result,
                'diarize' => $request->boolean('diarize'),
                'mode' => config('ai.mode'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
