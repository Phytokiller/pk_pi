import Vue from 'vue'
import PortalVue from 'portal-vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue'
import io from 'socket.io-client'
import VueSocketIO from 'vue-socket.io';

Vue.mixin({ methods: { route: window.route } })

Vue.use(PortalVue)

Vue.use(
  new VueSocketIO({
    debug: true,
    connection: io(process.env.MIX_PI_WS_CONNEXION, {
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
      render: h => h(App, props),
    }).$mount(el)
  },
})