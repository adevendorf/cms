<template>
  <ol class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li class="active">ACL</li>
  </ol>  

    
    <div class="panel panel-default">
      <div class="panel-body">
        <a v-if="!addFormVisible" @click="showAddForm" class="btn btn-primary"><i class="fa fa-plus"></i> New Section</a>
        <span v-if="addFormVisible">
          <div class="form-group col-md-2">
            <label for="styling" class="control-label">Name: </label>
            <input class="form-control" name="name" type="text" v-model="content.name">
          </div>
          <div class="form-group col-md-2">
            <label for="styling" class="control-label">Slug: </label>
            <input class="form-control" name="slug" type="text" v-model="content.slug">
          </div>
          <div class="form-group col-md-12">   
            <input class=" btn btn-primary" type="submit" value="Add Section" @click="addItem($event)">
          </div>
        </span>
      </div>
    </div>

    <div class="loading" v-if="!items"><div class="spinner"></div></div>
    <table class="table table-bordered table-striped table-hover" v-if="items">
    <tr>
      <th>Name</th>
      <th>Slug</th>
      <th>Action</th>
    </tr>
    <tr v-for="items">
      <td><a v-link="{ name: 'acl-edit', params: { id: id }}">{{ name }}</a></td>
      <td><a v-link="{ name: 'acl-edit', params: { id: id }}">{{ slug }}</a></td>
      <td>
        <a><button type="submit" @click="remove(id, $event)" class="btn btn-danger"><i class="fa fa-times"></i> Delete</button></a></td>
      </tr>
    </table>  
    <nav v-if="total_pages > 1">
      <ul class="pagination">
        <li>
          <a href="#" @click="prevPage($event)">
            <span>&laquo;</span>
          </a>
        </li>
        <li>
          <a href="#">
            <span>{{ current_page }} of {{ total_pages }}</span>
          </a>
        <li>
          <a href="#" @click="nextPage($event)">
            <span>&raquo;</span>
          </a>
        </li>
      </ul>
    </nav> 



</template>

<script>
export default {
  data() {
    return {
      addFormVisible: false,
      content: {
        name: '',
        slug: ''
      },
      items: false,
      total_pages: 1,
      current_page: 1
    } 
  },

  methods: {
    nextPage: function(e) {
      e.preventDefault();
      if (this.$data.current_page == this.$data.last_page) return;
      this.getItems( this.$data.current_page + 1);

    },

    prevPage: function(e) {
      e.preventDefault();
      if (this.$data.current_page == 1) return;
      this.getItems( this.$data.current_page - 1);
    },

    showAddForm: function() {
      this.$data.addFormVisible = true;
    },

    addItem: function(e) {
      e.preventDefault();

      if (!this.$data.content) return;

      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');

      this.$http.post('/api/v1/acl/create', 
        this.content, 
        function(data, status, request) {
          if (data.success) {
            this.getItems();
          }
      }).error(function(data, status, request) {
          System.handleError(status);
      });
    },


    remove: function(id, e) {
      e.preventDefault();
      $(e.target).closest('tr').hide(150);
      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value')
      this.$http.delete('/api/v1/acl/' + id, function(data, status, request) {
        console.log(data, status, request);
      }).error(function(data, status, request) {
        System.handleError(status);
      });
    },

    getItems: function(pageNumber) {

      this.$http.get('/api/v1/acl/?page=' + (pageNumber ? parseInt(pageNumber) : this.$data.current_page), function (data, status, request) {
        this.$set('items', data.data);
         this.$set('total_pages', Math.round(data.total / data.per_page));
        this.$set('current_page', data.current_page);
      }).error(function (data, status, request) {
        System.handleError(status);
      });

    }

  },

  ready() {
    this.getItems(1);
  }  
}
</script>