<template>
    <div class="calendar">
      <div class="month text-center"> 
        <h4>{{ month }} {{ year }}</h4>
      </div>

      <div class="text-center">
        <button @click="prev('month')" class="btn btn-primary"><i class="fa fa-angle-left"></i> Mo</button>      
        <button @click="next('month')" class="btn btn-primary">Mo <i class="fa fa-angle-right"></i> </button>
        &nbsp;&nbsp;
        <button @click="prev('year')" class="btn btn-primary"><i class="fa fa-angle-left"></i> Yr</button> 
        <button @click="next('year')" class="btn btn-primary">Yr <i class="fa fa-angle-right"></i></button>
      </div>

      <hr />

      <div class="calendar-row">
          <div class="dow">Su</div>
          <div class="dow">Mo</div>
          <div class="dow">Tu</div>
          <div class="dow">We</div>
          <div class="dow">Th</div>
          <div class="dow">Fr</div>
          <div class="dow">Sa</div>
      </div>
      <div class="calendar-row">
        <div class="dow" v-for="n in firstDayOfMonth"></div>  
        <div class="dow" v-for="n in daysInTheMonth">
          <a @click="selectDay(n+1, month, year)" v-bind:class="n+1 == selectedDay && month == selectedMonth && year == selectedYear ? 'active' : ''">{{ n+1 }}</a>
        </div>       
      </div>
      <div class="calendar-row"></div>
    </div>
    <hr />
    <div class="row text-center">
      <button @click="today" class="btn btn-primary"><i class="fa fa-calendar-o"></i> Show Today</button> 
    </div>
</template>

<script>
import 'moment';

export default {

  props: ['format', 'model'],

  components: {
  },

  data() {
    return {
      selectedDay: moment().format('D'),
      selectedMonth: moment().format('MMM'),
      selectedYear: moment().format('YYYY'),
      moment: moment().format(this.format)
    }
  },

  computed: {
    year() {
      return moment(this.moment).format('YYYY');
    },
    month() {
      return moment(this.moment).format('MMM');
    },
    firstDayOfMonth() {
      let m = moment(moment(this.moment).format('YYYY-MM'), 'YYYY-MM');
      m.set('date', 1);
      return parseInt(m.format('d'));
    },
    daysInTheMonth() {
      return parseInt(moment(this.moment).daysInMonth());
    }
  },
  
  methods: {
    today() {
      this.moment = moment().format(this.format);
    },
    tomorrow() {
      this.moment = moment().add(1, 'day').format(this.format);
    },
    next(type) {
      this.moment = moment(this.moment).add(1, type).format(this.format);
    },
    prev(type) {
      this.moment = moment(this.moment).subtract(1, type).format(this.format);
    },
    selectDay(date, month, year) {
      this.selectedDay = date;
      this.selectedMonth = month;
      this.selectedYear = year;

      let m = moment();
      m.set({
        'date': date,
        'month': month,
        'year': year
      });

      this.model = m.format(this.format);
    }
  },

  events: {
  },

  created() {
  },

  ready() {
  }
}
</script>