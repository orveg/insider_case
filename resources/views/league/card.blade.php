<div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow  dark:border-gray-700">
    @if($header ?? true)
    <a href="{{route('league.show', $league)}}">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900  ">
            {{ $league->name }}
        </h5>
    </a>
    @endif
    <div class="mb-3 font-normal text-gray-700 dark:text-gray-400">
        <ul role="list" class="divide-y divide-gray-100">
            <li class="flex justify-between items-center gap-x-3 py-2">
                Team Count
                <span class="inline-flex rounded-md bg-indigo-50 px-2 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">{{ $league->teams->count() }}</span>
            </li>
            <li class="flex justify-between items-center gap-x-3 py-2">
                Match Count
                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">{{ $league->leagueMatches->count() }}</span>
            </li>
            <li class="flex justify-between items-center gap-x-3 py-2">
                Remind Match Count
                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">{{ $league->leagueMatches->where('status', 0)->count() }}</span>
            </li>
            <li class="flex justify-between items-center gap-x-3 py-2">
                Remind Week Count
                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">{{ $league->total_week - $league->played_week}}</span>
            </li>
            <li class="flex justify-between items-center gap-x-3 py-2">
                Status
                <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">{{ $league->status->value }}</span>
            </li>
        </ul>
        @if(\App\Enums\LeagueEnum::NOT_STARTED === $league->status)
            <a href="{{ url()->signedRoute('league.start', $league) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Start League
            </a>
        @endif
        @if(\App\Enums\LeagueEnum::STARTED === $league->status)
            <a href="{{ url()->signedRoute('league.simulate', [$league])  }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-1 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Simulate All Weeks
            </a>

        @endif
        @if(\App\Enums\LeagueEnum::NOT_STARTED !== $league->status)
            <a href="{{ url()->signedRoute('league.reset', [$league])  }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-1 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                Reset League
            </a>
        @endif
    </div>
</div>
