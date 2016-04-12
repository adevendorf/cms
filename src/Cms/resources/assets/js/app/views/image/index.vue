<template>
<div>
  <div class="modal" id="image-manager" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-most" role="document">
      <div class="modal-content">
        IMAGE MANAGER
      </div>
    </div>
  </div>
</div>

</template>

<script>
export default {

  el: '#imagemanager', 

  data() {
    return {
      items: false,  
    } 
  },

  methods: {

    show() {
      $('#image-manager').modal('show'); 
    }
    ,
    hide() {
      $('#image-manager').modal('hide'); 
    }
  },

  ready() {
 
  }  
}
</script>