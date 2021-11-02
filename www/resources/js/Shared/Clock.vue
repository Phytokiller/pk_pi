<template>
  <div>{{ timestamp }}</div>
</template>

<script>

  import * as moment from "moment/moment";
  moment.locale("fr");

  export default {
    name: "Clock",
    data: function() {
      return {
        timestamp: null
      };
    },
    created() {
      setInterval(this.getNow, 1000);
    },
    methods: {
      getNow: function() {
        this.timestamp = moment().format("ddd D MMMM HH:mm");
      }
    }
  };

</script>