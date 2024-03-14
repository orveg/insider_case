<?php

namespace Database\Seeders;

use App\Enums\TeamEnum;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        foreach (TeamEnum::TEAMS as $team) {
            Team::create(array_merge($team,[
                'created_at' => $now,
                'updated_at' => $now
            ]));
        }
    }
}
