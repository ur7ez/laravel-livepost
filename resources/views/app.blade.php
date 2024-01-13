<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Document</title>
</head>
<body>

<script>
    function getCookie(name, decodeURI = true) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) {
            let res = parts.pop().split(';').shift();
            return decodeURI ? decodeURIComponent(res) : res;
        }
    }

    function request(url, options) {
        // get cookie
        const csrfToken = getCookie('XSRF-TOKEN', true);
        return fetch(url, {
            headers: {
                'content-type': 'application/json',
                'accept': 'application/json',
                'X-XSRF-TOKEN': csrfToken,
            },
            credentials: "include",
            ...options,
        })
    }

    function logout() {
        return request('/logout', {
            method: "POST",
        });
    }

    function login() {
        return request('/login', {
            method: "POST",
            body: JSON.stringify({
                email: "testen@example.net",
                password: "password",
            })
        }).then(() => {
            // ..
        });
    }

    fetch('sanctum/csrf-cookie', {
        headers: {
            'content-type': 'application/json',
            'accept': 'application/json',
        },
        credentials: "include",
    })
        .then(() => logout())
        .then(() => login())
        .then(() => request('/api/v1/users'))
    ;
</script>

</body>
</html>
