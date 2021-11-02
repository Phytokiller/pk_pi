<template>
  
  <canvas :id="id" class="h-full w-full"></canvas>

</template>

<script>

  import { Chart, registerables } from 'chart.js';
  Chart.register(...registerables);

  export default ({

    data() {
        return {
          id: this._uid,
          chart: null,
        }
    },

    props: {
      chartData: Object,
    },

    watch: {
      chart: function (newChart, oldChart) {
        this.chart.update();
      }
    },

    mounted () {

      var ctx = document.getElementById(this.id);

      this.chart = new Chart(ctx, {
        type: 'line',
        data: this.chartData,
        options: {
        responsive: true,
          interaction: {
            mode: 'index',
            intersect: false,
          },
        }
      });
      
    },


  })

</script>