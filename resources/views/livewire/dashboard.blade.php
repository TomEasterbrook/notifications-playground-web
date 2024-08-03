<div x-data="{}"
x-init="
Notification.requestPermission().then((result)=>{
    if(result === 'granted'){
        console.log('Notification permission granted');
    }
});
    Echo.channel('messages').listen('NewMessageEvent',(event)=>{
        console.log(event);
        new Notification(event.title,{body:event.message});
    });

">
</div>
<script>

</script>
