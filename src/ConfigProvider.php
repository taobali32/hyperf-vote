<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Jtar\HyperfVote;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for vote.',
                    'source' => __DIR__ . '/../publish/vote.php',
                    'destination' => BASE_PATH . '/config/autoload/vote.php',
                ],
                [
                    'id' => 'sql',
                    'description' => 'The config for follow.sql',
                    'source' => __DIR__ . '/../publish/migrations/2022_11_10_193313_create_votes_table.php',
                    'destination' => BASE_PATH . '/migrations/2022_11_10_193313_create_votes_table.php',
                ],
            ],
        ];
    }
}
