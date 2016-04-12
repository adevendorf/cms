<template>
  <div v-if="visible">
      
    <div class="card card-inverse card-default ">
      <div class="card-block">
        <div class="row">
          <div class="col-md-4">     
            <div class="form-group">
              <label class="control-label">Name</label>
              <input class="form-control" type="text" name="name" v-model="block.title" @blur="updateParent()" {{ (block.title == 'main' ? 'disabled' : '') }} /> 
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label class="control-label"> Image</label>
              <component is="controller" :parent.sync="block"></component>
            </div>
          </div>
        </div>
        <styling :content="block"></styling>
      </div>
    </div>
    <hr />
  </div>
</template>

<script>
import controller from '../../components/image/controller.vue';
import styling from '../content/_styling.vue';

export default {
  props: ['block'],

  components: {
    styling,
    controller
  },

  data() {
    return {
      visible: false,
      resource: null
    }
  },

  events: {
    'editor::toggle'() {
      this.visible = !this.visible;
      return false;
    },
    'image::set'() {
      this.saveItemUpdateImage();
      return false;
    }
  },

  methods: {
    saveItemUpdateImage() {
      this.resource.update({id: this.block.id}, this.block).then((response) => {
        this.block.image = response.data.image;
      });
    },

    updateParent() {
      this.$dispatch('update-parent');

      // this.$parent.$data.item.title = this.$data.content.title;
      // this.$parent.$data.item.styling = this.$data.content.styling;
      // this.$http.post('/api/v1/block/' + this.$data.content.id,  this.$data.content, function(data, status, request) {
      // }).error(function(data, status, request) {
      //   System.handleError(status);
      // });
    }
  },

  ready() {  
    var resource = this.$resource('block{/id}');
    this.$set('resource', resource);

    this.$on('styler-updated', function() {
      this.updateParent();
      return false;
    });
  }
}
</script>