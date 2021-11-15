<template>
  <div class="w-full flex flex-col h-full text-center pt-4">

    <p class="pb-4">La station n'a pas de compte enregistré. Vous devez synchroniser votre compte depuis le manager.</p>

    <p>Connexion avec le manager : <span v-if="websocketStatus">OK</span><span v-else>Pas de connexion</span></p>

  </div>
</template>

<script>

  export default {

    data() {
      return {
        form: null,
        websocketStatus: false,
      }
    },

    sockets:  {
      connect: function() {
        console.log("socket connected");
        this.websocketStatus = true;
        this.listen();
      },
      disconnect: function() {
        console.log("socket disconnected");
        this.websocketStatus = false;
        this.stopListening();
      }
    },

    methods: {

      listen() {

        this.sockets.subscribe('/syncFromManager', (data) => {
            this.synchronize(data);
        });

      },

      stopListening() {

        this.sockets.unsubscribe('/syncFromManager');

      },

      synchronize(data) {

        console.log(data);

        // Push data on device
        fetch(route('api.synchronize'), {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
          console.log(data);
          this.$socket.emit('syncFromDevice', data);
          window.location.href = "/";
        });

      },

    },
    
  }

</script>
