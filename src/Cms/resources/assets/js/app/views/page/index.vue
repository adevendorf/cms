<template>
<div class="content">

  <div class="card card-block">
    <div class="row">
      <div class="col-sm-3">
        <h3>Page</h3>
        <hr />
        <a @click="addItem" class="btn btn-primary">New Page</a>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Seach Titles</label>
          <input type="text" class="form-control" v-model="filter.title" @keyup.enter="filterItems($event)" placeholder="Page Title..." /> 
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label>Filter Status</label>
          <select class="form-control" v-model="filter.status" @change="filterItems($event)">
            <option value="">All</option>
            <option value="published">Published</option>
            <option value="scheduled">Scheduled</option>
            <option value="hidden">Hidden</option>
          </select>
        </div>
      </div>
       <div class="col-sm-3">
        <div class="form-group">
          <label>Filter Section</label>
          <select class="form-control" v-model="filter.section" @change="filterItems($event)">
            <option value="">All</option>
            <option v-for="option in sections" v-bind:value="option.id">
              {{ option.name }}
            </option>
          </select>  
        </div>
      </div>
    </div>
  </div>
 

  <loader :show="!items.current_page"></loader>    


  <div class="row" v-show="items.data.length">
    <div class="col-sm-12">
      <table class="table table-striped table-hover" v-if="items.current_page">
      <thead>
        <tr>
          <th></th>
          <th>Title</th>
          <th>Section</th>
          <th>Last Edit</th>
          <th></th>
        </tr>
      </thead>
      <tr v-for="item in items.data">      
        <td class="text-xs-center"> 
            <span v-if="item.status == 'published'" class="label label-success">{{ (item.status).toUpperCase().substring(0,1) }}</span>
            <span v-if="item.status == 'scheduled'" class="label label-warning">{{ (item.status).toUpperCase().substring(0,1) }}</span>
            <span v-if="item.status == 'hidden'" class="label label-default">{{ (item.status).toUpperCase().substring(0,1) }}</span>
        </td>          
        <td>
          <a v-link="{ name: 'page-edit', params: { id: item.id }}">{{ item.title }}</a>
          <div class="small">/{{ item.route.url }}</div>
        </td>
        <td>{{ item.section ? item.section.name : '-' }}</td>
        <td><ago :timestamp="item.updated_at"></ago></td>
        <td class="text-xs-right">
          <a v-link="{ name: 'page-edit', params: { id: item.id }}" class="btn btn-primary">Edit</a>
          <a class="btn btn-info">Clone</a>
          <a @click="confirmRemoval(item.id)" :class="'btn btn-danger'">Remove</a>
        </td>
        </tr>
      </table> 
    </div>
  </div>

  <paginator :items="items" v-on:next="nextPage" v-on:prev="prevPage"></paginator>  
  <confirm v-on:confirm="removeItem"></confirm>

</div>


</template>

<script>
export default {


  data() {
    return {
      filter: {
        status: '',
        title: '',
        section: ''
      },
      resource: null,
      items: {
        data: [],
        last_page: 1,
        current_page: false,
      },
      orderBy: 'last_edit',
      sectionResource: null,
      sections: [],
      toRemove: null,
    }
  },

  methods: {

    

    filterItems(event) {
      event.preventDefault();  
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


    addItem() {
      var pageData = {
        type: 'page'
      };

      this.resource.save(pageData).then((response) => {
        this.$router.go({name: 'page-edit', params: { id: response.data.id }});
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

    getSections(pageNumber) {
      var options = {
        count: 1000
      };
      this.sectionResource.get(options).then((response) => {
        this.$set('sections', response.data.data);
      });
    },


    getItems(pageNumber) {
      var options = {
        type: 'page',
        page: (pageNumber ? parseInt(pageNumber) : this.items.current_page),
        order_by: this.orderby
      };
      if (this.filter.status !== '') options.status = this.filter.status;
      if (this.filter.title !== '') options.title = this.filter.title;
      if (this.filter.section !== '') options.section_id = this.filter.section;

      this.resource.get(options).then((response) => {
        this.$set('items', response.data);
        // this.$set('sections', response.data.sections);
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
    var resource = this.$resource('page{/id}');
    this.$set('resource', resource);
    this.getItems(1); 

    var sectionResource = this.$resource('section{/id}');
    this.$set('sectionResource', sectionResource);
    this.getSections(1); 
  }  

}
</script>