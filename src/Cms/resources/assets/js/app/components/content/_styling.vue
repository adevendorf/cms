<template>
  <div v-if="visible">
    <hr />
    <div class="card card-default">
      <div class="card-block">
        <styler :model="content"></styler>

        <div class="row">
          <div v-if="items.data" class="col-md-4">
            <label class="control-label">Content Template</label>
            <select v-model="content.template" class="form-control" @change="updateItem">
              <option value="_default">default</option>
              <option v-for="option in items.data" v-bind:value="option.value">
                {{ option.value }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import styler  from '../styler.vue';

export default {
  props: ['content'],
  
  components: {
    styler
  },

  data() {
    return {
      resource: null,
      type: null,
      items: {
        data: []
      },
      visible: false
    }
  },

  methods: {
    getItems() {
      this.resource.get({type: this.type}).then((response) => {
        this.items.data = response.data;
      }); 
    },

    updateItem() {
      this.$dispatch('styler::updated');
    }
  },

  events: {
    'styler::toggle'() {
      this.visible = !this.visible;
    }
  },

  ready() {

    var resource = this.$resource('contenttemplate{/type}');
    this.$set('resource', resource);
    this.$set('type', this.content.type);

    this.getItems();

  }
}
</script>