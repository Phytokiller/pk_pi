<template>
  <div>
    <portal-target name="dropdown" slim />
    <div class="md:flex md:flex-col">
      <div class="md:h-screen md:flex md:flex-col">
        <div class="md:flex md:flex-shrink-0" v-if="!running">
          <div class="bg-indigo-900 md:flex-shrink-0 md:w-56 px-6 py-4 flex items-center justify-between md:justify-center">
            <inertia-link class="mt-1 text-white" href="/">
              <logo class="fill-white inline-block"/> Station
            </inertia-link>
          </div>
          <div class="bg-white border-b w-full p-4 md:py-0 md:px-12 text-sm md:text-md flex justify-between items-center">

            <div class="mt-1 mr-4">

              <div class="flex items-center">

                <dropdown v-if="$page.props.accounts.length > 1" class="mt-1" placement="bottom-end">
                  <div class="flex items-center cursor-pointer select-none group">
                    <div class="text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 mr-1 whitespace-nowrap">
                      <span class="font-semibold text-lg">{{ $page.props.account.name }}</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                  <div slot="dropdown" class="mt-2 py-2 shadow-xl bg-white rounded text-sm">
                    <inertia-link v-for="account in $page.props.accounts" :key="account.id" v-if="account.id != $page.props.account.id" class="text-left block w-full px-6 py-2 hover:bg-indigo-500 hover:text-white" :href="route('accounts.switch', account.id)" method="put" as="button">{{ account.name }}</inertia-link>
                  </div>
                </dropdown>

                <span v-else class="font-semibold text-lg">{{ $page.props.account.name }}</span>

              </div>

            </div>

            <div class="flex justify-end items-center">

              <dropdown v-if="$page.props.users.length > 1" class="mt-1" placement="bottom-end">
                <div class="flex items-center cursor-pointer select-none group">
                  <div class="text-gray-700 group-hover:text-indigo-600 focus:text-indigo-600 mr-1 whitespace-nowrap">
                    <span class="font-semibold text-lg">{{ $page.props.user.name }}</span>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
                <div slot="dropdown" class="mt-2 py-2 shadow-xl bg-white rounded text-sm">
                  <inertia-link v-for="user in $page.props.users" :key="user.id" v-if="user.id != $page.props.user.id" class="text-left block w-full px-6 py-2 hover:bg-indigo-500 hover:text-white" :href="route('users.switch', user.id)" method="put" as="button">{{ user.name }}</inertia-link>
                </div>
              </dropdown>

              <span v-else class="font-semibold text-lg">{{ $page.props.user.name }}</span>

            </div>

          </div>
        </div>

        <div class="md:flex md:flex-grow md:overflow-hidden">
          <div class="bg-indigo-800 flex-shrink-0 w-56 p-2 overflow-y-auto flex flex-col">
            <sensors class="flex-1"/>
            <clock class="text-white text-center"/>
            <inertia-link v-if="!running" :href="route('settings')" class="text-white py-4 px-4 mt-4 border-t border-indigo-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
              </svg> Paramètres
            </inertia-link>

          </div>
          <div class="md:flex-1 overflow-y-auto flex flex-col items-center justify-center" scroll-region>
            <flash-messages />
            <slot/>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>

import Logo from '@/Shared/Logo'
import Sensors from '@/Shared/Sensors'
import Clock from '@/Shared/Clock'
import Dropdown from '@/Shared/Dropdown'
import FlashMessages from '@/Shared/FlashMessages'

export default {

  components: {
    Dropdown,
    FlashMessages,
    Logo,
    Sensors,
    Clock,
  },

  computed: {
    running () {
      return this.$store.state.running
    }
  },

/*
  sockets:  {
    connect: function() {
      this.sockets.subscribe('/syncFromManager', (data) => {
          this.synchronize(data);
      });
    },
    disconnect: function() {
      this.sockets.unsubscribe('/syncFromManager');
    }
  },
*/

  methods: {

/*
    synchronize(data) {

      console.log(data);

      // Push data on device
      fetch(route('api.synchronize'), {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(data => {
        console.log(data);
        this.$socket.emit('syncFromDevice', data);
      });

    },
*/

  }
  
}
</script>
