<template>
  <div class="content">
    <div class="card card-block">
      <div class="row">
        <div class="col-sm-3">
        <a @click="addItem($event)" class="btn btn-primary"> New Form</a>
      </div>
    </div>
  
    </div>

    <div class="loading" v-if="!items"><div class="spinner"></div></div>

    <table class="table" v-if="items">
    <thead class="thead-inverse">
      <th>Title</th>
      <th>Redirect To</th>
      <th>E-mail To</th>
      <th>Action</th>      
    </thead>
    <tr v-for="items">
      <td><a v-link="{ name: 'form-edit', params: { id: id }}">{{ title }}</a></td>
      <td><a v-link="{ name: 'form-edit', params: { id: id }}">{{ redirect_to }}</a></td>
      <td><a v-link="{ name: 'form-edit', params: { id: id }}">{{ email_to }}</a></td>
      <td>
        <a v-link="{ name: 'form-edit', params: { id: id }}" class="btn btn-primary"><i class="fa fa-pencil"></i> Edit</a>
        <button type="submit" @click="remove(id, $event)" class="btn btn-danger"><i class="fa fa-times"></i> Delete</button>
      </td>
    </tr>
    </table>  


        <nav v-if="items.last_page > 1">
          <ul class="pagination">
            <li class="page-item">
              <a class="page-link" @click="prevPage">
                <span>&laquo;</span>
              </a>
            </li>
            <li class="page-item">
              <a class="page-link">
                <span>{{ items.current_page }} of {{ items.last_page }}</span>
              </a>
            <li class="page-item">
              <a class="page-link" @click="nextPage">
                <span>&raquo;</span>
              </a>
            </li>
          </ul>
        </nav> 

  </div>


</template>

<script>
export default {
  data() {
    return {
      content: {
        type: '',
        key: '',
        value: ''
      },
      items: false,
      total_pages: 1,
      current_page: 1
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



    remove: function(id, e) {
      e.preventDefault();
      $(e.target).closest('tr').hide(150);
      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value')
      this.$http.delete('/api/v1/form/' + id, function(data, status, request) {
      }).error(function(data, status, request) {
        System.handleError(status);
      });
    },

    getItems: function(pageNumber) {

      this.$http.get('/api/v1/form/?page=' + (pageNumber ? parseInt(pageNumber) : this.$data.current_page), function (data, status, request) {
        console.log(data, status, request);
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