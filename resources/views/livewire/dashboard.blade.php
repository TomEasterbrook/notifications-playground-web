<div x-data="State()">
    <div x-show="!deviceRegistered">
        <button @click="enableNotifications()">Enable Notifications</button>
    </div>
    <div x-show="deviceRegistered">
        <button @click="removeDevice()">Disable Notifications</button>
    </div>
</div>
<script>
    function State() {
        return {
            deviceRegistered: false,
            enableNotifications: async function () {
                Notification.requestPermission().then((result)=>{
                    if(result === 'granted'){
                        if ('serviceWorker' in navigator) {
                            this.registerDevice()
                            navigator.serviceWorker.register('/serviceworker.js').then(function(registration) {
                                console.log('Service Worker registered with scope:', registration.scope);
                            }).catch(function(error) {
                                console.log('Service Worker registration failed:', error);
                            });
                        }
                    }
                });
            },
            isDeviceRegistered: async function () {
                let deviceId = await localforage.getItem('deviceId');
                this.deviceRegistered = deviceId !== null;
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
                this.deviceRegistered = true;
            },
            removeDevice: async function () {
                if (await this.isDeviceRegistered()) {
                    const deviceId = await localforage.getItem('deviceId');
                    const removeRequest = await fetch('/notification-devices/remove/', {
                        method: 'DELETE',
                        body: JSON.stringify({device_id: deviceId}),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    if (removeRequest.ok) {
                        await localforage.removeItem('deviceId');
                        navigator.serviceWorker.getRegistrations().then(function(registrations) {
                            for(let registration of registrations) {
                                registration.unregister();
                            }
                        });
                        console.log('Device removed from the server');
                    } else {
                        throw new Error('Device removal failed');
                    }
                } else {
                    console.log('Device not registered with the server');
                }
                this.deviceRegistered = false;
            }

        };
    }

</script>
