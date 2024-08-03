<div x-data="State()"
x-init="
Notification.requestPermission().then((result)=>{
    if(result === 'granted'){
        if ('serviceWorker' in navigator) {
            registerDevice()
            navigator.serviceWorker.register('/serviceworker.js').then(function(registration) {
                console.log('Service Worker registered with scope:', registration.scope);
            }).catch(function(error) {
                console.log('Service Worker registration failed:', error);
            });
        }
    }
});


">
</div>
<script>
    function State() {
        return {
            isDeviceRegistered: async function () {
                let deviceId = await localforage.getItem('deviceId');
                return deviceId !== null;
            },
            registerDevice: async function () {
                if (!await this.isDeviceRegistered()) {
                    const registerRequest = await fetch('/notification-devices/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    if (registerRequest.ok) {
                        const deviceId = await registerRequest.json();
                        await localforage.setItem('deviceId', deviceId.device_id);
                        console.log('Device registered with the server');
                    } else {
                        throw new Error('Device registration failed');
                    }

                } else {
                    console.log('Device already registered with the server');
                }
            }
        };
    }

</script>
