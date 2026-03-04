<?php

namespace App\Services;

use Anthropic\Client;
use Anthropic\Messages\MessageParam;
use App\Models\AiInsight;
use Illuminate\Database\Eloquent\Model;
use Parsedown;

class AIService
{
    protected Client $client;
    protected string $model;

    public function __construct()
    {
        $this->client = new Client(
            apiKey: config('services.anthropic.key')
        );
        $this->model = config('services.anthropic.model');
    }

    public function ask(string $systemPrompt, string $userMessage, int $maxTokens = 1024): string
    {
        $message = $this->client->messages->create(
            maxTokens: $maxTokens,
            messages: [
                MessageParam::with(role: 'user', content: $userMessage)
            ],
            model: $this->model,
            system: $systemPrompt,
        );

        return $message->content[0]->text;
    }

    // Ask Claude AND save the result to database
    public function askAndSave(
        string $type,
        Model $subject,
        string $systemPrompt,
        string $userMessage,
        int $maxTokens = 1024
    ): AiInsight {
        $response     = $this->ask($systemPrompt, $userMessage, $maxTokens);
        $responseHtml = (new Parsedown())->text($response);

        return AiInsight::updateOrCreate(
            [
                'user_id'      => auth()->id(),
                'type'         => $type,
                'subject_type' => get_class($subject),
                'subject_id'   => $subject->id,
            ],
            [
                'prompt'       => $userMessage,
                'response'     => $response,
                'response_html' => $responseHtml,
                'generated_at' => now(),
            ]
        );
    }
}