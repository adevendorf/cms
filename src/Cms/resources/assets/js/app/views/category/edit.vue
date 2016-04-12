<template>
  <div class="content">

    <loader :show="!item"></loader>

    <div class="card" v-if="item">
      <div class="card-block">
        <h4 class="card-title">Edit Category</h4>    
        <h6 class="card-subtitle text-muted"><i class="fa fa-angle-left"></i> <a v-link="{name: 'category-index'}">Back to Categories</a></h6>
      </div>
      <div class="card-block">

          <div class="form-group">
            <label for="type" class="control-label">Name </label>
            <input class="form-control" type="text" v-model="item.name" />
          </div>

          <div class="form-group">
            <label for="type" class="control-label">Slug </label>
            <input class="form-control" type="text" v-model="item.slug" />
          </div>

          <div class="form-group">
            <label for="group" class="control-label">Group </label>
            <select class="form-control" v-model="item.group">
              <option value="">Select a Group</option>
              <option v-for="option in groupOptions" v-bind:value="option.value">
                {{ option.text }}
              </option>
            </select>
          </div>

      </div>
      <div class="card-block">

        <button class="btn btn-primary" @click="saveItem" v-show="valid">Save</button>
        <button class="btn btn-danger" v-show="!valid">Missing Fields</button>

      </div>
    </div> 
  </div>
</template>

<script>
export default {

  components: {

  },

  data() {
    return {
      resource: null,
      id: null,
      item: null,
      groupOptions: [
        { text:'Blog', value:'blog'},
        { text:'Product', value:'product'}
      ],
    }
  }, 

  computed: {
    valid() {
      return this.validate();
    }
  },

  methods: {

    validate() {
      if (!this.item) return false;
      if (!this.item.group) return false;
      if (!this.item.name) return false;
      if (!this.item.slug) return false;
      return true;
    },

    saveItem() {
      if (!this.validate()) return;

      this.resource.update({id: this.id}, this.item).then((response) => {
        this.$router.go({name: 'category-index'});
      });
    },


    getItem() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('item', response.data);
      }); 
    }

  },

  ready() {
    var resource = this.$resource('category{/id}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItem();
  }  
}
</script>