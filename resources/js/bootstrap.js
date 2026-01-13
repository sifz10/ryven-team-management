import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

// Initialize Echo with Reverb (local WebSocket) for local development
console.log('🔌 Initializing Echo with Reverb...');
console.log('Reverb Config:', {
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: import.meta.env.VITE_REVERB_HOST,
    port: import.meta.env.VITE_REVERB_PORT,
    scheme: import.meta.env.VITE_REVERB_SCHEME,
});

try {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        encrypted: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        disableStats: true,
    });
    console.log('✅ Echo initialized successfully with Reverb');
    
    // Monitor connection state
    if (window.Echo.connector && window.Echo.connector.socket) {
        window.Echo.connector.socket.on('connecting', () => {
            console.log('🔄 Reverb: connecting...');
        });
        window.Echo.connector.socket.on('connected', () => {
            console.log('✅ Reverb: CONNECTED');
        });
        window.Echo.connector.socket.on('disconnected', () => {
            console.log('❌ Reverb: disconnected');
        });
        window.Echo.connector.socket.on('error', (error) => {
            console.error('❌ Reverb error:', error);
        });
    }
} catch (error) {
    console.error('❌ Failed to initialize Echo:', error);
}
