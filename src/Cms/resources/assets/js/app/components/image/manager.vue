<template>
  <div>
    <side-panel 
      :show.sync="showPanel" 
      size="large" 
      header="true" 
      side="left"
      v-on:close="done"
      >
      <div slot="header">
        Image Control
      </div>
      <div slot="body">

        <main 
          :parent="item"
          >
        </main>
        
        <modal
          size="large"
          footer="true"
          header="true"
          :show.sync="visibleModal"
          >
          <div slot="header">
            More
          </div>
          <div slot="body">              
            <component 
              :is="loaded" 
              :image="item" 
              slot="body"
              >
            </component>
          </div>
          <div slot="footer">
            <button 
              class="btn btn-default" 
              @click="visibleModal = false"
              >
              Cancel
            </button>
          </div>
        </modal>        
      </div>
    </side-panel>
  </div>
</template>

<script>
import Main from './views/main.vue';
import Lister from './views/lister.vue';
import Uploader from '../../components-reusable/uploader.vue';
import CropSizes from './views/cropsize.vue';


export default {

  props: ['item', 'random', 'showPanel'],

  data() {
    return {
      loaded: false,
      visibleModal: false
    }
  },

  components: {
    Main,
    Lister,
    Uploader,
    CropSizes
  },

  methods: {
    done() {
      this.$dispatch('image::manager::done');
    }, 

    view(which) {
      this.loaded = which;  
      this.visibleModal = true;
    }
  },

  events: {
    'image::selected'() {
      this.visibleModal = false;
      return true;
    },
    'image::manager::view'(data) {
      this.view(data); 
      return false;
    }
  },

  ready() {
    this.loaded = 'lister';    
  }
}
</script>