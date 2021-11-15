<template>
  <div class="w-full flex flex-col h-full text-center pt-4">

    La station n'a pas de compte enregistré. Vous devez synchroniser votre compte depuis le manager.

  </div>
</template>

<script>

  import Layout from '@/Shared/Layout'

  export default {

    layout: Layout,

    data() {
      return {
        selected: this.default,
      }
    },

    mounted() {


    },

    methods: {
      

    }

  }
</script>