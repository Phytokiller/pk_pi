<template>
  <div class="text-white">

    <div class="flex flex-col">

        <div v-for="(sensor, index) in sensors" class="py-4 border-b border-indigo-400 w-40">
          {{ index }} : {{ sensor }}
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
      websocketStatus: false,
      sensors: null,
      door: false,
      boiler: false,
    }
  },

  methods: {

    listen() {

      this.sockets.subscribe('sensors', (data) => {
          this.sensors = data;
      });
      this.sockets.subscribe('door', (data) => {
          this.door = data;
      });
      this.sockets.subscribe('boiler', (data) => {
          this.boiler = data;
      });

    },

  },
  
}
</script>
