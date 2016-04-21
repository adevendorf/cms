<template>
<div>
  <confirm v-on:confirm="clearImage"></confirm>

  <div class="well">
    <span v-if="haveImage">
      <a 
        class="image-preview" 
        v-bind:style="'background-image:url(/img/asset/preview/' + parent.image.asset.path + parent.image.asset.cms_filename + ')'"
        @click="openManager" 
      >&nbsp;</a> 
      <a 
        class="btn btn-primary" 
        @click="openManager"
        >
        Edit
      </a>
    </span>
    <a 
      v-show="!haveImage" 
      class="btn btn-primary" 
      @click="openManager"
      >
      Select
    </a>
    <a v-show="haveImage" class="btn btn-danger" @click="confirmRemoval">Remove</a>
  </div>


    <manager 
      :item="parent" 
      :random="randomId" 
      :show-panel.sync="showModal"
      >
    </manager> 
</div>
</template>

<script>

import manager from './manager.vue';


export default {
  props: ['parent', 'details'],

  components: {
    manager,
  },

  data() {
    return {
      visible: false,
      randomId: Math.random().toString(36).substring(7) + '-' + Math.random().toString(36).substring(7),
      resource: null,
      showModal: false
    }
  },
  watch: {
    'parent.image': function (val, oldVal) {
      console.log('new/old', val, oldVal)
    },
  },
  computed: {
    haveImage() {
      return this.parent.image && this.parent.image.asset_id;
    }
  },

  methods: {

    clearImage() {
      this.resource.delete({id: this.parent.image.id}).then(() => {
        this.parent.image_id = null;
        this.parent.image = null;
        this.$dispatch('image::set');
      });
      return false;
    },

    openManager() {
      console.log(this.parent.image, this.parent.image_id)
      this.visible = true;
      this.showModal = true;
      this.$broadcast('image::main::getsizes');
    },

    confirmObject() {
      if (this.parent.image == null) { 
        this.parent.image = {}; 
      }
    },

    confirmRemoval() {
      this.$broadcast('confirm::ask');
    }
  },


  events: {

    'image::upload::done'(data) {
      this.$broadcast('image-manager', 'lister');
      return false;
    },

    'image::selected'(asset) {
      this.confirmObject();
      this.$set('parent.image.asset_id', asset.id);
      this.$set('parent.image.asset', asset);
      this.$dispatch('image::set');
      return false;
    },

    'image::manager::done'() {
      this.$dispatch('image::set');
      this.showModal = false;
      return false;
    },

    'confirm::confirmed'() {
      this.clearImage();
      return false;
    }
  },


  ready() {
    var resource = this.$resource('image{/id}');
    this.$set('resource', resource);
    this.confirmObject();
  }
}
</script>