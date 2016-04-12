<template>
  <div class="content">
    <div class="card" v-if="item">
      <div class="card-block">
        <h4 class="card-title">Edit Gallery</h4>    
        <h6 class="card-subtitle text-muted"><i class="fa fa-angle-left"></i> <a v-link="{name: 'gallery-index'}">Back to Galleries</a></h6>
      </div>
      <div class="card-block">
        <div v-if="item">
          <blocker :block.sync="item" :content-types="contentTypes" type="Gallery"></blocker>
        </div>   
      </div>
    </div>
  </div>
</template>

<script>
export default {

  data() {
    return {
      resource: null,
      id: null,
      item: null,
      contentTypes: ['image'],
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

    console.log('ready');

    var resource = this.$resource('gallery{/id}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItem();  

  }  
}
</script>