<template>
  <ol class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li class="active">Extensions</li>
  </ol>  



<div class="col-md-12">
  <div class="loading" v-if="!item"><div class="spinner"></div></div>
  <span v-if="item">
    <div class="form-group">
      <label for="type" class="control-label">Type: </label>
      <input class="form-control" name="type" type="text" v-model="item.type" />
    </div>
    <div class="form-group">
      <label for="key" class="control-label">Key: </label>
      <input class="form-control" name="key" type="text" v-model="item.key" />
    </div>
    <div class="form-group">
      <label for="value" class="control-label">Display Value: </label>
      <input class="form-control" name="value" type="text" v-model="item.value" />
    </div>
    <div class="form-group">
      <button class="btn btn-success" @click="saveItem($event)"><i class="fa fa-check"></i> Save</button>
    </div>
  </span> 
</div>  
</template>

<script>
export default {

  data() {
    return {
      item: null
    }
  }, 

  methods: {

    saveItem: function(e) {
      e.preventDefault();

      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');

      this.$http.put('/api/v1/extension/' + this.$route.params.id,
        this.item, 
        function(data, status, request) {
          console.log(data, status, request);
          router.go({name: 'extension-index', params: { }});
       })
        .error(function(data, status, request) {
          console.log(data, status, request);
      });
    },


    getItem: function() {
      this.$http.get('/api/v1/extension/' + this.$route.params.id, function (data, status, request) {
        this.$set('item', data);
      }).error(function (data, status, request) {
         console.log(data, status, request);
      }); 
    }

  },

  ready() {
    this.getItem();
  }  
}
</script>