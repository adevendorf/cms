<template>
<div>
  <div class="loading" v-if="!items.data"><div class="spinner"></div></div>

  <div class="row">

    <div class="col-sm-6">
      <div class="form-group">
        <label>Title</label>
        <input class="form-control" @keyup.enter="filter($event)" type="text" v-model="filterTerm" />
      </div>
    </div>

    <div class="col-sm-4">
      <div class="form-group">
        <label>Status</label>
        <select class="form-control" v-model="filterStatus" @change="filter($event)">
          <option value="">All</option>
          <option value="published">Published</option>
          <option value="scheduled">Scheduled</option>
          <option value="hidden">Hidden</option>
        </select>
      </div>
    </div>

    <div class="col-sm-2">
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

  <table class="table table-striped table-hover" v-if="items.data.length">
    <tr v-for="item in items.data">
      <td>
        <span v-if="item.status == 'published'" class="label label-success">{{ (item.status).toUpperCase().substring(0,1) }}</span>
        <span v-if="item.status == 'scheduled'" class="label label-warning">{{ (item.status).toUpperCase().substring(0,1) }}</span>
        <span v-if="item.status == 'hidden'" class="label label-default">{{ (item.status).toUpperCase().substring(0,1) }}</span>
        <a @click="selectItem({id: item.id, title: item.title})">{{ item.title }}</a>
      </td>
      <td>{{ item.section ? item.section.name : '-' }}</td>
      <td><ago :timestamp="item.updated_at"></ago></td>
    </tr>
  </table>  

  <div>Results: {{ items.total }}</div>

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
  props: ['parentId'],

  components: {
  },

  data() {
    return {
      resource: null,
      items: {
        data: [],
        last_page: 1,
        current_page: 1,
      },
      filterTerm: '',
      filterStatus: 'published',
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
        options.title = this.filterTerm;
      }

      if (this.filterStatus != '') {
        options.status = this.filterStatus;
      }
        
 
      this.resource.get(options).then((response) => {
          this.$set('items', response.data);
      }); 
    }

  },

  ready() {
    var resource = this.$resource('page{/id}');
    this.$set('resource', resource);
    this.getItems(1);
  }  
}
</script>