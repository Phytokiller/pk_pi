<template>
  <div class="w-full flex flex-col h-full text-center">

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
        <span v-if="palette.baths.length < 1" class="block text-sm">non traitée</span>
        <span v-else class="block text-sm text-red-600">déjà traitée</span>

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
      <span class="text-sm">En attente du capteur de déclenchement...</span>
      <button type="button" class="block w-full mt-6 border bg-indigo-300 border-indigo-600 text-white font-bold px-4 py-2 text-4xl uppercase rounded tracking-wider focus:outline-none hover:bg-blue-2000"> Démarrage manuel</button>
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
      }
    },

    mounted() {

      this.selected = (this.$store.state.palettes.length >= 1) ? this.$store.state.palettes : this.default;

    },

    methods: {
      
      select(palette) {
        this.selected.push(palette);
        this.$store.commit('selected', this.selected);
      },

      deselect(index) {
        this.selected.splice(index, 1);
        this.$store.commit('selected', this.selected);
      }

    }

  }
</script>