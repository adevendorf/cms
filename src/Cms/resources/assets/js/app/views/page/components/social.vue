<template>
<div>
  <div class="row">

    <div class="col-sm-12">
      <div class="form-group">
        <label for="section" class="control-label">Category </label>
        <select v-model="page.category_id" class="form-control" @change="saveParent">
          <option v-for="option in categories.data" v-bind:value="option.id">
            {{ option.name }}
          </option>
        </select>  
      </div> 
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="form-group">
          <label for="author_id" class="control-label">Author </label>      
          <div>
            <a class="btn btn-primary" @click="openSelector">Select Author</a> 
            <span>{{ userName }}</span> 
          </div>
      </div>
    </div>
  </div>

  <div class="row">
      <div class="col-sm-12">
      <div class="form-group">
        <label for="section" class="control-label">Social Options</label>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="allow_comments" v-model="page.allow_comments" value="1" @change="saveParent"> Allow Comments
          </label>
        </div>
      </div> 
    </div>
  </div>  

  <modal
    size="large"
    footer="true"
    header="true"
    :show.sync="modalVisible"
    >
    <div slot="header">
      Select the Author
    </div>
    <div slot="body">
      <lister 
        :parent-id="page.id"
        >
      </lister>
    </div>
    <div slot="footer">
      <button class="btn btn-default" @click="modalVisible = false">Cancel</button>
    </div>
  </modal>
</div>           

</template>

<script>

import lister from '../../../components/user/lister.vue';

export default {
  props: ['page'],

  components: {
    lister
  },

  data() {
    return {
      resource: null,
      categories: {
        data: []
      },
      selectorOptions: {
        parentType: 'user'
      },
      userName: '',
      modalVisible: false
    }
  },

  methods: {
    openSelector() {
      this.modalVisible = true;
    },

    saveParent() {
      this.$dispatch('parent::save');
    },

    getCategories(pageNumber) {
      var options = {
        group: 'blog',
        count: 1000
      };

      this.resource.get(options).then((response) => {
        this.$set('categories', response.data);
      });
    }
  },

  events: {
    'lister::selected'(data) {   
      this.page.author_id = data.id;
      this.userName = data.name
      this.saveParent();
      this.modalVisible = false;
      return false;
    }
  },

  compiled() {
    var resource = this.$resource('category');
    this.$set('resource', resource);
    this.getCategories();
    if (this.page.author) this.userName = this.page.author.name;
  },  
}
</script>