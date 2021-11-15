<template>
  <div class="w-full flex flex-col h-full text-center p-4">

    {{ settings }}

    <div class="flex items-center">

      <div class="mr-2">
        <label class="block mb-2">Offset T1</label>
        <vue-number-input v-model="form.T1offset" :step="0.1" inline controls size="large"></vue-number-input>
      </div>

      <div>
        <label class="block mb-2">Offset T2</label>
        <vue-number-input v-model="form.T2offset" :step="0.1" inline controls size="large"></vue-number-input>
      </div>

      <div>
        <label class="block mb-2">Température du bain</label>
        <vue-number-input v-model="form.Tboiler" :step="0.1" inline controls size="large"></vue-number-input>
      </div>

    </div>

    <button @click="updateSettings" type="button" class="p-3 bg-indigo-400 rounded text-white text-2xl">Mettre à jour</button>

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
          T1offset: this.settings.offset_T1,
          T2offset: this.settings.offset_T1,
          Tboiler: this.account.bath_temperature,
        }),
      }
    },

    mounted() {

      this.$socket.emit('getSettings', true);

      this.sockets.subscribe('settings', (data) => {
          this.form.T1offset = data.T1offset;
          this.form.T2offset = data.T2offset;
          this.form.Tboiler = data.Tboiler;
      });

    },

    methods: {

      updateSettings() {

        this.form.put(route('settings.update'), {
          preserveScroll: true,
          onSuccess: () => {
            this.$socket.emit('setSettings', this.form.data);
          },
        });

      },


    }

  }

</script>