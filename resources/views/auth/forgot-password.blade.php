<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>
    <button type="submit">Send Reset Link</button>
</form>

@if(session('status'))
    <p>{{ session('status') }}</p>
@endif

@if($errors->any())
    <p>{{ $errors->first() }}</p>
@endif
