import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export const store = new Vuex.Store({

  state () {
      return {
          palettes: [],
      }
  },

  mutations: {

    selected(state, palettes) {
      state.palettes = palettes
    }

  }
  
})