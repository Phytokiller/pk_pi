<template>

  <div class="w-full flex flex-col h-full text-center pt-4">

    <div class="flex items-center justify-center text-center w-full mt-2 text-2xl px-4">

      <div class="border p-2 flex-1 m-1 bg-white rounded">
        Durée du bain<br>
        {{ $page.props.account.bath_duration }} min
      </div>
      <div class="border p-2 flex-1 m-1 bg-white rounded" :class="{'bg-red-400 animate-pulse': errors.time}">
        Temps écoulé<br>
        {{ elapsed_time }}
      </div>
      <div class="border p-2 flex-none m-1 bg-white rounded">
        <span v-for="palette in palettes" :key="palette.id">{{ palette.number }}<br></span>
      </div>

    </div>

    <div class="flex flex-1 items-center justify-center px-4">
      <line-chart v-if="chart" :chart-data="chart" class="relative max-h-72"/>
    </div>

    <div class="text-center">

      <span class="text-sm animate-pulse">Traitement en cours...</span>

      <button type="button" @click="stop" class="block w-full mt-6 border-t bg-red-400 border-red-600 text-white font-bold px-4 py-2 text-4xl uppercase tracking-wider focus:outline-none hover:bg-blue-2000">Arret manuel</button>
    </div>

  </div>

</template>

<script>

  import Layout from '@/Shared/Layout'
  import LineChart from '@/Shared/LineChart'
  import moment from "moment/moment";
  moment.locale("fr");

  export default {

    layout: Layout,

    components: {
      LineChart,
    },

    props: {
      bath: Object,
      palettes: Array,
    },

    mounted() {
      this.listen();
      this.timer();
      this.measures();
    },

    data() {
      return {
        form: this.$inertia.form(),
        errors: {
          time: false,
        },
        chart: {
          labels: [],
          datasets: [
            {
                label: 'T1',
                data: [],
            },
            {
                label: 'T2',
                data: [],
            },
            {
                label: 'Chaudière',
                data: [],
            },
          ],
          options: {
            scales: {
              xAxis: {
                type: 'time',
              }
            }
          }
        },
        sensors: null,
        door: false,
        boiler: false,
        measuresInterval: null,
        timerInterval: null,
        counter: 0,
        elapsed_time: '00:00',
      }
    },

    methods: {

      alert() {
        // Event if something wrong
        // Sound
        // WS
        // Classes
      },

      listen() {

        this.sockets.subscribe('sensors', (data) => {
            this.sensors = data;
        });
        this.sockets.subscribe('door', (data) => {
            this.door = data.door;
            if(!this.door) this.stop();
        });
        this.sockets.subscribe('boiler', (data) => {
            this.boiler = data.boiler;
        });

      },

      updateChart() {
        this.chart.labels.push(this.elapsed_time);
        this.chart.datasets[0].data.push(this.sensors.T1);
        this.chart.datasets[1].data.push(this.sensors.T2);
        this.chart.datasets[2].data.push(this.boiler);
      },

      measures(every = 3) {
        this.measuresInterval = setInterval(function(){

          this.updateChart();

          axios.post(route('baths.measure', this.bath.id), {
            sensor_1: this.sensors.T1,
            sensor_2: this.sensors.T2,
            door: this.door,
            boiler: this.boiler,
            elapsed_time: this.elapsed_time
          });

        }.bind(this), every * 1000);
      },

      timer() {
        this.timerInterval = setInterval(function(){
          ++this.counter;
          this.elapsed_time = moment("2015-01-01").startOf('day').seconds(this.counter).format('mm:ss');
          this.errors.time = (this.elapsed_time > this.$page.props.account.bath_duration) ? true : false;
        }.bind(this), 1000);
      },

      stop() {

        // LOG
        console.log('Bath stopped.');

        // STOP INTERVALS
        clearInterval(this.timerInterval);
        this.timerInterval = null;
        clearInterval(this.measuresInterval);
        this.measuresInterval = null;

        // RESET
        this.counter = 0;

        // UPDATE BATH
        this.form.put(route('baths.finish', this.bath.id));

      },

    },

    beforeDestroy() {
      if(this.timerInterval || this.measuresInterval)
        this.stop();
    },


  }
</script>