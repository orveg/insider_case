@extends('layouts.app')
@section('content')
    <div class="mt-2">
            @foreach($leagues as $league)
                @include('league.card', $league)
            @endforeach
    </div>
@endsection
