
importScripts('https://cdnjs.cloudflare.com/ajax/libs/pusher/8.3.0/pusher.worker.min.js');
importScripts('https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.16.1/echo.iife.min.js');
importScripts('https://cdnjs.cloudflare.com/ajax/libs/localforage/1.10.0/localforage.min.js');
const EchoClient = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
console.log(getDeviceId());
EchoClient.channel('messages').listen('NewMessageEvent',(event)=> {
    console.log(event);
    self.registration.showNotification(event.title, {
        body: event.message,
    });
});
async function getDeviceId() {
    let deviceId = await localforage.getItem('deviceId');
    console.log(deviceId);
}
