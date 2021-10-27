<template>
  <div>
    <H1>Welcome</H1>
    <p>Hello you, welcome to your first Inertia app!</p>
    {{ palettes }}

    <button type="button" class="block mt-4 bg-indigo-500 text-white font-bold px-4 py-2 text-2xl uppercase rounded tracking-wider focus:outline-none hover:bg-blue-600"> Démarrer le traitement</button>

  </div>
</template>

<script>

  import Layout from '../Shared/Layout'

  export default {

    // Using the shorthand
    layout: Layout,

    props: {
      palettes: Array,
    },

  }
</script>