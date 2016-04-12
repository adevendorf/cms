<template>
  <ol class="breadcrumb">
    <li><a href="#">Dashboard</a></li>
    <li class="active">Products</li>
  </ol>  



  <div class="content">

    <div class="loading" v-if="!items"><div class="spinner"></div></div>

    <table class="table table-bordered table-striped table-hover" v-if="items">
      <thead>
        <tr>
          <th>SKU</th>
          <th>Name</th>
          <th>Price</th>
          <th>Discount</th>
          <th>Inventory</th>
          <th class="col-action"></th>
        </tr>
      </thead>
      <tr v-for="item.items">
        <td><a v-link="{ name: 'extension-edit', params: { id: item.id }}">{{ item.sku }}</a></td>
        <td><a v-link="{ name: 'extension-edit', params: { id: item.id }}">{{ item.name }}</a></td>
        <td>{{ item.price }}</td>
        <td>{{ item.discount }}</td>
        <td>{{ item.inventory }}</td>
        <td class="col-action">
          <a class="btn btn-primary" v-link="{ name: 'product-edit', params: { id: item.id }}"><i class="fa fa-pencil"></i> Edit</a>
          <button type="submit" @click="remove(item.id, $event)" class="btn btn-danger"><i class="fa fa-times"></i> Remove</button>
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

</template>

<script>
export default {
  data() {
    return {
      // addFormVisible: false,
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

    // showAddForm: function() {
    //   this.$data.addFormVisible = true;
    // },

    addItem: function(e) {
      e.preventDefault();

      if (!this.$data.content) return;



      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');

      this.$http.post('/api/v1/product',
        this.content, 
        function(data, status, request) {
            this.$data.items.push(data);
            this.$data.content = {
              type: '',
              key: '',
              value: ''
            };
      }).error(function(data, status, request) {
          System.handleError(status);
      });
    },

    remove: function(id, e) {
      e.preventDefault();
      
      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value')
      this.$http.delete('/api/v1/product/' + id, function(data, status, request) {

        $(e.target).closest('tr').hide(150);
        
      }).error(function(data, status, request) {
        System.handleError(status);
      });
    },

    getItems: function(pageNumber) {

      this.$http.get('/api/v1/product/?page=' + (pageNumber ? parseInt(pageNumber) : this.$data.current_page), function (data, status, request) {
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