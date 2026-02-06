<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Ai\Facades\Ai;
use App\Models\Article;
use App\Ai\Providers\MockProvider;

class SearchController extends Controller
{
    private function getMockProvider()
    {
        return new MockProvider();
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:1000',
            'min_similarity' => 'numeric|min:0|max:1',
        ]);

        try {
            if (config('ai.mode') === 'mock') {
                // Use mock search - simple text matching
                $results = Article::where(function ($q) use ($request) {
                    $queryWords = explode(' ', strtolower($request->input('query')));
                    foreach ($queryWords as $word) {
                        $q->orWhere('title', 'ILIKE', "%{$word}%")
                          ->orWhere('content', 'ILIKE', "%{$word}%");
                    }
                })
                ->limit(10)
                ->get();

                $searchType = 'text';
            } else {
                // Use Laravel AI SDK vector search
                $results = Article::query()
                    ->whereVectorSimilarTo('embedding', $request->input('query'), minSimilarity: $request->input('min_similarity', 0.5))
                    ->limit(10)
                    ->get();

                $searchType = 'vector';
            }

            return response()->json([
                'success' => true,
                'results' => $results,
                'query' => $request->input('query'),
                'count' => $results->count(),
                'search_type' => $searchType,
                'mode' => config('ai.mode'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
        ]);

        try {
            if (config('ai.mode') === 'mock') {
                // Create article with mock embedding
                $mockProvider = $this->getMockProvider();
                $embedding = json_encode($mockProvider->embeddings([$request->title . ' ' . $request->content])[0]);

                $article = Article::create([
                    'title' => $request->title,
                    'content' => $request->content,
                    'embedding' => $embedding,
                ]);
            } else {
                // Create article with real embedding (Laravel AI SDK handles this automatically)
                $article = Article::create([
                    'title' => $request->title,
                    'content' => $request->content,
                ]);
            }

            return response()->json([
                'success' => true,
                'article' => $article,
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
