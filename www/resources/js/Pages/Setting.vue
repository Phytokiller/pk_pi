<template>
  <div class="w-full flex flex-col h-full p-4">

    <h1 class="text-2xl mb-4">Paramètres de la station</h1>

    <div class="flex items-center mb-4">

      <div class="mr-2">
        <label class="block mb-2">Offset T1 (°C)</label>
        <vue-number-input v-model="form.T1offset" :step="0.1" :min="-20" :max="20" inline controls size="large"></vue-number-input>
      </div>

      <div>
        <label class="block mb-2">Offset T2 (°C)</label>
        <vue-number-input v-model="form.T2offset" :step="0.1" :min="-20" :max="20" inline controls size="large"></vue-number-input>
      </div>

    </div>

    <div class="flex items-center mb-4">

      <div>
        <label class="block mb-2">Température chaudière</label>
        <vue-number-input v-model="form.Tboiler" :step="1" :min="0" :max="100" inline controls size="large"></vue-number-input>
      </div>

    </div>

    <div class="flex items-center mb-4">

      <div>
        <button @click="sendRebootCmd" type="button" class="mt-4 mr-4 p-3 bg-indigo-800 rounded text-white text-2xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Redémarrer la station
        </button>
        <button @click="sendUpdateCmd" type="button" class="mt-4 p-3 bg-indigo-800 rounded text-white text-2xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Mise à jour système
        </button>
      </div>

    </div>

    <button @click="updateSettings" type="button" class="mt-4 p-3 bg-indigo-800 rounded text-white text-2xl">Mettre à jour</button>

    <inertia-link :href="route('welcome')" class="mt-4 p-3 bg-gray-300 rounded text-2xl text-center">Fermer</inertia-link>

  </div>
</template>

<script>

  import Layout from '@/Shared/Layout'
  import VueNumberInput from '@chenfengyuan/vue-number-input';

  export default {

    layout: Layout,

    props: {
      settings: Object,
      account: Object,
    },

    components: {
      VueNumberInput
    },

    data() {
      return {
        form: this.$inertia.form({
          T1offset: parseFloat(this.settings.offset_T1),
          T2offset: parseFloat(this.settings.offset_T1),
          Tboiler: parseFloat(this.account.bath_temperature),
        }),
      }
    },

    mounted() {

      this.sockets.subscribe('settings', (data) => {

          this.form.T1offset = parseFloat(data.T1offset);
          this.form.T2offset = parseFloat(data.T2offset);
          this.form.Tboiler = parseFloat(data.Tboiler);
      });

      this.$socket.emit('getSettings', {getSettings: true});

    },

    methods: {

      updateSettings() {

        this.form.put(route('settings.update'), {
          preserveScroll: true,
          onSuccess: () => {
            this.$socket.emit('setSettings', this.form);
          },
        });

      },

      sendRebootCmd() {
        this.$socket.emit('system', { cmd: 'sudo reboot'} );
      },

      sendUpdateCmd() {
        this.$socket.emit('system', { cmd: 'sudo systemctl restart pk_server.service && sudo systemctl restart pk_client_ardbox.service && sudo systemctl restart pk_chromium.service'} );
      }


    }

  }

</script>