<?php

namespace App\Http\Controllers\League;

use App\Http\Controllers\Controller;
use App\Models\League;
use App\Services\Approximation\ApproximationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ShowLeagueController extends Controller
{
    public function __invoke(League $league, ApproximationService $predictionService): View|Factory|Application
    {
        $approximations = $this->getPredictions($league, $predictionService);

        return view('league.show', [
            'league' => $league,
            'approximations' => $approximations
        ]);
    }

    private function getPredictions(League $league, ApproximationService $predictionService): array
    {
        return $predictionService->setLeague($league)->getPrediction();
    }
}