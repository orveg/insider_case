<?php

namespace App\Services\Approximation;

use App\Enums\PointEnum;
use App\Models\League;
use App\Models\LeagueTeamStanding;
use App\Models\Team;

class ApproximationService
{
    /**
     * @var League
     */
    private League $league;

    /**
     * @var int
     */
    private int $remainedPoints;

    /**
     * @var int
     */
    private int $topTeamPoint;

    /**
     * @var array
     */
    private array $prediction = [];


    /**
     * Retrieves the prediction for the teams in the league.
     *
     * @return array The predicted chances for each team in the league.
     */
    public function getPrediction(): array
    {
        if ($this->checkIfPredictionIsNeeded()) {
            return [];
        }

        $this->remainedPoints = PointEnum::WON_POINT * ($this->league->total_week - $this->league->played_week);
        $this->topTeamPoint = $this->league->standing->first()->points;

        foreach ($this->league->teams as $team) {
            $this->prediction[$team->name] = $this->calculateTeamChance($team);
        }

        $this->calculateChanceInPercentage();

        return $this->prediction;
    }

    /**
     * Checks if a prediction is needed for the current league.
     *
     * @return bool Whether a prediction is needed or not.
     */
    private function checkIfPredictionIsNeeded(): bool
    {
        return (
            $this->league->played_week === 0
            || $this->league->played_week === $this->league->total_week
        );
    }

    /**
     * Calculates the chance for a team to perform well in the league.
     *
     * @param Team $team The team for which the chance is calculated.
     * @return float|int The calculated chance for the team. If the chance is negative, 0 is returned.
     */
    private function calculateTeamChance(Team $team): float|int
    {
        /** @var LeagueTeamStanding $teamStanding */
        $teamStanding = $team->standing()->where('league_id', $this->league->id)->first();

        if ($this->remainedPoints + $teamStanding->points < $this->topTeamPoint) {
            return 0;
        }

        $chance = 0;
        $matches = $team->leagueMatches($this->league->id);

        foreach ($matches as $match) {
            if ($match->home_team == $team->id) {
                $chance += 1;
            }

            if ($match->away_team == $team->id) {
                $chance += 0;
            }
        }

        $chance = $chance * $team->strength - (($this->topTeamPoint - $teamStanding->points) / 2);

        return max($chance, 0);
    }

    /**
     * Calculates the chance in percentage for each team based on the raw prediction.
     *
     * @return void
     */
    private function calculateChanceInPercentage(): void
    {
        $rawPrediction = $this->prediction;

        $this->prediction = [];

        $onePointPercent = 100 / (array_sum($rawPrediction) != 0 ? array_sum($rawPrediction) : 1);

        foreach ($rawPrediction as $team => $teamChance) {

            $this->prediction[] = [
                'name' => $team,
                'rate' => round($teamChance * $onePointPercent, 2)
            ];
        }


        $keys = array_column($this->prediction, 'rate');

        array_multisort($keys, SORT_DESC, $this->prediction);
    }

    /**
     * Sets the league for the Prediction class.
     *
     * @param League $league The league object to be set.
     * @return self Returns an instance of the Prediction class.
     */
    public function setLeague(League $league): self
    {
        $this->league = $league;

        return $this;
    }
}
