<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class CodeReviewer implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return 'You are an expert code reviewer. Analyze the provided code and provide structured feedback including a quality score, identified issues, improvement suggestions, and a summary.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'score' => $schema->integer()->min(1)->max(10)->required()->description('Overall code quality score from 1-10'),
            'issues' => $schema->array()->items($schema->string())->required()->description('List of identified issues or problems'),
            'suggestions' => $schema->array()->items($schema->string())->required()->description('List of improvement suggestions'),
            'summary' => $schema->string()->required()->description('Brief summary of the code review'),
        ];
    }
}
