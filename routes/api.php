<?php

use App\DTO\DiscordInteraction;
use App\Http\Middleware\DiscordValidateRequest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// @TODO: Create a Route abstraction, that allows you to register routes something like: /{type}/{command}/{?subcommand} e.g. /2/command/subcommand
// @TODO: Command Validation ?
Route::middleware(DiscordValidateRequest::class)->post('/webhook', function (Request $request) {
    $interaction = DiscordInteraction::make($request->json()->all());

    if ($interaction->type === 1) {
        return response()->json(['type' => 1]);
    }

    if ($interaction->type === 2) {
        $command = $interaction->toCommandInteraction();
        // @TODO: Add a bunch of validation logic

        // Channel is a thread
        if ($interaction->channel->type !== 11) {
            return response()->json([
                'type' => 4,
                'data' => [
                    'flags' => 1 << 6,
                    'content' => 'This command can only be used in threads.',
                ],
            ]);
        }

        // Member is thread owner
        if ($interaction->channel->owner_id !== $interaction->member->discord_id) {
            return response()->json([
                'type' => 4,
                'data' => [
                    'flags' => 1 << 6,
                    'content' => 'This command can only be used by the thread owner.',
                ],
            ]);
        }

        // Member hasn't elected themselves as answerer
        if ($command->by->discord_id === $interaction->member->discord_id) {
            return response()->json([
                'type' => 4,
                'data' => [
                    'flags' => 1 << 6,
                    'content' => 'Whilst we admire your problem solving skills, you cannot elect yourself as the answerer.',
                ],
            ]);
        }

        // Channel hasn't already been marked as Complete
        if (Question::query()->where('channel_id', $interaction->channel->id)->exists()) {
            return response()->json([
                'type' => 4,
                'data' => [
                    'flags' => 1 << 6,
                    'content' => 'This thread has already been marked as answered.',
                ],
            ]);
        }

        $question = Question::factory()
            ->for($interaction->member, 'owner')
            ->for($command->by, 'answerer')
            ->create([
                'name' => $interaction->channel->name,
                'channel_id' => $interaction->channel->id,
            ]);

        $answers = $command->by->fresh()->answers->count();

        return response()->json([
            'type' => 4,
            'data' => [
                'content' => "Congratulation <@{$interaction->member->discord_id}>, we're glad to hear you got the answer you needed! Thanks <@{$command->by->discord_id}>, you have now answered $answers questions!",
            ],
        ]);
    }
});
