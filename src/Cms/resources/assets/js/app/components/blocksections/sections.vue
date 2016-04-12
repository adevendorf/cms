<template>
  <div>
    <span v-if="page">
      <ul class="nav nav-tabs"> 
        <li class="nav-item" v-for="block in page.blocks">
          <a  v-bind:class="'section-drop nav-link '" @click="changeTab(block.id)">{{ block.title }}</a>
        </li> 
        <li class="nav-item" v-if="page.type == 'page'">
          <creater :parent.sync="page"></creater>
        </li>
      </ul> 

      <div class="tab-content" v-if="item"> 
        <div v-bind:class="'tab-pane active'" v-bind:id="'tab-section-' + item.id">
          <div v-if="!item">Loading</div>
          <blocker :block="item" :content-types="contentTypes"></blocker>
        </div>
      </div>
    </span>  
  </div>
</template>

<script>
import creater from './creater.vue';

export default {
  props: ['page'],

  components: {
    creater,
  },

  data() {
    return {
      item: null,
      resource: null,
      hasBlockData: false,
      contentTypes: [
        'predefined', 
        'remove', 
        'options', 
        'text', 
        'image', 
        'form', 
        'gallery', 
        'block', 
        'feed'
      ],
    }
  },

  methods: {


    changeTab(id) {    
      this.item = null;
      this.getBlock(id);
    },

    getBlock(id) {
      this.resource.get({id: id}).then((response) => {  
        this.hasBlockData = true;
        this.item = response.data;      
      });      
    },

    loadFirstBlock() {
      if (!this.page.blocks.length) return;

      this.getBlock(this.page.blocks[0].id);
    }
  },

  events: {
    'block::remove'(id) {
      var item = this.page.blocks.filter(function(item) {
        return item.id == id;
      });

      var index = this.page.blocks.indexOf(item[0]);
      
      this.page.blocks.splice(index, 1);

      if (index == this.activeTab) {
        this.activeTab = this.activeTab-1;
      }

      return false;
    }
  },

  ready() {
    var resource = this.$resource('block{/id}');
    this.$set('resource', resource);

    this.loadFirstBlock();
  }
}
</script>