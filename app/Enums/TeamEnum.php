<?php

namespace App\Enums;

enum TeamEnum: string
{
    public const TEAMS = [
        [
            'name' => 'Manchester City',
            'strength' => 0.85,
        ],
        [
            'name' => 'Liverpool',
            'strength' => 0.83,
        ],
        [
            'name' => 'Arsenal',
            'strength' => 0.82,
        ],
        [
            'name' => 'Chelsea',
            'strength' => 0.80,
        ]
    ];
}
