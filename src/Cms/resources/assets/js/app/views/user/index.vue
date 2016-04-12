<template>

  <div class="content">

    <div class="card card-block">
      <div class="row">
        <div class="col-sm-3">
          <h3>User</h3>
        </div>
      </div>
    </div>

<loader :show="!items.current_page"></loader>

    <table class="table table-striped table-hover" v-if="items.current_page">
      <thead>
        <tr>
          <th>Name</th>
          <th>E-mail</th>
          <th>Level</th>          
          <th class="text-center"></th>
        </tr>
      </thead>
      <tr v-for="item in items.data">
        <td><a v-link="{ name: 'user-edit', params: { id: item.id }}">{{ item.name }}</a></td>
        <td><a v-link="{ name: 'user-edit', params: { id: item.id }}">{{ item.email }}</a></td>
        <td><a v-link="{ name: 'user-edit', params: { id: item.id }}">{{ item.user_level }}</a></td>
        <td class="text-center">
          <a class="btn btn-primary" v-link="{ name: 'user-edit', params: { id: item.id }}">Edit</a>
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


  data() {
    return {
      resource: null,
      items: {
        data: [],
        total_pages: 1,
        current_page: false,
        last_page: 1
      },
      toRemove: false
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


    // addItem() {

    //   if (!this.content) return;

    //   this.resource.save(this.content).then((response) => { 
    //     if (response.data.success) {
    //       this.getItems(1);
    //     }
    //   });
    // },


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
    }

  },

  events: {
    'confirm::confirmed'() {
      this.removeItem();
      return false;
    }
  },    

  ready() {
    var resource = this.$resource('user{/id}');
    this.$set('resource', resource);
    this.getItems(1);
 
  }  
}
</script>