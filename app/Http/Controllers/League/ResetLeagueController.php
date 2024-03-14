<?php

namespace App\Http\Controllers\League;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Services\League\LeagueService;
use Illuminate\Http\RedirectResponse;

class ResetLeagueController extends Controller
{
    protected LeagueService $leagueService;

    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }

    public function __invoke(League $league): RedirectResponse
    {
        $this->leagueService->reset($league);

        return redirect()->route('league.show', $league);
    }
}