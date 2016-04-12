<template>
    <div class="timepicker text-center">
      <div class="row"> 
        <h4>{{ hour }}:{{minute}} {{ampm}}</h4>
      </div>
      <div class="row">
        <div class="col-sm-4">
          <button class="btn btn-xs btn-primary" @click="changeTime(true, 'hour')"><i class="fa fa-angle-up"></i></button>
          <div>HOUR</div>
          <button class="btn btn-xs btn-primary" @click="changeTime(false, 'hour')"><i class="fa fa-angle-down"></i></button>
        </div>
        <div class="col-sm-4">
          <button class="btn btn-xs btn-primary" @click="changeTime(true, 'minute')"><i class="fa fa-angle-up"></i></button>
          <div>MIN</div>
          <button class="btn btn-xs btn-primary" @click="changeTime(false, 'minute')"><i class="fa fa-angle-down"></i></button>
        </div>
        <div class="col-sm-4">
           <button class="btn btn-xs btn-primary" @click="changeAmPm(true)"><i class="fa fa-angle-up"></i></button>
            <div>AM/PM</div>
          <button class="btn btn-xs btn-primary" @click="changeAmPm(false)"><i class="fa fa-angle-down"></i></button>
        </div>
      </div>
      <hr />
      <div class="row">
        <button class="btn btn-primary btn-xs" @click="now"><i class="fa fa-clock-o"></i> Now</button>
        <button class="btn btn-primary btn-xs" @click="topOfTheHour"><i class="fa fa-clock-o"></i> Top</button>
        &nbsp;&nbsp;
        <button class="btn btn-primary btn-xs" @click="add15()"><i class="fa fa-plus"></i> 15</button>
        <button class="btn btn-primary btn-xs" @click="sub15()"><i class="fa fa-minus"></i> 15</button>
      </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {

  props: ['model'],

  components: {
  },

  data() {
    return {
      moment: moment().format('YYYY-MM-DD HH:mm:ss')
    }
  },

  computed: {
    hour() {
      let m = moment(this.moment);
      return m.format('h')
    },
    minute() {
      let m = moment(this.moment);
      return m.format('mm')
    },
    ampm() {
      let m = moment(this.moment);
      return m.format('A')
    }
  },

  events: {
  },

  methods: {
    topOfTheHour() {
      let m = moment();

      let minutes = m.format('mm');
      let tillHour = 60 - minutes;

      m.add(tillHour, 'minutes');

      this.moment = m.format('YYYY-MM-DD HH:mm:ss')
      this.model = m.format('HH:mm:ss');
    },

    add15() {
      let m = moment(this.moment);

      m.add(15, 'minutes');

      this.moment = m.format('YYYY-MM-DD HH:mm:ss')
      this.model = m.format('HH:mm:ss');
    },

    sub15() {
      let m = moment(this.moment);

      m.subtract(15, 'minutes');

      this.moment = m.format('YYYY-MM-DD HH:mm:ss')
      this.model = m.format('HH:mm:ss');
    },

    now() {
      let m = moment();
      this.moment = m.format('YYYY-MM-DD HH:mm:ss')
      this.model = m.format('HH:mm:ss');
    },

    changeTime(forward, type) {
      let m = moment(this.moment);

      if (forward) {
        m.add(1, type);
      } else {;
        m.subtract(1, type)
      }

      this.moment = m.format('YYYY-MM-DD HH:mm:ss')
      this.model = m.format('HH:mm:ss');

    },

    changeAmPm(forward) {
      let m = moment(this.moment);

      if (parseInt(m.format('H')) < 12) {
        m.add('12', 'hours')
      } else {
        m.subtract('12', 'hours')
      }

      this.moment = m.format('YYYY-MM-DD HH:mm:ss')
      this.model = m.format('HH:mm:ss');

    },
  },

  created() {
  },

  ready() {
  }
}
</script>