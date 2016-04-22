<template>
<div>  

  <div class="row">
    <div class=" col-sm-8">
      <div class="form-group" v-if="block.type != 'page'">
        <input class="form-control" name="type" type="text" v-model="block.title" @blur="saveItem($event)"/>
      </div>
      <div class="form-group" v-if="block.type == 'page'">
        <div>{{ block.title }}</div>
      </div>
    </div>
    <div class="col-sm-4 form-group text-right">
      <div>
        <a v-if="contentTypes.indexOf('options') > -1" class="btn btn-default" @click="toggleEditor">
          <i class="fa fa-cog"></i>
        </a>

        <a @click="expandAll" class='btn btn-default'><i class="fa fa-expand"></i></a> 
        <a @click="hideAll" class='btn btn-default'><i class="fa fa-compress"></i></a> 

        <a v-if="block.title !== 'main' && contentTypes.indexOf('remove') > -1" class="btn btn-default" @click="confirmRemoval(block.id)">
          <i class="fa fa-times"></i> 
        </a>
      </div>
    </div>    
  </div> 

  <hr />

  <div v-if="contentTypes.indexOf('options') > -1 && block.id">
    <editor :block.sync="block"></editor> 
  </div>

  <ul v-if="block.id" v-bind:id="'blocker-sortable-' + block.id" class="will-sort">

    <li v-for="content in items" v-bind:data-id="content.id">
        <div 
          v-if="content.id" 
          class="handle ">         
            <div class="handle-holder"></div>
            <i class="fa fa-arrows"></i>
        </div>
        <contentcomponents :content="content"></contentcomponents>
    </li>

  </ul>

  <div class="btn-group">
    
    <div class="btn-group" role="group">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
        Text
      </button>
      <div class="dropdown-menu">
        <a @click="addToEnd('Text')" v-if="contentTypes.indexOf('text') > -1" class="dropdown-item"><i class="fa fa-paragraph"></i> Rich Text</a>
        <a @click="addToEnd('Code')" v-if="contentTypes.indexOf('text') > -1" class="dropdown-item"><i class="fa fa-at"></i> Markdown</a>
        <a @click="addToEnd('Code')" v-if="contentTypes.indexOf('text') > -1" class="dropdown-item"><i class="fa fa-code"></i> Code</a>
        <a @click="addToEnd('Code')" v-if="contentTypes.indexOf('text') > -1" class="dropdown-item"><i class="fa fa-sticky-note-o"></i> Free Form</a>
        <a @click="addToEnd('IconBox')" v-if="contentTypes.indexOf('text') > -1" class="dropdown-item"><i class="fa fa-star"></i> IconBox</a>
      </div>
    </div>

    <div class="btn-group" role="group">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
        Media
      </button>
      <div class="dropdown-menu">
         <a @click="addToEnd('Image')" v-if="contentTypes.indexOf('image') > -1" class="dropdown-item"><i class="fa fa-camera"></i> Image</a>
      </div>
    </div>
  
  
    <div class="btn-group" role="group">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
        Includes
      </button>
      <div class="dropdown-menu">
        <a @click="addToEnd('Gallery')" v-if="contentTypes.indexOf('gallery') > -1" class="dropdown-item"><i class="fa fa-photo"></i> Gallery</a>
        <a @click="addToEnd('Form')" v-if="contentTypes.indexOf('form') > -1" class="dropdown-item"><i class="fa fa-check-square-o"></i> Form</a>
        <a @click="addToEnd('Block')" v-if="contentTypes.indexOf('block') > -1" class="dropdown-item"><i class="fa fa-cube"></i> Block</a>
      </div>
    </div>

    <div class="btn-group" role="group">
      <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
        Content
      </button>
      <div class="dropdown-menu">


        <a @click="addToEnd('Feed')" v-if="contentTypes.indexOf('feed') > -1" class="dropdown-item"><i class="fa fa-newspaper-o"></i> Feed</a>
        <a @click="addToEnd('ListCategories')" v-if="contentTypes.indexOf('predefined') > -1" class="dropdown-item"><i class="fa fa-list-ul"></i> Categories</a>
        <a @click="addToEnd('LatestPosts')" v-if="contentTypes.indexOf('predefined') > -1" class="dropdown-item"><i class="fa fa-list-ul"></i> Latest Posts</a> 
        <a @click="addToEnd('Register')" v-if="contentTypes.indexOf('predefined') > -1" class="dropdown-item"><i class="fa fa-list-ul"></i> Register</a> 
        <a @click="addToEnd('Login')" v-if="contentTypes.indexOf('predefined') > -1" class="dropdown-item"><i class="fa fa-list-ul"></i> Login</a>  
      </div>
    </div>   
  </div>

  <div class="row"></div>


  <confirm v-on:confirm="removeItem"></confirm>
</div>
</template>

<script>

import contentcomponents from '../components/content/_components.vue'
import editor from '../components/blocksections/editor.vue';

export default {

  props: ['block', 'contentTypes'],

  data() {
    return {
      resource: null,
      items: [],
      drake: null,
    }
  }, 


  components: {
    editor,
    contentcomponents
  },  


  methods: {
    expandAll() {
      this.$broadcast('blocker::expandAll');
    },
    hideAll() {
      this.$broadcast('blocker::hideAll');
    },
    toggleEditor() {
      this.$broadcast('editor::toggle');
    },

    addItem(type, pos) {
      let newItem = {
        type: type,
        order: pos,
        block_id: this.block.id
      };

      this.resource.save(newItem).then((response) => {    
        this.items.splice(pos, 0, response.data);
        setTimeout(this.saveOrder, 1000);
      });
    },

    addToEnd(type) {
      this.addItem(type, this.items.length);
    },

    addAtIndex(type, pos) {
      this.addItem(type, pos);
    },

    setOrder() {
      var index = 0;
      _.forEach($('#blocker-sortable-' + this.block.id + ' li'), (item) => {
        var id = parseInt($(item).attr('data-id'));        
        var obj = _.findWhere(this.items, {id: id});
        obj.order = index;
        index++;
      });
    },


    saveOrder() {
      var order = [];

      this.setOrder();

      this.items.forEach((val) => {
        order.push({
          id: val.id,
          order: val.order
        });
      })

      this.$http.post('content/order', {items: order}).then((response) => {});
    },

    saveItem() {  
      this.$http.put('/api/v1/block/' + this.block.id, this.block);
    },

    confirmRemoval() {
      this.$broadcast('confirm::ask');
    },

    removeItem() {
      var id = this.block.id;
      this.$http.delete('/api/v1/block/' + this.block.id).then((response) => {
        this.$dispatch('block::remove', id);    
      });
    }, 

    getItems() {
      var blockData = {
        block_id: this.block.id
      };

      this.resource.get(blockData).then((response) => {
        this.items = response.data;

        setTimeout(this.setUpDragging, 1000);
       
      }); 
    },

    setUpDragging() {

      if (window.blockerDrake) window.blockerDrake.destroy();

      window.blockerDrake = dragula({
        moves: function (el, container, handle) {
          return handle.className === 'handle-holder'
        },
        direction: 'vertical',
        revertOnSpill: false,
      });

      window.blockerDrake.on('drop', (el, target, source, sibling) => {     
          this.saveOrder();
      });

      window.blockerDrake.containers.push(document.getElementById('blocker-sortable-' + this.block.id));

    }

  },

  events: {

    'confirm::confirmed'() {
      this.removeItem();
      return false;
    },

    'update-parent'() {
      this.saveItem();
      return false;
    },

    'sorter::moved'() {
      this.removeDropSpotsAndSave();
      return false;
    },

    'block::content::remove'(id) {
      for (var i = 0 ; i < this.block.content.length; i++) {
        if (this.block.content[i].id == id) {
          this.block.content.splice(i, 1);
          break;
        } 
      }
      return false;
    } 
  },


  ready() {
    
    var resource = this.$resource('content{/id}');
    this.$set('resource', resource);


    this.getItems();
  
  }  
}
</script>