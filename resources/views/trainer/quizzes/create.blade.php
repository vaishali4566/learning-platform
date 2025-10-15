<h1>Create Quiz</h1>
<form action="{{ route('trainer.quizzes.store') }}" method="POST">
    @csrf

    <input type="number" name="lesson_id" placeholder="Lesson ID" required><br>
    <input type="text" name="title" placeholder="Quiz Title" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>

    {{-- Total Marks and Passing Marks are calculated automatically --}}
    <p>Total Marks: 0 (will update after adding questions)</p>
    <p>Passing Marks (33%): 0 (will update after adding questions)</p>

    <button type="submit">Create Quiz</button>
</form>
