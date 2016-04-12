<template>
<div>  

  <div class="row">
    <div class="col-sm-10">
      <div class="form-group" v-if="block.type != 'page'">
        <label for="type" class="control-label">Step Name </label>
        <input class="form-control" name="type" type="text" v-model="block.title" @blur="saveItem($event)"/>
      </div>
    </div>
    <div class="col-sm-2 form-group">
      <label class="control-label">&nbsp;</label>
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
  <ul v-if="block.id" v-bind:id="'sortable-' + block.id" class="will-sort">

    <li v-for="content in block.content" v-bind:data-id="content.id">
        <div 
          v-if="content.id" 
          class="handle ">         
            <div class="handle-holder"></div>
            <i class="fa fa-arrows"></i>
        </div>
        <formcomponents :content.sync="content"></formcomponents>
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
      </div>
    </div>


      <a class="btn btn-secondary" @click="addToEnd('Input')">
        Form Element
      </a>
      
  </div>

  <div class="row"></div>
  <div class="loading" v-if="loadingNewContent"><div class="spinner"></div></div>


  <confirm v-on:confirm="removeItem"></confirm>
</div>
</template>

<script>

import formcomponents from '../formcontent/_formcomponents.vue'

export default {

  props: ['block', 'contentTypes'],

  data() {
    return {
      resource: null,
      items: {
        data: []
      },
      loadingNewContent: false,
      validTarget: false
    }
  }, 




  components: {
    formcomponents
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
        this.block.content.splice(pos, 0, response.data);
        setTimeout(this.saveOrder, 1000);
      });
    },

    addToEnd(type) {
      this.addItem(type, this.block.content.length);
    },

    addAtIndex(type, pos) {
      this.addItem(type, pos);
    },

    setOrder() {
      var index = 0;
      _.forEach($('#sortable-' + this.block.id + ' li'), (item) => {
        var id = parseInt($(item).attr('data-id'));        
        var obj = _.findWhere(this.block.content, {id: id});
        obj.order = index;
        index++;
      });
    },


    saveOrder() {
      var order = [];

      this.setOrder();

      this.block.content.forEach((val) => {
        order.push({
          id: val.id,
          order: val.order
        });
      })

      this.$http.post('content/order', {items: order}).then((response) => {
      });
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
        this.block.content = response.data;       
      }); 
    },

    setUpDragging() {
      if (window.drake) window.drake.destroy();
      window.drake = dragula({
        moves: function (el, container, handle) {
          return handle.className === 'handle-holder'
        },
        direction: 'vertical',
        revertOnSpill: false,
      });

      window.drake.on('drop', (el, target, source, sibling) => {     
        // if (source == document.getElementById('tools')) {
        //   let type = $(el).data('type');
        //   let siblingId = $(sibling).data('id');
        //   let vIndex = sibling ? _.findIndex(this.block.content, (item) => {
        //     return item.id == siblingId;
        //   }) : null;

        //   if (vIndex !== null && vIndex < 0) vIndex = 0;

        //   if (vIndex === null) vIndex = this.block.content.length;
          
        //   let newItem = {
        //     type: type,
        //     order: vIndex,
        //     block_id: this.block.id
        //   };

        //   $(el).remove();

        //   this.resource.save(newItem).then((response) => {    
        //     this.block.content.splice(vIndex, 0, response.data);
        //     setTimeout(this.saveOrder, 1000);
        //   });

        // } else {
          this.saveOrder();
        // }
      });

      window.drake.containers.push(document.getElementById('sortable-' + this.block.id));

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

    if (this.block.content) this.setOrder();
    if (!this.block.content) this.block.content = [];

  }  
}
</script>