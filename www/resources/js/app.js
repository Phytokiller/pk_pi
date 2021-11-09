require('./bootstrap');

import Vue from 'vue'
import PortalVue from 'portal-vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue'
import io from 'socket.io-client'
import VueSocketIO from 'vue-socket.io';
import { store } from './store'

Vue.mixin({ methods: { route: window.route } })

Vue.use(PortalVue)

Vue.use(
  new VueSocketIO({
    debug: process.env.APP_DEBUG,
    connection: io('http://phytokiller.local:5000', {
      transports: ["websocket", "polling", "flashsocket"]
    }),
  })
);

InertiaProgress.init()

Vue.component('inertia-link', Link)

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props }) {
    new Vue({
      store,
      render: h => h(App, props),
    }).$mount(el)
  },
})