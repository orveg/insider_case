<?php

namespace App\Services\League;

use App\Enums\LeagueEnum;
use App\Models\League;

class LeagueService
{
    private const DEFAULT_VALUE = 0;
    private const MATCH_STATUS = 0;

    public static function reset(League $league): void
    {
        self::resetStandings($league);
        self::resetMatches($league);
        self::resetLeague($league);
    }

    private static function resetStandings(League $league): void
    {
        $standing = $league->standing;
        $standing->each->update([
            'played' => self::DEFAULT_VALUE,
            'won' => self::DEFAULT_VALUE,
            'drawn' => self::DEFAULT_VALUE,
            'lost' => self::DEFAULT_VALUE,
            'goals_for' => self::DEFAULT_VALUE,
            'goals_against' => self::DEFAULT_VALUE,
            'goals_difference' => self::DEFAULT_VALUE,
            'points' => self::DEFAULT_VALUE
        ]);
    }

    private static function resetMatches(League $league): void
    {
        $leagueMatches = $league->leagueMatches;
        $leagueMatches->each->update([
            'home_team_goal' => self::DEFAULT_VALUE,
            'away_team_goal' => self::DEFAULT_VALUE,
            'status' => self::MATCH_STATUS
        ]);
    }

    private static function resetLeague(League $league): void
    {
        $league->update(['status' => LeagueEnum::STARTED, 'played_week' => self::DEFAULT_VALUE]);
    }
}