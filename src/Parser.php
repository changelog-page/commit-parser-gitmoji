<?php

declare(strict_types=1);

namespace Changelog\CommitParser\Gitmoji;

use Changelog\CommitParser\AbstractCommitParser;
use Illuminate\Support\Collection;
use Spatie\Regex\MatchResult;

final class Parser extends AbstractCommitParser
{
    public function pattern(): string
    {
        return '/^(?<emoji>((?::\w*:)|(?:[\x{2600}-\x{26FF}\x{1F600}-\x{1F64F}])))\s?(?<subject>(?:(?!#).)*(?:(?!\s).))\s?(?<ticket>(#\d+)|\(#\d+\))?$/u';
    }

    protected function getType(MatchResult $result): string
    {
        $type   = trim($result->groupOr('emoji', ''));
        $emojis = new Collection(json_decode(file_get_contents(realpath(__DIR__.'/../data/gitmoji.json')), true));

        if (str_starts_with($type, ':')) {
            $type = substr($result->groupOr('emoji', ''), 1, -1);
        } else {
            foreach ($emojis as $emoji) {
                $actual = mb_convert_encoding($emoji['emoji'], 'UTF-32', 'UTF-8');

                if (str_starts_with($actual, mb_convert_encoding($type, 'UTF-32', 'UTF-8'))) {
                    $type = $emoji['name'];

                    break;
                }
            }
        }

        return [
            'art'                       => 'refactor',
            'zap'                       => 'perf',
            'fire'                      => 'chore',
            'bug'                       => 'fix',
            'ambulance'                 => 'fix',
            'sparkles'                  => 'feat',
            'memo'                      => 'docs',
            'rocket'                    => 'ci',
            'lipstick'                  => 'style',
            'tada'                      => 'chore',
            'white-check-mark'          => 'test',
            'lock'                      => 'fix',
            'bookmark'                  => 'release',
            'rotating-light'            => 'chore',
            'construction'              => 'chore',
            'green-heart'               => 'ci',
            'arrow-down'                => 'chore',
            'arrow-up'                  => 'chore',
            'pushpin'                   => 'chore',
            'construction-worker'       => 'ci',
            'chart-with-upwards-trend'  => 'chore',
            'recycle'                   => 'refactor',
            'heavy-plus-sign'           => 'chore',
            'heavy-minus-sign'          => 'chore',
            'wrench'                    => 'chore',
            'hammer'                    => 'chore',
            'globe-with-meridians'      => 'chore',
            'pencil2'                   => 'chore',
            'poop'                      => 'chore',
            'rewind'                    => 'revert',
            'twisted-rightwards-arrows' => 'chore',
            'package'                   => 'chore',
            'alien'                     => 'refactor',
            'truck'                     => 'chore',
            'page-facing-up'            => 'chore',
            'boom'                      => 'breaking',
            'bento'                     => 'chore',
            'wheelchair'                => 'chore',
            'bulb'                      => 'docs',
            'beers'                     => 'refactor',
            'speech-balloon'            => 'chore',
            'card-file-box'             => 'refactor',
            'loud-sound'                => 'chore',
            'mute'                      => 'chore',
            'busts-in-silhouette'       => 'chore',
            'children-crossing'         => 'refactor',
            'building-construction'     => 'refactor',
            'iphone'                    => 'chore',
            'clown-face'                => 'test',
            'egg'                       => 'chore',
            'see-no-evil'               => 'chore',
            'camera-flash'              => 'chore',
            'alembic'                   => 'refactor',
            'mag'                       => 'perf',
            'label'                     => 'refactor',
            'seedling'                  => 'refactor',
            'triangular-flag-on-post'   => 'refactor',
            'goal-net'                  => 'fix',
            'animation'                 => 'refactor',
            'wastebasket'               => 'refactor',
            'passport-control'          => 'refactor',
            'adhesive-bandage'          => 'fix',
            'monocle-face'              => 'refactor',
            'coffin'                    => 'refactor',
            'test-tube'                 => 'test',
            'necktie'                   => 'refactor',
            'stethoscope'               => 'chore',
            'bricks'                    => 'chore',
            'technologist'              => 'refactor',
        ][$type];
    }

    protected function getBreakingChange(MatchResult $result): bool
    {
        $emoji = $result->groupOr('emoji', '');

        if ($emoji === '') {
            return false;
        }

        if ($emoji === ':boom:') {
            return true;
        }

        if ($emoji === '💥') {
            return true;
        }

        return false;
    }
}
