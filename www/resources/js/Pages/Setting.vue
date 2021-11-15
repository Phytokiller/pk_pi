<template>
  <div class="w-full flex flex-col h-full text-center p-4">

    {{ settings }}

    <div class="flex items-center">

      <div class="mr-2">
        <label class="block mb-2">Offset T1</label>
        <vue-number-input v-model="form.offset_t1" :step="0.1" inline controls size="large"></vue-number-input>
      </div>

      <div>
        <label class="block mb-2">Offset T2</label>
        <vue-number-input v-model="form.offset_t2" :step="0.1" inline controls size="large"></vue-number-input>
      </div>

    </div>

    <button type="button" class="p-3 bg-indigo-400 rounded text-white text-2xl">Mettre à jour</button>

  </div>
</template>

<script>

  import Layout from '@/Shared/Layout'
  import VueNumberInput from '@chenfengyuan/vue-number-input';

  export default {

    layout: Layout,

    props: {
      settings: Object,
    },

    components: {
      VueNumberInput
    },

    data() {
      return {
        form: this.$inertia.form(this.settings),
      }
    },

    mounted() {

      this.$socket.emit('setSettings', this.settings);

      console.log(this.settings);
      this.sockets.subscribe('settings', (data) => {
          //this.settings = data;
      });
    },

    methods: {

      updateSettings() {

      },


    }

  }

</script>