<template>
  <div class="w-full flex flex-col h-full text-center pt-4">

    Sélection des palettes

    <div class="flex items-center justify-center text-4xl text-center w-full mt-2">

      <!-- PREVIOUS CHEVRON -->
      <inertia-link v-if="palettes.prev_page_url" :href="palettes.prev_page_url">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </inertia-link>
      <div v-else>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </div>

      <!-- CURRENT PALETTE -->
      <div v-for="palette in palettes.data" :key="palette.id" class="flex-1">

        <button @click="select(palette)" class="border rounded p-4 border-gray-400">
          {{ palette.number }}
        </button>
        <span v-if="palette.bath.length > 0" class="block text-sm text-yellow-500 mt-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          Traitée le {{ palette.bath[0].created_at_formated }}
        </span>
        <span v-else class="block text-sm text-green-500 mt-1">non traitée</span>

      </div>

      <!-- NEXT CHEVRON -->
      <inertia-link v-if="palettes.next_page_url" :href="palettes.next_page_url">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </inertia-link>
      <div v-else>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </div>

    </div>

    <div v-if="selected.length > 0" class="flex flex-1 flex-col overflow-x-auto items-center justify-center border-t border-b my-8">

      Palettes sélectionées :

      <div class="flex items-center justify-center mt-2">

        <div v-for="(palette, index) in selected" :key="palette.id" @click="deselect(index)" class="cursor-pointer flex border p-4 items-center justify-center text-2xl">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          {{ palette.number}}
        </div>

      </div>

    </div>

    <div v-else class="flex flex-1 items-center justify-center">Aucune palette sélectionnée.</div>

    <div class="text-center" v-if="selected.length > 0">
      <div class="flex flex-col items-center justify-center">

        <label for="mode" class="flex items-center cursor-pointer mr-4">
          <div class="relative">
            <input id="mode" type="checkbox" v-model="mode" class="sr-only" />
            <div class="w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
            <div class="dot absolute w-6 h-6 bg-white rounded-full shadow -left-1 -top-1 transition"></div>
          </div>
          <div class="ml-3 text-gray-700 font-medium">
            Mode automatique
          </div>
        </label>

        <span v-if="mode" class="text-sm animate-pulse">En attente du capteur de déclenchement...</span>
        <span v-else class="text-sm text-yellow-500">Mode automatique desactivé</span>
      </div>

      <button type="button" @click="start" class="block w-full mt-6 border-t bg-green-400 border-green-600 text-white font-bold px-4 py-6 text-4xl uppercase tracking-wider focus:outline-none hover:bg-blue-2000"> Démarrage manuel</button>
    </div>

  </div>
</template>

<script>

  import Layout from '@/Shared/Layout'
  import Pagination from '@/Shared/Pagination'

  export default {

    layout: Layout,

    props: {
      default: Array,
      palettes: Object,
    },

    components: {
      Pagination,
    },

    data() {
      return {
        selected: this.default,
        mode: true,
      }
    },

    mounted() {

      // If none selected get default palettes
      this.selected = (this.$store.state.palettes.length >= 1) ? this.$store.state.palettes : this.default;

      this.listen();

    },

    watch: {
      mode: function (newMode, oldMode) {
        if(newMode)
          this.listen();
        else
          this.stopListening();
      }
    },

    methods: {
      
      select(palette) {
        this.selected.push(palette);
        this.$store.commit('selected', this.selected);
      },

      deselect(index) {
        this.selected.splice(index, 1);
        this.$store.commit('selected', this.selected);
      },

      start() {
        let form = this.$inertia.form({'palettes': this.selected});
        form.post(route('baths.store'));
      },

      listen() {
        console.log('Welcome : Start listening from door.')
        this.sockets.subscribe('door', (data) => {
            this.door = data.door;
            if(this.door && this.selected.length > 0) this.start();
        });
      },

      stopListening() {
        console.log('Welcome : Stop listening from door.')
        this.sockets.unsubscribe('door');
      },

    }

  }
</script>


<style scoped>

  input:checked ~ .dot {
    transform: translateX(100%);
    background-color: #48bb78;
  }

</style>