<template>
  <h2>Cache</h2>

  <table class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>Setting</th>
      <th>Value</th>
    </tr>
  </thead>
  <tr v-for="item in items">
    <td>{{ item.key }}</td>
    <td>{{ item.value }}</td>
  </tr>
  </table>

  <p><button class="btn btn-primary">Clear All Cache</button></p>

</template>

<script>
export default {

  data() {
    return {
      items: []
    }
  },

  methods: {
    getItems: function() {
      this.$http.get('/api/v1/cache/', function (data, status, request) {
        this.$set('items', data);
      }).error(function (data, status, request) {
        System.handleError(status);
      });
    }
  },

  ready() {
    this.getItems();
  }

}
</script>