"" 
<!DOCTYPE html>
<html>
<body>
    <h1>✅ Admin Dashboard Works!</h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>