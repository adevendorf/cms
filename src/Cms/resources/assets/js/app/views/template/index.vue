<template>

    <ol class="breadcrumb">
      <li><a href="#">Dashboard</a></li>
      <li class="active">Templates</li>
    </ol>  

    <div class="content">

      <div class="row">
        <div class="col-sm-12">
          <div class="search">
            <a @click="addPage" class="btn btn-primary"><i class="fa fa-caret-right"></i> New Template</a>
          </div>
        </div>
      </div>

     <hr />

     <div class="row">


      <div class="col-sm-12">

        <div class="loading" v-if="!pages"><div class="spinner"></div></div>
        <table class="table" v-if="pages">
        <thead>
          <tr>
            <th>Title</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tr v-for="pages">
          <td>
            <a v-link="{ name: 'page-edit', params: { id: id }}">{{ title }}</a>
          </td>
          <td class="text-center">
            <a v-link="{ name: 'page-edit', params: { id: id }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
            <a @click="removePage(id, $event)" class="btn btn-sm btn-danger {{ internal_slug ? 'disabled' : '' }}"><i class="fa fa-times"></i></a>
          </td>
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
                <span>{{ current_page }} of {{ total_pages }}</span>
            <li>
              <a href="#" @click="nextPage($event)">
                <span>&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>   
      </div>
    </div>
    <div class="row"></div>
  </div>
</template>

<script>
export default {

  data() {
    return {
      filterStatus: '',
      pages: false,
      current_page: 1,
      last_page: 1,
      total_pages: 1
    }
  },

  methods: {

    filter: function(e){
      e.preventDefault();
      this.$data.pages = [];
      this.getItems();
    },

    nextPage: function(e) {
      e.preventDefault();
      if (this.$data.current_page == this.$data.last_page) return;

      this.$data.current_page++;
      this.getPages();

    },

    prevPage: function(e) {
      e.preventDefault();
      if (this.$data.current_page == 1) return;

      this.$data.current_page--;
      this.getPages();
    },



    addPage: function() {
      this.$http.get('/api/v1/page/create', function(data, status, request) {
          router.go({name: 'page-edit', params: { id: data.id }});
      }).error(function(data, status, request) {
        System.handleError(status);
      });
    },



    removePage: function(id, e) {
      e.preventDefault();
      $(e.target).closest('tr').hide(150);
      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value')
      this.$http.delete('/api/v1/page/' + id, function(data, status, request) {
      }).error(function(data, status, request) {
        $(e.target).closest('tr').show(150);
        System.handleError(status);
      });
    },



    getItems: function(pageNumber) {

      var filtering = {
        type: 'template',
        page: (pageNumber ? parseInt(pageNumber) : this.$data.current_page),
      };

      if (this.$data.filterStatus != '') filtering.status = this.$data.filterStatus;

      this.$http.get('/api/v1/page/', filtering, function (data, status, request) {
        this.$set('pages', data.data);
        this.$set('total_pages', Math.ceil(data.total / data.per_page));
        this.$set('current_page', data.current_page);
        this.$set('last_page', data.last_page);
      }).error(function (data, status, request) {
        System.handleError(status);
      });
    },


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

  },


  ready() {
    this.getItems(1);
  }  

}
</script>