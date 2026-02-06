<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Promptable;
use Stringable;

class ChatAssistant implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    public function instructions(): Stringable|string
    {
        return 'أنت مساعد ذكي باللغة العربية والإنجليزية. تجيب بشكل واضح ومفيد. You are a smart assistant fluent in both Arabic and English. You provide clear and helpful answers.';
    }
}
