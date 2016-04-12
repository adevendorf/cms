<template>
  <div>
    <span v-if="form">
      <ul class="nav nav-tabs"> 
        <li class="nav-item"  v-for="block in form.blocks" @click="changeTab($index)">
          <a v-bind:class="'section-drop nav-link ' + (activeTab == $index ? 'active' : '')" v-bind:href="'#tab-section-' + block.id" data-toggle="tab">{{ block.title }}</a>
        </li> 
        <li class="nav-item" >
          <creater :parent.sync="form"></creater>
        </li>
      </ul> 

      <div class="tab-content"> 
        <div v-bind:class="'tab-pane active'" v-bind:id="'tab-section-' + activeTab">
          <former :block.sync="form.blocks[activeTab]" :content-types="contentTypes"></former>
        </div>
      </div>
    </span>  
  </div>
</template>

<script>
import creater from './creater.vue';
import former from '../../components/formsteps/former.vue';

export default {
  props: ['form'],

  components: {
    creater,
    // editor,
    former
  },

  data() {
    return {
      activeTab: 0,
      contentTypes: ['input', 'remove', 'options', 'text', 'image', 'gallery', 'block'],
      resource: null,
      dragTargetId: null
    }
  },

  methods: {
    changeTab(index) {
      this.activeTab = index;
    },
    dropItem($event) {
      $event.preventDefault();
      var item = JSON.parse($event.dataTransfer.getData('text/json'));
      this.moveToBlock(item);
    },
    dragEnter($event, id) {
      $event.preventDefault();
      this.setTarget(id);
    },
    dragOver($event) {
      $event.preventDefault();
    },
    moveToBlock(content) {
      var oldBlockIndex = 0;
      var oldBlockContentIndex = 0;
      var newBlockIndex = 0;

      var blockIndex;
      var contentIndex;

      for (blockIndex = 0; blockIndex < this.form.blocks.length; blockIndex++) {

        var block = this.form.blocks[blockIndex];
        
        if (block.id == this.dragTargetId) newBlockIndex = blockIndex;

        for (contentIndex = 0; contentIndex < block.content.length; contentIndex++) {
          var item = this.form.blocks[blockIndex].content[contentIndex];

          if (item.id == content.id) {
            oldBlockIndex = blockIndex;
            oldBlockContentIndex = contentIndex;
          }
        } 
      }

      this.form.blocks[oldBlockIndex].content.splice(oldBlockContentIndex, 1);

      if (!this.form.blocks[newBlockIndex].content) this.form.blocks[newBlockIndex].content = [];

      content.block_id = this.form.blocks[newBlockIndex].id;
      content.order = 999;
      this.form.blocks[newBlockIndex].content.push(content);

      this.$broadcast('sorter::moved');
      this.$broadcast('content::save', content);

 

    },
    setTarget(id) {
      this.dragTargetId = id;
    },

    findIndex(arr, id) {
      for (var i = 0; i < arr.length; i++) {
        if (arr[i].id == id) {
          return i;
          break;
        }
      }
      return -1;
    }
  },

  ready() {

    
    this.$on('block::remove', function(id){
      var item = this.form.blocks.filter(function(item) {
        return item.id == id;
      });

      var index = this.form.blocks.indexOf(item[0]);
      
      this.form.blocks.splice(index, 1);

      if (index == this.activeTab) {
        this.activeTab = this.activeTab-1;
      }

      return false;
    });
  }
}
</script>