<template>
<div>
  <div class="loading" v-if="!items.data"><div class="spinner"></div></div>

  <div class="row">

    <div class="col-sm-6">
      <div class="form-group">
        <label>Username</label>
        <input class="form-control" @keyup.enter="filter($event)" type="text" v-model="filterTerm" />
      </div>
    </div>

    <div class="col-sm-6">
      <div class="form-group">
        <label>Per Page</label>
        <select class="form-control" v-model="filterCount" @change="filter($event)">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
    </div>


  </div>

  <table class="table table-bordered table-striped table-hover" v-if="items.data.length">
    <tr v-for="item in items.data">
      <td>
        <a @click="selectItem({id: item.id, name: item.name})">{{ item.name }}</a>
      </td>
      <td>{{item.status}}</td>
    </tr>
  </table>  

  <div>Results: {{ items.total }}</div>

  <nav v-if="items.last_page > 1">
    <ul class="pagination">
      <li>
        <a @click="prevPage">
          <span>&laquo;</span>
        </a>
      </li>
      <li>
        <a>
          <span>{{ items.current_page }} of {{ items.last_page }}</span>
        </a>
      <li>
        <a @click="nextPage">
          <span>&raquo;</span>
        </a>
      </li>
    </ul>
  </nav> 
</div>
</template>

<script>
export default {
  props: ['parentId'],

  data() {
    return {
      resource: null,
      items: {
        data: [],
        last_page: 1,
        current_page: 1,
      },
      filterTerm: '',
      filterCount: 10
    } 
  },

  methods: {

    filter($event) {
      $event.preventDefault();
      this.getItems(1);
    },
    
    nextPage() {
      if (this.items.current_page == this.items.last_page) return;
      this.getItems( this.items.current_page + 1);

    },

    prevPage() {
      if (this.items.current_page == 1) return;
      this.getItems( this.items.current_page - 1);
    },

    selectItem(data) {
      this.$dispatch('lister::selected', data);
    },

    getItems(pageNumber) {
      var options = {
        page: pageNumber ? pageNumber : 1,
        count: this.filterCount
      };

      if (this.filterTerm != '') {
        options.name = this.filterTerm;
      }     
       
 
      this.resource.get(options).then((response) => {
          this.$set('items', response.data);
      }); 
    }

  },

  ready() {
    var resource = this.$resource('user{/id}');
    this.$set('resource', resource);
    this.getItems(1);
  }  
}
</script>