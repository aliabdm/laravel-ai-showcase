<?php

namespace App\Ai\Providers;

use Stringable;

class MockProvider
{
    public function text(string $prompt, array $options = []): Stringable|string
    {
        // Deterministic mock responses based on prompt content
        $responses = [
            'Hello' => 'Hello! I\'m a mock AI assistant. How can I help you today?',
            'How are you' => 'I\'m doing great! Thanks for asking. I\'m ready to assist with any questions.',
            'What is Laravel' => 'Laravel is a powerful PHP web application framework with expressive, elegant syntax. It provides tools for routing, authentication, caching, and more.',
            'Code review' => 'This code looks good overall. Consider adding more comments and error handling. I\'d rate it 7/10.',
            'Weather' => 'The weather today is sunny with a temperature of 22°C. Perfect weather for coding!',
        ];

        foreach ($responses as $key => $response) {
            if (stripos($prompt, $key) !== false) {
                return $response;
            }
        }

        return 'This is a mock response from AI provider. In real mode, you would get an actual AI-generated response here.';
    }

    public function streamText(string $prompt, array $options = []): \Generator
    {
        $response = $this->text($prompt, $options);

        // Split response into words and chunks for realistic streaming
        $words = explode(' ', $response);
        $currentChunk = '';
        $chunkSize = rand(2, 4); // Random chunk size for natural feel

        foreach ($words as $index => $word) {
            $currentChunk .= $word . ' ';

            // Emit chunk when we reach desired size or at the end
            if (count(explode(' ', trim($currentChunk))) >= $chunkSize || $index === count($words) - 1) {
                yield trim($currentChunk);
                $currentChunk = '';
                $chunkSize = rand(2, 4); // Randomize next chunk size

                // Simulate network delay (50-150ms)
                usleep(rand(50000, 150000));
            }
        }
    }

    public function streamTextWithTokens(string $prompt, array $options = []): \Generator
    {
        $response = $this->text($prompt, $options);
        $tokens = str_split($response);
        $currentToken = '';

        foreach ($tokens as $token) {
            $currentToken .= $token;

            // Emit tokens in small groups (1-3 characters)
            if (rand(1, 3) === 1 || $token === ' ' || $token === '.') {
                yield $currentToken;
                $currentToken = '';

                // Simulate token generation delay (10-50ms)
                usleep(rand(10000, 50000));
            }
        }

        // Emit any remaining characters
        if (!empty($currentToken)) {
            yield $currentToken;
        }
    }

    public function image(string $prompt, array $options = []): mixed
    {
        // Create a simple placeholder image path
        $filename = 'mock-image-' . md5($prompt) . '.jpg';

        // Return mock image response
        return (object) [
            'url' => "/storage/mock-images/{$filename}",
            'prompt' => $prompt,
            'width' => $options['width'] ?? 1024,
            'height' => $options['height'] ?? 1024,
            'model' => 'mock-image-model',
        ];
    }

    public function audio(string $text, array $options = []): mixed
    {
        $filename = 'mock-audio-' . md5($text) . '.mp3';

        return (object) [
            'url' => "/storage/mock-audio/{$filename}",
            'text' => $text,
            'voice' => $options['voice'] ?? 'female',
            'duration' => strlen($text) * 0.1, // Mock duration calculation
            'format' => 'mp3',
        ];
    }

    public function transcription(mixed $audio, array $options = []): Stringable|string
    {
        // Mock transcription based on audio file name
        if (is_string($audio) && str_contains($audio, 'hello')) {
            return 'Hello world! This is a mock transcription of the audio file.';
        }

        return 'This is a mock transcription. In real mode, this would contain the actual transcribed text from the audio file.';
    }

    public function embeddings(array $texts, array $options = []): array
    {
        // Generate deterministic mock embeddings (768 dimensions)
        $embeddings = [];
        foreach ($texts as $text) {
            $embedding = [];
            $seed = crc32($text);
            mt_srand($seed);

            for ($i = 0; $i < 768; $i++) {
                $embedding[] = (mt_rand() / mt_getrandmax() - 0.5) * 2; // Values between -1 and 1
            }

            $embeddings[] = $embedding;
        }

        return $embeddings;
    }

    public function rerank(array $documents, string $query, array $options = []): array
    {
        // Simple mock reranking based on keyword matching
        $scores = [];
        $queryWords = explode(' ', strtolower($query));

        foreach ($documents as $index => $document) {
            $score = 0;
            $docText = strtolower(is_string($document) ? $document : json_encode($document));

            foreach ($queryWords as $word) {
                if (str_contains($docText, $word)) {
                    $score += 1;
                }
            }

            $scores[$index] = $score;
        }

        arsort($scores);
        return array_keys($scores);
    }
}
