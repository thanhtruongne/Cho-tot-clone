<form method="POST" action="{{ url('password/reset') }}">
    @csrf
    <input type="hidden" name="email" value="{{ $user->email }}">
    <div>
        <label for="password">New Password</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm New Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>
    <button type="submit">Reset Password</button>
</form>
