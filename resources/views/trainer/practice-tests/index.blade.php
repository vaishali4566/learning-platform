@extends('layouts.trainer.index')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Practice Tests</h3>
        <a href="{{ route('practice-tests.create') }}" class="btn btn-primary">+ Add Practice Test</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Lesson</th>
                <th>Title</th>
                <th>Total Questions</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($tests as $test)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $test->lesson->title ?? 'N/A' }}</td>
                <td>{{ $test->title }}</td>
                <td>{{ $test->total_questions }}</td>
                <td>
                    <a href="{{ route('practice-tests.show', $test->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('practice-tests.edit', $test->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('practice-tests.destroy', $test->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this test?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tests->links() }}
</div>
@endsection
