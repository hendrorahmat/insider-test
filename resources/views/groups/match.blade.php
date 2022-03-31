@extends('layouts.app')

@section('content')
    <div class="row">
        @foreach($matches as $match)
            <div class="col-4" style="padding-top: 20px;">
                <div class="card">
                    <div class="card-header">
                        <b>{{ $match->getName() }}</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
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
        @endforeach
    </div>
    <div class="row" style="padding-top: 20px;">
        <div class="col">
            <a href="{{ route('simulation.index', [$groupId]) }}" class="btn btn-primary">Start Simulation</a>
        </div>
    </div>
@endsection
