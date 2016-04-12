<template> 
  <span>{{ timeAgo }}</span>
</template>

<script>
export default {

  props: ['timestamp'],
  data() {
    return {
      timeAgo: ''
    }
  },
  methods: {
    ago() {
      //http://stackoverflow.com/a/23259289
      var date = this.timestamp;

      if (typeof date !== 'object') {
          date = new Date(date + ' UTC');
      }

      var seconds = Math.floor((new Date() - date) / 1000);
      var intervalType;

      var interval = Math.floor(seconds / 31536000);
      if (interval >= 1) {
          intervalType = 'year';
      } else {
          interval = Math.floor(seconds / 2592000);
          if (interval >= 1) {
              intervalType = 'month';
          } else {
              interval = Math.floor(seconds / 86400);
              if (interval >= 1) {
                  intervalType = 'day';
              } else {
                  interval = Math.floor(seconds / 3600);
                  if (interval >= 1) {
                      intervalType = "hour";
                  } else {
                      interval = Math.floor(seconds / 60);
                      if (interval >= 1) {
                          intervalType = "min";
                      } else {
                          interval = seconds;
                          intervalType = "sec";
                      }
                  }
              }
          }
      }

      if (interval > 1 || interval === 0) {
          intervalType += 's';
      }

      return interval + ' ' + intervalType;
    },
    update() {
      this.timeAgo = this.ago();
      setTimeout(this.update, 1000);
    }
  },

  ready() {
    this.update();
  }
}
</script>