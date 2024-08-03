<div x-data="{}"
x-init="
Notification.requestPermission().then((result)=>{
    if(result === 'granted'){
        if ('serviceWorker' in navigator) {
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

</script>
