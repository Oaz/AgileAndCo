<?php

namespace Bga\Games\AgileAndCo;

class Text
{
    public static array $messages = [
        'ACTIVITY_WAS_CHOSEN' => '${player_name} chooses activity "${activity}"',
        'ACTIVITY_DEVELOPMENT' => 'Development',
        'ACTIVITY_DEPLOYMENT' => 'Deployment',
        'ACTIVITY_RETROSPECTIVE' => 'Retrospective',
        'ACTIVITY_CONFERENCE' => 'Conference',
        'ACTIVITY_COACH' => 'Coach',
    ];

    public static function get(string $messageId): string
    {
        return clienttranslate(self::$messages[$messageId]) ?? $messageId;
    }
}