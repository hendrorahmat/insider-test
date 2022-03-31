@extends('layouts.app')

@section('content')
    <div class="row">
        @foreach($datas as $data)
            <div class="col-6" style="padding-top: 20px;">
                <div class="card">
                    <div class="card-header">
                        <b>
                            {{ $data->getGroup()->getName() }}
                        </b>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Club</th>
                                    <th>M</th>
                                    <th>D</th>
                                    <th>L</th>
                                    <th>GF</th>
                                    <th>GA</th>
                                    <th>GD</th>
                                    <th>Pts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->getClubs() as $club)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $club->getName() }}</td>
                                        <td>{{ $club->getStanding()->getMatch() }}</td>
                                        <td>{{ $club->getStanding()->getDraw() }}</td>
                                        <td>{{ $club->getStanding()->getLoose() }}</td>
                                        <td>{{ $club->getStanding()->getGoalFor() }}</td>
                                        <td>{{ $club->getStanding()->getGoalAgainst() }}</td>
                                        <td>{{ $club->getStanding()->getGoalDiff() }}</td>
                                        <td>{{ $club->getStanding()->getTotalPoints() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a class="btn btn-info" href="{{ route('groups.show', $data->getGroup()->getId()) }}">Play Qualification</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
