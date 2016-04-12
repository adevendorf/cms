<template>
<div class="content">

  <loader :show="!item"></loader>

  <div class="card card-block" v-if="item">
      <div class="card-block">
        <h4 class="card-title">Edit Extension</h4>    
        <h6 class="card-subtitle text-muted"><i class="fa fa-angle-left"></i> <a v-link="{name: 'extension-index'}">Back to Extensions</a></h6>
      </div>
      <div class="card-block">
      <div class="form-group">
        <label for="type" class="control-label">Type: </label>
        <input class="form-control" name="type" type="text" v-model="item.type"/>
      </div>
      <div class="form-group">
        <label for="key" class="control-label">Key: </label>
        <input class="form-control" name="key" type="text" v-model="item.key"/>
      </div>
      <div class="form-group">
        <label for="value" class="control-label">Display Value: </label>
        <input class="form-control" name="value" type="text" v-model="item.value"/>
      </div>
    </div>

    <div class="card-block">
      <button class="btn btn-primary" @click="saveItem" v-show="valid">Save</button>
      <button class="btn btn-danger" v-show="!valid">Missing Fields</button>
    </div>
  </div> 
</div>  
</template>

<script>
export default {

  components: {
 
  },

  data() {
    return {
      resource: null,
      id: null,
      item: null
    }
  }, 

  computed: {
    valid() {
      return this.validate();
    }
  },

  methods: {

    validate() {
      if (!this.item) return false;
      if (!this.item.type) return false;
      if (!this.item.key) return false;
      if (!this.item.value) return false;
      return true;
    },

    saveItem() {

      if (!this.validate()) return;

      this.resource.update({id: this.id}, this.item).then((response) => {
          this.$router.go({name: 'extension-index', params: { }});
       });
    },


    getItem() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('item', response.data);
      }); 
    }

  },

  ready() {
    var resource = this.$resource('extension{/id}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItem();
  }  
}
</script>