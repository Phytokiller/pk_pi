<template>
  <div class="text-white">

    <div class="flex flex-col text-2xl" v-if="websocketStatus && sensors">

        <div class="py-4 px-4 border-b border-indigo-400" :class="{'bg-red-400 animate-pulse': errors.T1}">
          T1:  {{ sensors.T1 | decimal }}°C
        </div>

        <div class="py-4 px-4 border-b border-indigo-400" :class="{'bg-red-400 animate-pulse': errors.T2}">
          T2:  {{ sensors.T2 | decimal }}°C
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

    <div class="flex flex-col h-full text-2xl justify-center items-center" v-else>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
      </svg>
    </div>

  </div>
</template>

<script>

export default {

  data() {
    return {
      form: null,
      bath_temperature: this.$page.props.account.bath_temperature,
      websocketStatus: false,
      sensors: null,
      door: false,
      boiler: false,
      errors: {
        T1: false,
        T2: false,
        temp: {}, 
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

  filters: {
    decimal: function (value) {
      return parseFloat(value).toFixed(1);
    }
  },

  methods: {

    checkAlarms() {

      let limit = 2;

      this.errors.T1 = (this.sensors.T1 > this.bath_temperature + limit);
      this.errors.T2 = (this.sensors.T1 > this.bath_temperature + limit);

      this.errors.temp.toHigh = (this.errors.T1 || this.errors.T2);

      this.errors.temp.toLow = (this.sensors.T2 < this.bath_temperature - limit) ? true : false;
      this.errors.temp.toLow = (this.sensors.T2 < this.bath_temperature - limit) ? true : false;
      this.errors.temp.diff = ((this.sensors.T1 - this.sensors.T2) > limit || (this.sensors.T1 - this.sensors.T2) < limit) ? true : false;

      this.$socket.emit('alarm', {
        temp: {
          toHigh: this.errors.temp.toHigh, 
          toLow: this.errors.temp.toLow, 
          diff: this.errors.temp.diff,
        },
      });

    },

    listen() {

      this.sockets.subscribe('sensors', (data) => {
          this.sensors = data;
          this.checkAlarms();
      });

      this.sockets.subscribe('door', (data) => {
          this.door = data.door;
      });

      this.sockets.subscribe('boiler', (data) => {
          this.boiler = data.boiler;
      });

      this.sockets.subscribe('/syncFromManager', (data) => {
          this.synchronize(data);
      });

      this.sockets.subscribe('/setSettings', (data) => {
        this.bath_temperature = data.Tboiler;
      });

      this.sockets.subscribe('settings', (data) => {
        this.bath_temperature = data.Tboiler;
      });

      this.$socket.emit('getSettings', {getSettings: true});

    },

    stopListening() {

      this.sockets.unsubscribe('sensors');
      this.sockets.unsubscribe('door');
      this.sockets.unsubscribe('boiler');
      this.sockets.unsubscribe('/syncFromManager');
      this.sockets.unsubscribe('/setSettings');

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
      });

    },

  },
  
}
</script>
