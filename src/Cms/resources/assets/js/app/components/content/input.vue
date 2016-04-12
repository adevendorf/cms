<template>
  <div class="card">

    <div class="card-header">
      Input
      <div class="pull-right">
        <a class="btn btn-xs btn-light" @click="toggleStyling"><i class="fa fa-cog"></i></a>
        <a class="btn btn-xs btn-light" @click="confirmRemoval"><i class="fa fa-times"></i></a>
      </div>
    </div>

    <div class="card-block">
      <div class="form-group">

        <div v-if="!content.resource_id">
          <selection :content.sync="content"></selection>
        </div>
        <div v-if="content.resource_id">
          <textfield :content.sync="content"></textfield>
        </div>
          
      </div>

      <div v-if="showStyling">

      </div>
    </div>
    <confirm v-on:confirm="removeItem"></confirm>
  </div>
</template>

<script>
import textfield from '../formcontent/textfield.vue';
import selection from '../formcontent/_selection.vue';

export default {

  props: ['content'],

  components: {
    textfield,
    selection
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