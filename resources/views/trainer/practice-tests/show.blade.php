@extends('layouts.trainer.index')

@section('content')
<div class="container mt-4">
    <h3>{{ $test->title }}</h3>
    <p>Total Questions: {{ $questions->count() }}</p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>A</th>
                <th>B</th>
                <th>C</th>
                <th>D</th>
                <th>Correct</th>
            </tr>
        </thead>

        <tbody>
            @foreach($questions as $q)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $q->question }}</td>
                <td>{{ $q->option_a }}</td>
                <td>{{ $q->option_b }}</td>
                <td>{{ $q->option_c }}</td>
                <td>{{ $q->option_d }}</td>
                <td>{{ strtoupper($q->correct_option) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
