<div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow">
    <div class="p-6">
        <h5 class="block mb-2 antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
            {{ $league->played_week }}. Week Champion Approximation
        </h5>
        <ul role="list" class="divide-y divide-gray-100">
            @foreach($approximations as $prediction)
            <li class="flex justify-between items-center gap-x-3 py-2">
                {{$prediction['name']}}
                <span class="inline-flex rounded-md bg-indigo-50 px-2 py-2 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">{{ $prediction['rate'] }}%</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>

