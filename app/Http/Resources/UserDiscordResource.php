<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDiscordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $latestQuestions = $this->resource->questions()->latest()->take(3)->get()->map(fn ($question) => "- $question->name")->join("\n");
        $countQuestions = $this->resource->questions()->count();
        $countAnswers = $this->resource->answers()->count();

        return [
            'title' => "$this->username's Profile",
            'description' => "### Latest questions\n$latestQuestions",
            'color' => 8514339,
            'fields' => [
                [
                    'name' => 'Questions:',
                    'value' => '12',
                    'inline' => $countQuestions,
                ],
                [
                    'name' => 'Answers:',
                    'value' => $countAnswers,
                    'inline' => true,
                ],
            ],
            // @TODO: Move this to config or summat
            'author' => [
                'name' => 'nor(DEV): StackOverflow',
                'url' => 'https://hub.norfolkdevelopers.com',
            ],
            'footer' => [
                'text' => 'nor(DEV): StackOverflow is in ALPHA',
            ],
        ];
    }
}
