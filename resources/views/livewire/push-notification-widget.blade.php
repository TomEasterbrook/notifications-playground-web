<div x-data="State()"
     x-init="bootWidget()"
     class="fi-dropdown-header flex w-full gap-2 p-3 text-sm  fi-dropdown-header-color-gray fi-color-gray">
    <div x-show="!deviceRegistered" class="fi-dropdown-header-label flex-1 truncate text-start text-gray-700 dark:text-gray-200">
        <button @click="enableNotifications()">Enable Notifications</button>
    </div>
    <div x-show="deviceRegistered" class="fi-dropdown-header-label flex-1 truncate text-start text-gray-700 dark:text-gray-200">
        <button @click="removeDevice()">Disable Notifications</button>
    </div>
</div>
<script>
    function State() {
        return {
            deviceRegistered: false,
            bootWidget: async function () {
                await this.isDeviceRegistered();
            },
            enableNotifications: async function () {
                Notification.requestPermission().then((result) => {
                    if (result === 'granted') {
                        if ('serviceWorker' in navigator) {
                            this.registerDevice()
                            navigator.serviceWorker.register('/serviceworker.js').then(function (registration) {
                                console.log('Service Worker registered with scope:', registration.scope);
                            }).catch(function (error) {
                                console.log('Service Worker registration failed:', error);
                            });
                            navigator.serviceWorker.ready.then((registration) => {
                                registration.active.postMessage(
                                    "connect"
                                );
                            });
                        }
                    }
                });
            },
            isDeviceRegistered: async function () {
                let deviceId = await localforage.getItem('deviceId');
                this.deviceRegistered = deviceId !== null;
                return this.deviceRegistered;
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
                        navigator.serviceWorker.ready.then((registration) => {
                            registration.active.postMessage(
                                "disconnect"
                            );
                        });
                        navigator.serviceWorker.getRegistrations().then(registrations => {
                            for (const registration of registrations) {
                                registration.unregister();
                                console.log('Service Worker unregistered with scope:', registration.scope);
                            }
                        });
                    }
                    this.deviceRegistered = false;
                } else {
                    console.log('Device not registered with the server');
                    this.deviceRegistered = false;
                }
            }
        };
    }

</script>
