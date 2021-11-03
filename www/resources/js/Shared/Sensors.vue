<template>
  <div class="text-white">

    <div class="flex flex-col text-2xl">

        <div class="py-4 px-4 border-b border-indigo-400" :class="{'bg-red-400 animate-pulse': errors.T1}">
          T1:  {{ sensors.T1 }}°C
        </div>

        <div class="py-4 px-4 border-b border-indigo-400" :class="{'bg-red-400 animate-pulse': errors.T2}">
          T2:  {{ sensors.T2 }}°C
        </div>

        <div class="py-4 px-4 border-b border-indigo-400">
          <span v-if="door" class="rounded-full inline-block mr-2 w-3 h-3 bg-green-500"/>
          <span v-else class="rounded-full inline-block mr-2 w-3 h-3 bg-red-400"/>
          Capot
        </div>

        <div class="py-4 px-4 border-b border-indigo-400">
          <span v-if="boiler" class="rounded-full inline-block mr-2 w-3 h-3 bg-green-500"/>
          <span v-else class="rounded-full inline-block mr-2 w-3 h-3 bg-red-400"/>
          Chaudière
        </div>

    </div>

  </div>
</template>

<script>

export default {

  data() {
    return {
      bath_temperature: this.$page.props.account.bath_temperature,
      websocketStatus: false,
      sensors: null,
      door: false,
      boiler: false,
      errors: {
        T1: false,
        T2: false,
      }
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

      this.sockets.subscribe('sensors', (data) => {
          this.sensors = data;
          this.errors.T1 = (this.sensors.T1 > this.bath_temperature + 2 || this.sensors.T1 < this.bath_temperature - 2) ? true : false;
          this.errors.T2 = (this.sensors.T2 > this.bath_temperature + 2 || this.sensors.T2 < this.bath_temperature - 2) ? true : false;
      });
      this.sockets.subscribe('door', (data) => {
          this.door = data.door;
      });
      this.sockets.subscribe('boiler', (data) => {
          this.boiler = data.boiler;
      });

    },

    stopListening() {

      this.sockets.unsubscribe('sensors');
      this.sockets.unsubscribe('door');
      this.sockets.unsubscribe('boiler');

    },

  },
  
}
</script>
