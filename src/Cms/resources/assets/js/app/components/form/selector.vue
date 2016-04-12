<template>
<div class="modal fade" v-bind:id="'modal-selector-' + parentId" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Forms
      </div>
      <div class="modal-body" v-if="viewable">

        <component :is="activeComponent"></component>        

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" @click="close"><i class="fa fa-close"></i> Close</button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Lister from './lister.vue';

export default {
  props: ['options', 'parentId'],

  data() {
    return {
      viewable: false,
      activeComponent: 'lister'
    }
  },

  components: {
    lister: Lister
  },

  methods: {

     showSelection() {
      this.activeComponent = 'lister';
    },

    close() {
      this.modalClose();
    },

    modalClose() {
      $('#modal-selector-' + this.parentId).modal('hide'); 
    },


  },

  ready() {

    this.$on('selector::open', function(id) {  
      this.viewable =  true;
      this.activeComponent = 'lister'
      $('#modal-selector-' + this.parentId).modal('show'); 
      return false;
    });

  }
}
</script>