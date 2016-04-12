<template>
  <div class="panel panel-primary">
  <div class="panel-heading">Blog Page Break</div>
  <div class="panel-body block">

    <input name="id" type="hidden" v-model="content.id">
    <input name="order" type="hidden" v-model="content.order">
    <input name="data" type="hidden"  v-model="content.data">
    <input name="styling" type="hidden"  v-model="content.styling"> 
    <input name="resource_id" type="hidden"  v-model="content.resource_id">   

      <div lass="alert alert-default" role="alert">
      <h4>Read More</h4>
      <p><small>This page break will only show up on a Blog's Index/Category page.</small></p>
    </div>

    
    <div>     

      <a href="#" class="btn btn-danger pull-right" @click="removeThis($event)"><i class="fa fa-times"></i></a>
    </div>



  </div>
  <confirm v-on:confirm="removeItem"></confirm>
</div>


</template>

<script>
export default {
  props: ['content'],

  data() {
    return {
      text: '',
    }
  },


  methods: {

    buildJson: function() {

      var obj = {};

      if (this.$data.text != '') obj.text = this.$data.text;

      var stringed = JSON.stringify(obj);

      this.content.data = stringed == "{}" ? "" : stringed;

    },

    loadJson: function() {

      var json = this.content.data;

      if (json && json.length) {
        try {
          json = JSON.parse(json);
        } catch(e) {
          console.log('data value is not valid');
        }

        if (json.text) this.$data.text = json.text;
      }
    },


    saveThis: function(e) {
      if (e) e.preventDefault();

      this.buildJson();

      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');

      this.$http.post('/api/v1/content/' + this.content.id, this.content, function(data, status, request) {
       }).error(function(data, status, request) {
          System.handleError(status);
      });
    }, 

    removeThis: function(e) {
      if (e) e.preventDefault();
      this.$broadcast('confirm-ask');
    },

    deleteAction: function() {
      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');
     
      this.$http.delete('/api/v1/content/' + this.content.id, {}, function(data, status, request) {
          this.$dispatch('block-updated');
       }).error(function(data, status, request) {
          System.handleError(status);
      });
    },

  },


  ready() {

    this.loadJson();

    this.$on('confirm-delete', function() {
      this.deleteAction();
      return false;
    });

    this.$on('content-save', function() {
      this.saveThis();
      return false;
    }); 

  }
}
</script>