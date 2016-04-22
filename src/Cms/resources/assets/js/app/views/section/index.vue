<template>
<div class="content">
      <div class="card card-block">
    <div class="row">
      <div class="col-sm-3">
        <h3>Site Section</h3>
        <hr />
        <a @click="addItem" class="btn btn-primary">New Site Section</a>
      </div>
    </div>
  </div>

  <loader :show="!items.current_page"></loader>

  <table class="table table-striped table-hover" v-if="items.current_page">
    <thead>
      <tr>
        <th>Name</th>
        <th>Path</th>          
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="item in items.data">       
        <td><a v-link="{ name: 'section-edit', params: { id: item.id }}">{{ item.name ? item.name : '-- no name --' }}</a></td>
        <td>{{ item.path ? item.path : '-- no path --' }}</td>
        <td class="text-xs-right">
          <a class="btn btn-primary" v-link="{ name: 'section-edit', params: { id: item.id }}">Edit</a>
          <a @click="confirmRemoval(item.id)" class="btn btn-danger">Remove</a>
        </td>
      </tr>
    </tbody>
  </table>  

  <paginator :items="items" v-on:next="nextPage" v-on:prev="prevPage"></paginator>
  <confirm v-on:confirm="removeItem"></confirm>
</div> 
</template>

<script>
export default {


  data() {
    return {
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
      this.$http.delete('/api/v1/section/' + id, function(data, status, request) {
        console.log(data, status, request);
      });
    },

    addItem(e) {
      this.$http.post('/api/v1/section', function(data, status, request) {
        this.$router.go({name: 'section-edit', params: { id: data.id }});
      });
    },

    getItems(pageNumber) {

      var options = {
        page: pageNumber ? pageNumber : 1,
        count: 50
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
    var resource = this.$resource('section{/id}');
    this.$set('resource', resource);
    this.getItems(1);   
  }  
}
</script>