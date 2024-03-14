<?php
namespace App\Services\Algorithm;

class Algorithm
{
    const MINIMUM_NUMBER_OF_TEAMS = 2;

    /**
     * Generates the match schedule for a round of a tournament.
     *
     * @param array $teamsInRound An array of teams participating in the round.
     * @return array The generated match schedule.
     *
     * @throws \InvalidArgumentException If the $teamsInRound array is empty.
     *
     */
    public static function generateMatchSchedule(array $teamsInRound): array
    {
        $teamsCount = count($teamsInRound);

        if ($teamsCount === 0) {
            throw new \InvalidArgumentException('Algorithm expects an array of at least 1 team');
        }

        if ($teamsCount % 2 == 1) {
            $teamsInRound[] = NULL;
            $teamsCount++;
        }

        if ($teamsCount === self::MINIMUM_NUMBER_OF_TEAMS) {
            return [
                [$teamsInRound[0], $teamsInRound[1]],
            ];
        }

        $firstHalfCalendar = self::generateFirstHalfSchedule($teamsInRound, $teamsCount);

        return self::generateSecondHalfSchedule($firstHalfCalendar);
    }

    /**
     * Generates the first half of the match schedule for a round of a tournament.
     *
     * @param array $teamsInRound An array of teams participating in the round.
     * @param int $teamsCount The number of teams in the round.
     * @return array The generated first half of the match schedule.
     *
     * @throws \InvalidArgumentException If the $teamsInRound array is empty.
     *
     */
    private static function generateFirstHalfSchedule(array $teamsInRound, int $teamsCount): array
    {
        $gamesCount = $teamsCount - 1;

        $home = [];
        $away = [];

        for ($i = 0; $i < $teamsCount / 2; $i++) {
            $home[$i] = $teamsInRound[$i];
            $away[$i] = $teamsInRound[$teamsCount - 1 - $i];
        }

        $firstHalfCalendar = [];
        for ($i = 0; $i < $gamesCount; $i++) {
            if (($i % 2) == 0) {
                for ($j = 0; $j < $teamsCount / 2; $j++) {
                    $firstHalfCalendar[$i][] = [$away[$j], $home[$j]];
                }
            } else {
                for ($j = 0; $j < $teamsCount / 2; $j++) {
                    $firstHalfCalendar[$i][] = [$home[$j], $away[$j]];
                }
            }

            $pivot = array_shift($home);
            array_unshift($away, array_shift($home));
            $home[] = array_pop($away);
            array_unshift($home, $pivot);
        }
        return $firstHalfCalendar;
    }

    /**
     * Generates the second half of the schedule based on the given first half calendar.
     *
     * @param array $firstHalfCalendar The first half calendar containing the matches.
     *
     * @return array The generated second half calendar.
     */
    private static function generateSecondHalfSchedule(array $firstHalfCalendar): array
    {
        $secondHalfCalendar = [];
        foreach ($firstHalfCalendar as $weekKey => $matches) {
            foreach ($matches as $match) {
                $secondHalfCalendar[$weekKey][] = [
                    $match[1],
                    $match[0],
                ];
            }
        }
        return array_merge($firstHalfCalendar, $secondHalfCalendar);
    }
}