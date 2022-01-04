import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export const store = new Vuex.Store({

  state () {
      return {
          palettes: [],
          running: false,
      }
  },

  mutations: {

    selected(state, palettes) {
      state.palettes = palettes
    },

    running(state, status) {
      state.running = status
    }

  }
  
})