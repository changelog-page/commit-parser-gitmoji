<?php

declare(strict_types=1);

use Changelog\CommitParser\Gitmoji\Parser;

it('parses messages with a scope', function (string $message, ?int $ticket) {
    $actual = (new Parser())->parse("$message");

    expect($actual->type)->toBeString();
    expect($actual->scope)->toBeNull();
    expect($actual->breaking)->toBeFalse();
    expect($actual->message)->toBeString();
    expect($actual->ticket)->toBe($ticket);
})->with([
    [':memo: test', null],
    [':art: test', null],
    [':zap: test #123', 123],
    [':memo: update README.md', null],
    [':bricks: i have a word #123', 123],
    [':rocket: extract parser-opts packages', null],
    [':ambulance: 添加中文标题', null],
    [':sparkles: add chinese title', null],
    ['⚡️ Add focus trap to the menu', null],
    ['⚡️ Add focus trap to the menu #955', 955],
    ['⚡️ Add focus trap to the menu (#955)', 955],
]);
