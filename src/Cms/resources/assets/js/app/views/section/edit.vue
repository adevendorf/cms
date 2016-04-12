<template>

  <div class="content">

    <loader :show="!item"></loader>

    <div class="card card-block" v-if="item">
      <div class="card-block">
        <h4 class="card-title">Edit Site Section</h4>    
        <h6 class="card-subtitle text-muted"><i class="fa fa-angle-left"></i> <a v-link="{name: 'section-index'}">Back to Site Sections</a></h6>
      </div>
      <div class="card-block">

        <div class="form-group">
          <label for="type" class="control-label">Name</label>
          <input class="form-control" type="text" v-model="item.name" />
        </div>

        <div class="form-group">
          <label for="type" class="control-label">Path</label>
          <input class="form-control" type="text" v-model="item.path" />
        </div>

        <div class="form-group">
          <label class="control-label">Section Image</label>
           <div v-if="item">
               <component is="controller" :parent.sync="item"></component>
            </div>  
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
import controller from '../../components/image/controller.vue';

export default {

  components: {
    controller
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
      if (!this.item.path) return false;
      if (!this.item.name) return false;
      return true;
    },

    saveItem() {
      if (!this.validate()) return;

      this.resource.update({id: this.id}, this.item).then((response) => {
          this.$router.go({name: 'section-index'});
      });
    },


    getItem() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('item', response.data);
      }); 
    }

  },

  events: {
    'image::set'() {
      this.saveItem();
      return false;
    }
  },

  ready() {
    var resource = this.$resource('section{/id}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItem();

      
  }  
}
</script>