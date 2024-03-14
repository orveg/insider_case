@if($league->standing)
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="table-auto w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
               aria-label="team table">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <th scope="col" class="px-6 py-3">#</th>
            <th scope="col" class="px-6 py-3">Team</th>
            <th scope="col" class="px-6 py-3">Played</th>
            <th scope="col" class="px-6 py-3">Won</th>
            <th scope="col" class="px-6 py-3">Drawn</th>
            <th scope="col" class="px-6 py-3">Lost</th>
            <th scope="col" class="px-6 py-3">GF</th>
            <th scope="col" class="px-6 py-3">GA</th>
            <th scope="col" class="px-6 py-3">GD</th>
            <th scope="col" class="px-6 py-3">Points</th>
            </thead>
            <tbody>
            @foreach($league->standing as $standing)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 dark:text-white">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{$standing->team->name}}</td>
                    <td class="px-6 py-4">{{$standing->played}}</td>
                    <td class="px-6 py-4">{{$standing->won}}</td>
                    <td class="px-6 py-4">{{$standing->drawn}}</td>
                    <td class="px-6 py-4">{{$standing->lost}}</td>
                    <td class="px-6 py-4">{{$standing->goals_for}}</td>
                    <td class="px-6 py-4">{{$standing->goals_against}}</td>
                    <td class="px-6 py-4">{{$standing->goals_difference}}</td>
                    <td class="px-6 py-4">{{$standing->points}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif

