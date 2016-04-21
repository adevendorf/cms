<template>
  <div class="card">
    <content-header type="Rich Text"></content-header>
    
    <div class="card-block" v-if="visible.main">
      <textarea name="data" v-bind:id="'textarea_' + content.id" v-model="content.data" style="display: none"></textarea>   
      <styling :content="content"></styling>           
    </div>    
  </div>
</template>

<script>

import styling from './_styling.vue';
import contentHeader from './_header.vue';

export default {

  props: ['content'],

  components: {
    contentHeader,
    styling,
  },

  data() {
    return {
      id: null, 
      resource: null,
      editor: '',
      editing: false,
      expanded: true,
      visible: {
        main: true
      }
    }
  },

  methods: {

    saveItem(item) {
      // if (this.editing) {
      //   this.stopEditing(e, true);
      //   return;
      // }

      if (item) {
        this.resource.update({id: this.id}, item);
        return;
      } 

      if (!this.content.id) {
        this.resource.save(this.content).then((response) => {
          this.content = response.data
        });
        return;
      }  

      this.resource.update({id: this.id}, this.content);
    },


    startEditing() {
      this.editing = true;
      setTimeout(this.createWYSIWYG, 100);
    },

    createWYSIWYG() {
      CKEDITOR.replace('textarea_' + this.id, {
      });

      CKEDITOR.instances['textarea_' + this.id].on('blur', () => {
         this.content.data = CKEDITOR.instances['textarea_' + this.id].getData();
         this.saveItem();
      });

    },

    killWYSIWYG() {
      CKEDITOR.instances['textarea_' + this.id].destroy();
    },

    stopEditing(saveAfter) {     
      this.content.data = CKEDITOR.instances['textarea_' + this.id].getData();
      this.editing = false;      
      this.saveItem();
      setTimeout(this.killWYSIWYG, 0);
    },

    removeItem() {
      this.resource.delete({id: this.id}).then((response) => {
        this.$dispatch('block::content::remove', this.id);
      });
    }

  },

  events: {

    'header::toggleStyler'() {
      if (!this.visible.main) {
        setTimeout(() => { this.visible.main = !this.visible.main; }, 0);
      }
      this.$broadcast('styler::toggle');
      return false;
    },

    'header::toggleVisible'() {
      if (this.visible.main) {
        this.stopEditing();
        setTimeout(() => { this.visible.main = false; }, 100)
      } else {
        this.visible.main = true;
        this.startEditing();
      }

      ;

      return false;
    },

    'header::removeContent'() {    
      this.removeItem();
      return false;
    },


    'blocker::expandAll'() {
      this.visible.main = true;
      this.startEditing();
      return false;
    },
    'blocker::hideAll'() {
      this.stopEditing();
      setTimeout(() => { this.visible.main = false; }, 100)
      return false;
    },

    'confirm::confirmed'() {
      this.removeItem();
      return false;
    },

    'styler::updated'() {
      this.saveItem();
      return false;
    },

    'content::save'(item) {
      if (item && item.id == this.content.id) {
        this.saveItem(item);
        return false;
      }
      this.saveItem();
      return false;
    }
  },  


  ready() {
    var resource = this.$resource('content{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.id);
    setTimeout(this.startEditing, 100);
  }
}
</script>