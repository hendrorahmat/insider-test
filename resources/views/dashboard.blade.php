@extends('layouts.app')
@section('content')
<br>
<a href="{{ route('champion.league.index') }}" class="btn btn-primary">Champion League</a>
<br>
<br>
@foreach($datas as $data)
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <nav class="navbar navbar-light bg-light">
                        <div class="container-fluid">
                            <span class="navbar-brand mb-0 h1">{{ $data->name }}</span>
                        </div>
                    </nav>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Teams</th>
                            <th scope="col">PTS</th>
                            <th scope="col">P</th>
                            <th scope="col">W</th>
                            <th scope="col">D</th>
                            <th scope="col">L</th>
                            <th scope="col">GD</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->leagueClubsActive as $leagueClub)
                            <tr>
                                <td>{{ $leagueClub->club->name }}</td>
                                <td>{{ $leagueClub->total_points }}</td>
                                <td>{{ $leagueClub->match }}</td>
                                <td>{{ $leagueClub->win }}</td>
                                <td>{{ $leagueClub->draw }}</td>
                                <td>{{ $leagueClub->loose }}</td>
                                <td>{{ $leagueClub->goal_difference }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
@endforeach
@endsection
