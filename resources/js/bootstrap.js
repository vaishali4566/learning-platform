import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// ------------------
// Axios Configuration
// ------------------
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ------------------
// Laravel Echo (Reverb) Setup
// ------------------
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb', // use Reverb as broadcaster
    key: import.meta.env.VITE_REVERB_APP_KEY, // from .env
    wsHost: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_REVERB_PORT || 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT || 8080,
    forceTLS: import.meta.env.VITE_REVERB_SCHEME === 'https',
    enabledTransports: ['ws', 'wss'], // WebSocket only
});
