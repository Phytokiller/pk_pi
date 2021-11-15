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
      <canvas id="bathChart"></canvas>
    </div>

    <div class="text-center">

      <span class="text-sm animate-pulse">Traitement en cours...</span>

      <button type="button" @click="stop" class="block w-full mt-6 border-t bg-red-400 border-red-600 text-white font-bold px-4 py-2 text-4xl uppercase tracking-wider focus:outline-none hover:bg-blue-2000">Arret manuel</button>
    </div>

  </div>

</template>

<script>

  import Layout from '@/Shared/Layout'

  import moment from "moment/moment";
  moment.locale("fr");

  import { Chart, registerables } from "chart.js";
  Chart.register(...registerables);

  export default {

    layout: Layout,

    components: {
    },

    props: {
      bath: Object,
      palettes: Array,
    },

    mounted() {
      this.listen();
      this.timer();
      this.measures();
      this.initChart();
      this.$socket.emit('stop', true);
    },

    data() {
      return {
        form: this.$inertia.form(),
        errors: {
          time: false,
        },
        chart: null,
        sensors: null,
        door: false,
        boiler: false,
        measuresInterval: null,
        timerInterval: null,
        counter: 0,
        elapsed_time: '00:00',
        bath_duration: moment("2015-01-01").startOf('day').minutes(this.$page.props.account.bath_duration).format('mm:ss'),
      }
    },

    methods: {

      alert() {
        this.$socket.emit('alarm', {temp: false, timeout: true});
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
        this.chart.data.labels.push(this.elapsed_time);
        this.chart.data.datasets[0].data.push(this.sensors.T1);
        this.chart.data.datasets[1].data.push(this.sensors.T2);
        this.chart.data.datasets[2].data.push(this.boiler);
        this.chart.update();
      },

      measures(every = 30) {
        this.measuresInterval = setInterval(function(){

          this.updateChart();

          if (this.bath) {
            axios.post(route('baths.measure', this.bath.id), {
              sensor_1: this.sensors.T1,
              sensor_2: this.sensors.T2,
              door: this.door,
              boiler: this.boiler,
              elapsed_time: this.elapsed_time
            });
          }

        }.bind(this), every * 1000);
      },

      timer() {

        this.timerInterval = setInterval(function(){

          ++this.counter;

          this.elapsed_time = moment("2015-01-01").startOf('day').seconds(this.counter).format('mm:ss');

          this.$socket.emit('processing', {
            'palettes': this.palettes,
            'bath': this.bath.number,
            'elapsed_time': this.elapsed_time,
          });

          this.errors.time = (this.elapsed_time > this.bath_duration) ? true : false;

          if(this.errors.time) this.alert(); 

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
        if(this.bath)
          this.form.put(route('baths.finish', this.bath.id));

        this.$socket.emit('stop', true);

      },

      initChart() {
        // CHART
        var ctx = document.getElementById("bathChart");
        this.chart = new Chart(ctx, {
          type: "line",
          data: {
            labels: [],
            datasets: [
              {
                label: "T1",
                yAxisID: 'A',
                data: [],
                borderColor: "#B5EAEA",
                backgroundColor: "#B5EAEA",
                tension: 0.4
              },
              {
                label: "T2",
                yAxisID: 'A',
                data: [],
                borderColor: "#FFBCBC",
                backgroundColor: "#FFBCBC",
                tension: 0.4,
              },
              {
                  label: "Chaudière",
                  yAxisID: 'B',
                  data: [],
                  borderColor: '#CCC',
                  borderDash: [10,5],
                  pointRadius: 0,
                  fill: true,
                  tension: 0.4,
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              A: {
                  type: 'linear',
                  position: 'left',
              },
              B: {
                  type: 'linear',
                  beginAtZero: true,
                  position: 'right',
                  ticks: {
                      max: 1,
                      min: 0
                  }
              }
            }
          }
        });
      },


    },

    beforeDestroy() {
      if(this.timerInterval || this.measuresInterval)
        this.stop();
    },


  }
</script>