@extends('layouts.app')

@section('content')
    <div class="row" style="padding-top: 20px;">
        <div class="col">
            <a href="{{ route('champion.league.index') }}" class="btn btn-warning">Back</a>
        </div>
    </div>
    <div class="row" style="padding-top: 20px;">
        <div class="{{ count($match->getClubs()) <=0 ? 'col-6' : 'col-5' }}">
            <div class="card">
                <div class="card-header">
                    <b>Standings</b>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Club</th>
                            <th>P</th>
                            <th>W</th>
                            <th>D</th>
                            <th>L</th>
                            <th>GF</th>
                            <th>GA</th>
                            <th>GD</th>
                            <th>Pts</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groupChampion->getClubs() as $club)
                            <tr>
                                <td>{{ $club->getName() }}</td>
                                <td>{{ $club->getStanding()->getMatch() }}</td>
                                <td>{{ $club->getStanding()->getWin() }}</td>
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
                </div>
            </div>
        </div>
        @if(count($match->getClubs()) > 0)
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <b>{{ $match->getName() }}</b>
                    </div>
                    <div class="card-body" style="height: 250px;">
                        <table class="table table-hover table-borderless">
                            <tbody>
                            @foreach($match->getClubs() as $club)
                                <tr>
                                    <td>{{ $club->getHome()->getName() }}</td>
                                    <td>vs</td>
                                    <td>{{ $club->getAway()->getName() }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        <div class="{{ count($match->getClubs()) <=0 ? 'col-6' : 'col-3' }}">
            <div class="card">
                <div class="card-header">
                    <b>Championship Predictions</b>
                </div>
                <div class="card-body" style="height: 255px;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Club</th>
                                <th>Champion</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($clubPredictions as $clubPrediction)
                            <tr>
                                <td>{{ $clubPrediction->getName() }}</td>
                                <td>{{ $clubPrediction->getStanding()->getChampionshipPercentage() }}%</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="padding-top: 20px;">
        @if(count($match->getClubs()) > 0)
            <div class="col">
                <form action="{{ route('play-match.store') }}" method="POST">
                    @csrf
                    @foreach($uuids as $uuid)
                        <input type="hidden" name="match_uuids[]" value="{{ $uuid }}">
                    @endforeach
                    <input type="hidden" name="group_id" value="{{ $groupChampion->getGroup()->getId() }}">
                    <input type="submit" value="Play All Weeks" class="btn btn-info">
                </form>
            </div>
            <div class="col">
                <form action="{{ route('play-match.store') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @foreach($match->getClubs() as $club)
                        <input type="hidden" name="match_uuids[]" value="{{ $club->getMatchUuid() }}">
                    @endforeach
                    <input type="hidden" name="group_id" value="{{ $groupChampion->getGroup()->getId() }}">
                    <input type="submit" value="Play Week" class="btn btn-info">
                </form>
            </div>
        @endif
        <div class="col">
            <form action="{{ route('reset-match.store', [$groupChampion->getGroup()->getId()]) }}" method="post"
                  class="btn btn-destroy">
                @csrf
                <input type="hidden" name="group_id" value="{{ $groupChampion->getGroup()->getId() }}">
                <input type="submit" value="Reset Data" class="btn btn-danger">
            </form>
        </div>
    </div>
    @if(count($histories) > 0)
        <div class="row">
            <div class="d-flex justify-content-center">
                <h2>Match Results</h2>
            </div>
            @foreach($histories as $match)
                <div class="col-6" style="padding-top: 20px; padding-bottom: 20px;">
                    <div class="card">
                        <div class="card-header">
                            <b>{{ $match->getName() }}</b>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover table-responsive">
                                <tbody>
                                @foreach($match->getClubs() as $club)
                                    <tr>
                                        <td>{{ $club->getHome()->getName() }}</td>
                                        <td>{{ $club->getHome()->getStanding()->getGoalFor() }}
                                            - {{ $club->getAway()->getStanding()->getGoalFor() }}</td>
                                        <td>{{ $club->getAway()->getName() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
