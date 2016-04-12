<template>

  <div class="content">
    
    <div class="card card-block">
      <div class="row">
        <div class="col-sm-3">
          <h3>Extension</h3>
          <hr />
          <a @click="addItem" class="btn btn-primary">New Extension</a>
        </div>
      </div>
    </div>

    <loader :show="!items.current_page"></loader>

    <table class="table table-striped table-hover" v-if="items.current_page">
      <thead>
        <tr>
          <th>Type</th>
          <th>Key</th>
          <th>Value</th>          
          <th></th>
        </tr>
      </thead>
      <tr v-for="item in items.data">
        <td><a v-link="{ name: 'extension-edit', params: { id: item.id }}">{{ item.type ? item.type : '-- no type --' }}</a></td>
        <td><a v-link="{ name: 'extension-edit', params: { id: item.id }}">{{ item.key ? item.key : '-- no key --' }}</a></td>
        <td><a v-link="{ name: 'extension-edit', params: { id: item.id }}">{{ item.value ? item.value : '-- no value --' }}</a></td>
        
        <td class="text-xs-right">
          <a class="btn btn-primary" v-link="{ name: 'extension-edit', params: { id: item.id }}">Edit</a>
          <a @click="confirmRemoval(item.id)" class="btn btn-danger">Remove</a>
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
      item: {
        type: '',
        key: '',
        value: ''
      },
      resource: null,
      items: {
        data: [],
        total_pages: 1,
        current_page: 1,
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


    addItem(e) {

      if (!this.item.type || !this.item.key || !this.item.value) return;

      this.resource.save(this.item).then((response) => {
        this.items.data.push(response.data);
        this.item = {
          type: '',
          key: '',
          value: ''
        };
      });
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

    getItems(pageNumber) {
      var options = {
        page: pageNumber ? pageNumber : 1
      };

      this.resource.get(options).then((response) => {
          this.$set('items', response.data);
      }); 
    },

    addItem() {
      this.resource.save({}).then((response) => {
        this.$router.go({name: 'extension-edit', params: {id: response.data.id} });
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
    var resource = this.$resource('extension{/id}');
    this.$set('resource', resource);
    this.getItems(1); 
  }  
}
</script>