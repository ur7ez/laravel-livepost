import './bootstrap';
import axios from "axios";

const form = document.getElementById('form');
const inputMessage = document.getElementById('input-message');
const listMessage = document.getElementById('list-messages');
const inputEmail = document.getElementById('input-email');
const inputPassword = document.getElementById('input-password');
const avatars = document.getElementById('avatars');
const spanTyping = document.getElementById('span-typing');

form.addEventListener('submit', function (e) {
    e.preventDefault();
    const userInput = inputMessage.value;

    axios.post('/chat-message', {
        message: userInput,
    }).then((res) => {
        inputMessage.value = '';
    })
});

// login the user
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

function login(email, password) {
    return fetch('sanctum/csrf-cookie', {
        headers: {
            'content-type': 'application/json',
            'accept': 'application/json',
        },
        credentials: "include",
    })
        .then(() => logout())
        .then(() => {
            // login user
            return request('/login', {
                method: "POST",
                body: JSON.stringify({
                    email: email,
                    password: password,
                })
            });
        })
        .then(() => {
            document.getElementById('section-login').classList.add('hide');
            document.getElementById('section-chat').classList.remove('hide');
        });
}
let usersOnline = [];

function userInitials(username) {
    const names = username.split(' ');
    return names.map((name) => name[0]).join('').toUpperCase();
}
function renderAvatars() {
    avatars.textContent = '';

    usersOnline.forEach((user) => {
        const span = document.createElement('span');
        span.textContent = userInitials(user.name);
        span.classList.add('avatar');
        avatars.append(span);
    })
}

function addChatMessage(name, message, color = 'black') {
    const li = document.createElement('li');
    li.classList.add('d-flex', 'flex-col');

    const span = document.createElement('span');
    span.classList.add('message-author');
    span.textContent = name;

    const messageSpan = document.createElement('span');
    messageSpan.textContent = message;
    messageSpan.style.color = color;

    li.append(span, messageSpan);
    listMessage.append(li);
}

document.getElementById('form-login').addEventListener('submit', function (e) {
    e.preventDefault();
    const email = inputEmail.value,
        password = inputPassword.value;

    login(email, password)
        .then(() => {

//            updatePost();
/*
            // subscribe to WS channel
            // const channel = Echo.channel('public.chat.1');   // public channel
            // const channel = Echo.private('private.chat.1');  // private channel: no tack of subscribed users
            const channel = Echo.join('presence.chat.1');  // presence channel: keeps track of all the subscribing clients

            inputMessage.addEventListener('input', function (e) {
                if (inputMessage.value.length === 0) {
                    channel.whisper('stop-typing', {});
                } else {
                    channel.whisper('typing', {
                        email: email,
                    });
                }
            });

            channel.here((users) => {  // channel.subscribed() for public & private channels
                usersOnline = [...users];
                renderAvatars();
                console.log('subscribed users:', {users});
            })
                .joining((user) => {
                    console.log({user}, 'joined');
                    usersOnline.push(user);
                    renderAvatars();
                    addChatMessage(user.name, 'has joined the room!');
                })
                .leaving((user) => {
                    console.log({user}, 'leaving');
                    usersOnline = usersOnline.filter((userOnline) => userOnline.id !== user.id);
                    renderAvatars();
                    addChatMessage(user.name, 'has left the room.', 'grey');
                })
                .listen('.chat-message', (e) => {
                    console.log('event received: ', e);
                    addChatMessage(e.user.name, e.message);
                    spanTyping.textContent = '';
                })
                .listenForWhisper('typing', (event) => {
                    spanTyping.textContent = event.email + ' is typing...';
                })
                .listenForWhisper('stop-typing', (event) => {
                    spanTyping.textContent = '';
                });
            */
        });
});

updatePost();

function updatePost() {
    const socket = new WebSocket(
        `ws://${window.location.hostname}:6001/socket/update-post?appKey=${import.meta.env.VITE_PUSHER_APP_KEY}`
    );
    socket.onopen = function (event) {
        console.log('on open!');


    };
}
