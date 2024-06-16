# Laravel Discord Command Handler

## Introduction

Accepts Discord Interactions at /api/webhook and allows you to register Commands

### How it works...

In a word; Jankily.

You create your Commands in `app/DiscordCommands`, and register them in `App/Providers/DiscordCommandsServiceProvider`:

```php
$handler->command('/2/command/subcommand', \App\DiscordCommands\SubCommand::class);
```

Then; the Controller will use `\App\DiscordCommands\SubCommand` to process the request
