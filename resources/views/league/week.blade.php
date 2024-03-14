<div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-center text-gray-900 dark:text-white">{{$week->first()->week}}. Week Matches</h5>
    <div class="mb-3 font-normal text-gray-700 dark:text-white">
        <div class="relative overflow-x-hidden">
            <table class="table-auto w-full text-gray-500 dark:text-gray-400">
                <tbody>
                @foreach($week as $match)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="px-2 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white text-left">
                        {{$match->homeTeam->name}}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $match->home_team_goal }}
                    </td>
                    <td class="px-2 py-2 text-center">
                        {{ $match->away_team_goal }}
                    </td>
                    <td class="px-2 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white text-right">
                        {{$match->awayTeam->name}}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($week->firstWhere('status', 0))
        <div class="max-w-fit">
            <a href="{{ url()->signedRoute('league.simulate', [$match->league, $week->first()->week])  }}"
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Simulate {{$week->first()->week}}. Week
            </a>
        </div>
    @endif
</div>
