<template>

<div class="content">


  <div class="card card-block">
    <div class="row">
      <div class="col-sm-3">
        <h3>News Feeds</h3>
      </div>
    </div>
  </div>

  <loader :show="!items.current_page"></loader>

  <table class="table table-striped table-hover" v-if="items.current_page">
    <thead>
      <tr>
      <th>Name</th>       
      <th class="text-center"></th>
      </tr>
    </thead>
    <tr v-for="item in items.data">
      <td><a v-link="{ name: 'newsfeed-edit', params: { id: item.id }}">{{ item.name }}</a></td>
      <td class="text-xs-right">
        <a class="btn btn-info" v-link="{ name: 'newsfeed-edit', params: { id: item.id }}">Edit</a>
      </td>
    </tr>
  </table>  


  <paginator :items="items" v-on:next="nextPage" v-on:prev="prevPage"></paginator>
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
        current_page: 1,
        last_page: 1
      }
    } 
  },

  methods: {
    nextPage(e) {
      e.preventDefault();
      if (this.$data.current_page == this.$data.last_page) return;
      this.getItems( this.$data.current_page + 1);

    },

    prevPage(e) {
      e.preventDefault();
      if (this.$data.current_page == 1) return;
      this.getItems( this.$data.current_page - 1);
    },

    showAddForm() {
      this.$data.addFormVisible = true;
    },


    getItems(pageNumber) {

      var options = {
        page: pageNumber ? pageNumber : 1
      };

      this.resource.get(options).then((response) => {
          this.$set('items', response.data);
      }); 

    }

  },

  ready() {
    var resource = this.$resource('section{/id}');
    this.$set('resource', resource);
    this.getItems(1);

    this.$on('confirm::confirmed', (id) => {
      this.removeItem(id);
      return false;
    });   
  }  
}
</script>