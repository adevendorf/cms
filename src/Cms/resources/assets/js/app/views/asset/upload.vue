<template>
  <div>
    <div class="card card-block">
      <div class="card-block">
        <h4 class="card-title">Upload Image</h4>    
        <h6 class="card-subtitle text-muted"><i class="fa fa-angle-left"></i> <a v-link="{name: 'asset-index'}">Back to Images</a></h6>
      </div>
      <div class="card-block">
        <uploader></uploader>
      </div> 
    </div>
  </div>
</template>

<script>
import uploader from '../../components-reusable/uploader.vue';

export default {

  components: {
    uploader
  },

  data() {
    return {
    }
  },

  methods: {

  },

  events: {
    'image-selected'() {
       router.go({name: 'asset-index' });
      return false;
    }
  },

  ready() {

   
  }
}
</script>