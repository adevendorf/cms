<template>
  <div class="card">

    <confirm v-on:confirm="removeItem"></confirm>

    <div class="card-header">
      Code
      <div class="pull-right">
        <a class="btn btn-xs btn-light" @click="toggleStyling"><i class="fa fa-cog"></i></a>
        <a class="btn btn-xs btn-light" @click="confirmRemoval"><i class="fa fa-times"></i></a>
      </div>
    </div>

    <div class="card-block">
      <div class="form-group">

          <textarea v-if="editing" v-model="editor" v-bind:id="'textarea_' + content.id"></textarea>
          <textarea style="display: none" name="data" v-model="content.data"></textarea>

          <div class="alert alert-default text-content-sample" role="alert" v-if="!editing">
            <div v-html="content.data"></div>
          </div>
          
      </div>

      <div class="form-group">
        <button class="btn btn-info" @click="startEditing" v-if="!editing"><i class="fa fa-pencil"></i> Edit</button> 
        <button class="btn btn-success" @click="stopEditing" v-if="editing"><i class="fa fa-check"></i> Done</button>
      </div>

      <div v-if="showStyling">
        <styling :content="content"></styling>
      </div>
    </div>

  </div>
</template>

<script>

import styling from './_styling.vue';

export default {

  props: ['content'],

  components: {
    styling,
  },

  data() {
    return {
      id: null, 
      resource: null,
      editor: '',
      editing: false,
      expanded: true,
      showStyling: false
    }
  },

  methods: {

    toggleStyling() {
      this.showStyling = !this.showStyling;
    },

    saveItem(item) {
      if (this.editing) {
        this.stopEditing(e, true);
        return;
      }

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
      this.editor = this.content.data;
      setTimeout(this.createWYSIWYG, 0);
    },

    createWYSIWYG() {
      CKEDITOR.replace('textarea_' + this.id);
    },

    stopEditing(saveAfter) {     
      this.content.data = CKEDITOR.instances['textarea_' + this.id].getData();
      this.editing = false;
      CKEDITOR.instances['textarea_' + this.id].destroy();
      this.saveItem();
    },

    confirmRemoval() {
      this.$broadcast('confirm::ask');
    },

    removeItem() {
      this.resource.delete({id: this.id}).then((response) => {
        this.$dispatch('block::content::remove', this.id);
      });
    }

  },

  ready() {

    var resource = this.$resource('content{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.id);



    this.$on('confirm::confirmed', () => {
      this.removeItem();
      return false;
    });

    this.$on('styler::updated', () => {
      this.saveItem();
      return false;
    });
    

    this.$on('content::save', (item) => {
      if (item && item.id == this.content.id) {
        this.saveItem(item);
        return false;
      }
      this.saveItem();
      return false;
    }); 



  }
}
</script>