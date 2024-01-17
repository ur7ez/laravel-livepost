<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/ws.css', 'resources/css/helper.css', 'resources/js/app.js'])
    <title>WS chat</title>
</head>
<body>
<main class="d-flex align-center h-100vh">
     <section id="section-login" class="d-flex flex-col justify-center align-center mx-auto card">
        <form id="form-login" class="d-flex flex-col">
            <h2>Login</h2>
            <input id="input-email" type="email" placeholder="Email">
            <input id="input-password" type="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>
    </section>

    <section id="section-chat" class="d-flex flex-col justify-between align-center card mx-auto h-80 hide">
        <nav id="nav-online" class="w-100 d-flex">
            <h3 class="white pl-1">Chat</h3>
            <div id="avatars"></div>
        </nav>
        <ul id="list-messages" class="d-flex flex-col px-1">
        </ul>
        <form id="form" class="w-100 d-flex flex-col">
            <span class="pl-1" id="span-typing"></span>
            {{--            <label for="input-message">Message:</label>--}}
            <input id="input-message" type="text" class="py-2 pl-1" placeholder="Type a message">
        </form>
    </section>
</main>

</body>
</html>
