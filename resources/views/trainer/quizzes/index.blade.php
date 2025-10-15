<h1>All Quizzes (Trainer)</h1>
<a href="{{ route('trainer.quizzes.create') }}">Create New Quiz</a>
<ul>
@foreach($quizzes as $quiz)
    <li>
        {{ $quiz->title }} - <a href="{{ route('trainer.quizzes.edit', $quiz->id) }}">Edit</a>
    </li>
@endforeach
</ul>
