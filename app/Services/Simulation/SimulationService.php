<?php

namespace App\Services\Simulation;

use App\Contracts\Repositories\LeagueMatchRepositoryContract;
use App\Enums\LeagueEnum;
use App\Enums\PointEnum;
use App\Models\League;
use App\Models\LeagueMatch;
use App\Models\LeagueTeamStanding;
use App\Models\Team;
use Illuminate\Support\Collection;

class SimulationService
{
    /**
     * @var League
     */
    private League $league;

    /**
     * @var int|null
     */
    private ?int $week = null;

    /**
     * @var Team
     */
    private Team $homeTeam;

    /**
     * @var Team
     */
    private Team $awayTeam;

    /**
     * @var LeagueTeamStanding
     */
    private LeagueTeamStanding $homeTeamStanding;

    /**
     * @var LeagueTeamStanding
     */
    private LeagueTeamStanding $awayTeamStanding;

    /**
     * @var int
     */
    private int $homeTeamScore;

    /**
     * @var int
     */
    private int $awayTeamScore;

    /**
     * @param LeagueMatchRepositoryContract $leagueMatchRepository
     */
    public function __construct(
        private readonly LeagueMatchRepositoryContract $leagueMatchRepository
    ){}


    /**
     * Simulates the matches in the league.
     *
     * @return void
     */
    public function simulate(): void
    {
        $matches = $this->getMatchList();
        $weekCount = $matches->groupBy('week')->count();
        foreach ($matches as $match) {
            $this->homeTeam = $match->homeTeam;
            $this->awayTeam = $match->awayTeam;
            $this->homeTeamStanding = $this->homeTeam->standing()->where('league_id', $this->league->id)->first();
            $this->awayTeamStanding = $this->awayTeam->standing()->where('league_id', $this->league->id)->first();
            $this->simulateMatch(match: $match);
        }

        $this->league->played_week = $this->league->played_week + $weekCount;
        $this->week = null;

        if ($this->getMatchList()->isEmpty()) {
            $this->league->status = LeagueEnum::ENDED;
        }

        $this->league->save();
    }

    /**
     * Simulates a match by generating scores for the home and away teams,
     * deciding the winner, and updating the match details.
     *
     * @param LeagueMatch $match The match to simulate.
     *
     * @return void
     */
    private function simulateMatch(LeagueMatch $match): void
    {
        $this->homeTeamScore = $this->generateScore(home: true, strength: $this->homeTeam->strength);
        $this->awayTeamScore = $this->generateScore(home: false, strength: $this->awayTeam->strength);
        $this->decideWinner();

        $match->home_team_goal = $this->homeTeamScore;
        $match->away_team_goal = $this->awayTeamScore;
        $match->status = true;
        $match->save();
    }

    /**
     * Updates the standing of the home and away teams after a match.
     *
     * This method increments the played games count for both teams, updates their
     * goals scored and goals conceded, and calculates the goal difference.
     * Finally, it saves the updated standings for both teams.
     *
     * @return void
     */
    private function updateStanding(): void
    {
        $this->homeTeamStanding->played++;
        $this->homeTeamStanding->goals_for = $this->homeTeamStanding->goals_for + $this->homeTeamScore;
        $this->homeTeamStanding->goals_against = $this->homeTeamStanding->goals_against + $this->awayTeamScore;
        $this->homeTeamStanding->goals_difference = $this->homeTeamStanding->goals_for - $this->homeTeamStanding->goals_against;

        $this->awayTeamStanding->played++;
        $this->awayTeamStanding->goals_for = $this->awayTeamStanding->goals_for + $this->awayTeamScore;
        $this->awayTeamStanding->goals_against = $this->awayTeamStanding->goals_against + $this->homeTeamScore;
        $this->awayTeamStanding->goals_difference = $this->awayTeamStanding->goals_for - $this->awayTeamStanding->goals_against;

        $this->homeTeamStanding->save();
        $this->awayTeamStanding->save();
    }

    /**
     * Updates the winner and standing based on the scores of the home team and away team.
     *
     * If the home team has a higher score than the away team, the winner will be set to the home team,
     * and the home team's standing will be updated to reflect the win. The away team's standing will be
     * updated to reflect the loss.
     *
     * If the away team has a higher score than the home team, the winner will be set to the away team,
     * and the away team's standing will be updated to reflect the win. The home team's standing will be
     * updated to reflect the loss.
     *
     * If both teams have the same score, the match is considered a draw. Both teams' standings will be
     * updated to reflect the draw.
     *
     * @return void
     */
    private function decideWinner(): void
    {
        if ($this->homeTeamScore > $this->awayTeamScore) {
            $this->winner = $this->homeTeam;
            $this->homeTeamStanding->won++;
            $this->homeTeamStanding->points = $this->homeTeamStanding->points + PointEnum::WON_POINT;
            $this->awayTeamStanding->lost++;
        } elseif ($this->homeTeamScore < $this->awayTeamScore) {
            $this->winner = $this->awayTeam;
            $this->awayTeamStanding->won++;
            $this->awayTeamStanding->points = $this->awayTeamStanding->points + PointEnum::WON_POINT;
            $this->homeTeamStanding->lost++;
        } else {
            $this->awayTeamStanding->drawn++;
            $this->homeTeamStanding->drawn++;

            $this->homeTeamStanding->points = $this->homeTeamStanding->points + PointEnum::DRAWN_POINT;
            $this->awayTeamStanding->points = $this->awayTeamStanding->points + PointEnum::DRAWN_POINT;
        }

        $this->updateStanding();
    }

    /**
     * Generates a score based on the given parameters.
     *
     * @param bool $home Indicates whether the score is for the home team.
     * @param float $strength The strength value to calculate the score.
     *
     * @return int The generated score.
     */
    private function generateScore(bool $home, float $strength): int
    {
        $score = round(rand(0, PointEnum::MAX_GOAL) * $strength);

        return $home ? ++$score : $score;
    }

    /**
     * Get the list of matches that have not started yet, based on the provided league and week.
     *
     * @return Collection The collection of matches that have not started yet.
     */
    private function getMatchList(): Collection
    {
        return $this->leagueMatchRepository->getNotStartedMatchByLeagueAndWeek($this->league->id, $this->week);
    }

    /**
     * Sets the league for the object.
     *
     * @param League $league The league to set.
     * @return self Returns the updated object.
     */
    public function setLeague(League $league): self
    {
        $this->league = $league;

        return $this;
    }


    /**
     * Set the week for the current instance and return the modified instance.
     *
     * @param int|null $week The week to be set. Pass null to unset the week.
     * @return self
     */
    public function setWeek(?int $week): self
    {
        $this->week = $week;

        return $this;
    }
}
