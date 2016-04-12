<template>
  <div>
    <button @click="toggle" class="btn btn-primary" />
      <i class="fa fa-calendar"></i>
    </button>

    <modal size="medium" :show.sync="visible" header="true" footer="true">
      <div slot="header">
        Select Date &amp; Time
      </div>
      <div slot="body">
        <div class="row">
          <div class="col-sm-6">
            <calendar :model.sync="date" format="YYYY-MM-DD"></calendar>
          </div>
          <div class="col-sm-6">
            <time :model.sync="time"></time>
          </div>
        </div>
      </div>
      <div slot="footer">

        <button class="btn btn-primary" @click="set">Set Date &amp; Time</button>
        <button class="btn btn-default" @click="visible = false">Cancel</button>
      </div>
    </modal>
  </div>
</template>

<script>
import moment from 'moment';
import calendar from './calendar.vue';
import time from './time.vue';
import Modal from '../modal.vue';

module.exports = {

  props: ['model', 'heading', 'status', 'timeFormat'],

  components: {
    calendar, 
    time,
    Modal
  },

  data() {
    return {
      visible: false,
      moment: '',
      date: '',
      time: ''
    }
  },



  methods: {

    toggle() {
      this.visible = !this.visible;
      if (this.visible) {
        if (this.model) {
          this.date = this.model.split(' ')[0]; 
          this.time = this.model.split(' ')[1]; 
        }
      }
    },

    set() {
      let m = moment(this.date);

      let t = this.time.split(':');

      console.log(t);

      m.set({
        hours: t[0],
        minutes: t[1],
        seconds: 0
      });

      this.model = m.format(this.timeFormat);
      this.visible = false;
      this.$emit('save');
    }
  },

  created() {    
  },

  ready() {


  }

}
</script>