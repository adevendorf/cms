<template>

  <div class="content">
<!--     <div class="row">
      <div class="col-md-12">
        <a class="btn btn-primary">Show Useage</a>
        <a class="btn btn-primary">Schedule Publish</a>
        <a class="btn btn-primary">Schedule Un-Publish</a>
      </div>
    </div> -->
    <hr />
    <div class="row">
      <div class="col-md-12" v-if="item">



      <blocker :block.sync="item" :content-types="contentTypes"></blocker>

      </div>  
    </div>
    <div class="row"></div>
  </div>

</template>

<script>

export default {

  props: ['blockId'],

  data() {
    return {
      resource: null,
      id: null,
      item: null,
      contentTypes: ['options', 'text', 'image', 'form', 'gallery', 'block'],
    }
  }, 


  methods: {

    getItem() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('item', response.data);
      }); 
    }

  },

  ready() {

    var resource = this.$resource('block{/id}');
    this.$set('resource', resource);
    if (!this.blockId) this.$set('id', this.$route.params.id);
    this.getItem();  

  }  
}
</script>