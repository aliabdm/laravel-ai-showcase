<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Facades\Ai;
use App\Ai\Providers\MockProvider;

class ImageController extends Controller
{
    private function getMockProvider()
    {
        return new MockProvider();
    }

    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
            'style' => 'in:landscape,portrait,square',
        ]);

        try {
            if (config('ai.mode') === 'mock') {
                $image = $this->getMockProvider()->image($request->prompt, [
                    'width' => $request->style === 'portrait' ? 768 : 1024,
                    'height' => $request->style === 'portrait' ? 1024 : 768,
                ]);
                $path = $image->url;
            } else {
                $image = Ai::image($request->prompt)
                    ->{$request->style ?? 'landscape'}()
                    ->generate();
                $path = $image->storePublicly('generated-images', 'public');
            }

            return response()->json([
                'success' => true,
                'url' => $path,
                'prompt' => $request->prompt,
                'mode' => config('ai.mode'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            if (config('ai.mode') === 'mock') {
                $image = $this->getMockProvider()->image($request->prompt);
                $path = $image->url;
            } else {
                $uploadedImage = $request->file('image');
                $image = Ai::edit($uploadedImage)
                    ->with($request->prompt)
                    ->generate();
                $path = $image->storePublicly('edited-images', 'public');
            }

            return response()->json([
                'success' => true,
                'url' => $path,
                'prompt' => $request->prompt,
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
