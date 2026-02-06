<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class RandomNumberGenerator implements Tool
{
    public function description(): Stringable|string
    {
        return 'Generate a cryptographically secure random number between min and max values.';
    }

    public function handle(Request $request): Stringable|string
    {
        $min = $request['min'] ?? 0;
        $max = $request['max'] ?? 100;

        return (string) random_int($min, $max);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'min' => $schema->integer()->min(0)->required()->description('Minimum value for the random number'),
            'max' => $schema->integer()->required()->description('Maximum value for the random number'),
        ];
    }
}
