@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col" style="padding-top: 20px;">
            <div class="card">
                <div class="card-header">
                    <b>
                        {{ $data->getGroup()->getName() }}
                    </b>
                </div>
                <div class="card-body">
                    <div class="col">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Club</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->getClubs() as $club)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $club->getName() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form action="{{ route('fixtures.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $data->getGroup()->getId() }}">
                            <input type="submit" value="Generate Fixtures" class="btn btn-info">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
