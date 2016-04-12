<template>


  <div class="content">


    <div class="card card-block">
      <div class="row">
        <div class="col-sm-3">
          <h3>Form</h3>
          <hr />
          <a @click="addItem" class="btn btn-primary">New Form</a>
        </div>
      </div>
    </div>

    <loader :show="!items.current_page"></loader>

    <table class="table table-striped table-hover" v-if="items.current_page">
      <thead>
        <tr>
          <th>Form</th>
          <th class="text-center">View</th>
          <th class="text-center">Download</th>
          <th class="text-center"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items.data">
          <td>
            <a v-link="{ name: 'form-edit', params: { id: item.id }}">{{ item.title ? item.title : '-- no title --' }}/a>
          </td>
          <td class="text-center">
            <a class="btn btn-success" v-link="{ name: 'formsubmission-index', params: { id: item. id }}">Results</a>
          </td>
          <td class="text-center">
            <a class="btn btn-success" @click="exportData(item.id)">Download</a>        
          </td>

          <td class="text-xs-right">
            <a class="btn btn-primary" v-link="{ name: 'form-edit', params: { id: item.id }}">Edit</a>
            <a class="btn btn-info">Clone</a>
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

  components: {

  },

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
    };
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

    confirmRemoval(id) {
      this.$broadcast('confirm::ask', id);
    },

    removeItem(id) {
      this.resource.delete({id}).then((response) => {
        this.clearItemFromList(id);
      });
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

    exportData(id) {
      window.location.href = '/api/v1/form/' + id + '/export/excel';
    },

    addItem() {
      this.resource.save().then((response) => {
        this.$router.go({name: 'form-edit', params: {id: response.data.id} });
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
    var resource = this.$resource('form{/id}');
    this.$set('resource', resource);
    this.getItems(1);
  }  
}
</script>