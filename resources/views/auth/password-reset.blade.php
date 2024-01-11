<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Password reset</title>
</head>
<body>

<form method="POST" action="{{route('password.update')}}">
    <input type="text" name="email" placeholder="Email">
    <input type="hidden" name="token" value="{{$token}}">
    <input type="text" name="password" placeholder="Password">
    <input type="text" name="password_confirmation" placeholder="Password confirm">
</form>

</body>
</html>

