<?php

namespace App\DiscordCommands;

use App\Contracts\DiscordCommand;
use App\DTO\DiscordInteraction;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

final class AnsweredMark implements DiscordCommand
{
    public static function handle(DiscordInteraction $interaction): JsonResponse
    {
        $command = $interaction->toCommandInteraction();
        $users = $command->resolve();
        $by = $users->get(
            $command->options->get('by')['value']
        );

        // Channel is a thread
        if ($interaction->channel->type !== 11) {
            return response()->error('This command can only be used in threads.');
        }

        // @TODO: Because @needle creates auto-threads, it is the owner. Need to
        //        find a more reliable means to track the owner (first message?)
        //
        // Member is thread owner
        // if ($interaction->channel->owner_id !== $interaction->member->discord_id) {
        //     return response()->error('This command can only be used by the thread owner.');
        // }

        // Member hasn't elected themselves as answerer
        if ($by->discord_id === $interaction->member->discord_id) {
            return response()->error('Whilst we admire your problem solving skills, you cannot elect yourself as the answerer.');
        }

        // Channel hasn't already been marked as Complete
        if (Question::query()->where('channel_id', $interaction->channel->id)->exists()) {
            return response()->error('This thread has already been marked as answered.');
        }

        $question = Question::factory()
            ->for($interaction->member, 'owner')
            ->for($by, 'answerer')
            ->create([
                'name' => $interaction->channel->name,
                'channel_id' => $interaction->channel->id,
            ]);

        $questions = $interaction->member->fresh()->questions->count();
        $answers = $by->fresh()->answers->count();

        return response()->json([
            'type' => 4,
            'data' => [
                'content' => "Congratulations <@{$interaction->member->discord_id}>, you have now asked $questions questions, we're glad to hear you got the answer you needed! Thanks <@{$by->discord_id}>, you have now answered $answers questions!",
            ],
        ]);
    }
}
