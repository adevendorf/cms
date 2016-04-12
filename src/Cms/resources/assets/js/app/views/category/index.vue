<template>

<div class="content">
  
  <div class="card card-block">
    <div class="row">
      <div class="col-sm-3">
        <h3>Category</h3>
        <hr />
        <a @click="addItem" class="btn btn-primary">New Category</a>
      </div>
      <div class="col-sm-6"></div>
      <div class="col-sm-3">
        <div class="from-group">
          <label>Group</label>
          <select class="form-control" name="type" v-model="group" @change=" getItems(1)">
            <option value="">All</option>
            <option value="blog">Blog</option>
            <option value="product">Product</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <loader :show="!items.current_page"></loader>

  <table class="table table-striped table-hover" v-if="items.current_page">
    <thead class="">
      <tr>
        <th>Name</th>
        <th>Group</th>
        <th>Slug</th>        
        <th></th>
      </tr>
    </thead>
    <tr v-for="item in items.data">

      <td><a v-link="{ name: 'category-edit', params: { id: item.id }}">{{ item.name ? item.name : '-- no name --' }}</a></td>
      <td><a v-link="{ name: 'category-edit', params: { id: item.id }}">{{ item.group ? item.group : '-- no group --' }}</a></td>
      <td><a v-link="{ name: 'category-edit', params: { id: item.id }}">{{ item.slug ? item.slug : '-- no slug --' }}</a></td>

      <td class="text-xs-right">
        <a class="btn btn-primary" v-link="{ name: 'category-edit', params: { id: item.id }}">Edit</a>
        <a class="btn btn-danger" @click="confirmRemoval(item.id)" >Remove</a>
      </td>
    </tr>
  </table>  


  <paginator :items="items" v-on:next="nextPage" v-on:prev="prevPage"></paginator>
  <confirm v-on:confirm="removeItem"></confirm>
</div>

  
</template>

<script>
export default {

  components: {

  },

  data() {
    return {
      group: '',
      content: {
        name: '',
        slug: ''
      },
      resource: null,
      items: {
        data: [],
        total_pages: 1,
        current_page: false,
        last_page: 1
      },
      toRemove: null
    } 
  },

  methods: {
    nextPage() {
      if (this.items.current_page == this.items.last_page) return;
      this.getItems( this.items.current_page + 1);

    },

    prevPage() {
      if (this.items.current_page == 1) return;
      this.getItems( this.items.current_page - 1);
    },

    showAddForm() {
      this.$data.addFormVisible = true;
    },

    confirmRemoval(id) {
      this.toRemove = id;
      this.$broadcast('confirm::ask');
    },

    removeItem() {
      this.clearItemFromList(this.toRemove);
      this.resource.delete({id: this.toRemove});
    },

    clearItemFromList(id) {
      for (var i = 0; i < this.items.data.length; i++) {
        if (this.items.data[i].id == id) {
          this.items.data.splice(i, 1);
          break;
        }
      }
    },

    remove(e, id) {
      $(e.target).closest('tr').hide(150);
      this.$http.delete('/api/v1/category/' + id, function(data, status, request) {
        console.log(data, status, request);
      });
    },

    addItem(e) {
      this.$http.post('/api/v1/category', function(data, status, request) {
          this.$router.go({name: 'category-edit', params: { id: data.id }});
      })
    },

    getItems(pageNumber) {

      var options = {
        group: this.group,
        page: pageNumber ? pageNumber : 1
      };

      this.resource.get(options).then((response) => {
          this.$set('items', response.data);
      }); 

    }

  },

  events: {
    'confirm::confirmed'(id) {
      this.removeItem(id);
      return false;
    }
  },

  ready() {
    var resource = this.$resource('category{/id}');
    this.$set('resource', resource);
    this.getItems(1);
  }  
}
</script>