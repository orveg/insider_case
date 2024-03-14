@extends('layouts.app')
@section('content')
    <h2 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl">{{ $league->name  }}</h2>
    <div class="grid grid-cols-3 md:grid-cols-3 gap-2">
        <div>
            @include('league.card', ['league' => $league, 'header' => false, 'class' => ''])
        </div>
        <div class="col-span-2">
            @include('league.table', $league)
        </div>
    </div>
    <h3 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl mt-5">
        Matches</h3>
    <div class="grid grid-cols-4 md:grid-cols-4 gap-2">
        @foreach($league->leagueMatches->groupBy('week') as $week)
            @include('league.week', $week)
        @endforeach
        @if(($league->played_week >= 4 and $league->played_week < 6))
            @include('league.approximation', $approximations)
        @endif
    </div>
@endsection
