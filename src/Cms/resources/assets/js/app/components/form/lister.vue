<template>
<div>  
  <div class="loading" v-if="!items"><div class="spinner"></div></div>

    <table class="table table-bordered table-striped table-hover" v-if="items">
    <tr>
      <th>Form</th>
    </tr>
    <tr v-for="item in items.data">
      <td>
        <a @click="selectItem({id: item.id, title: item.title})">{{ item.title }}</a>
      </td>
      </tr>
    </table>  
    <nav v-if="total_pages > 1">
      <ul class="pagination">
        <li>
          <a @click="prevPage">
            <span>&laquo;</span>
          </a>
        </li>
        <li>
          <a href="#">
            <span>{{ items.current_page }} of {{ items.total_pages }}</span>
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
  data() {
    return {
      resource: null,
      items: {
        data: [],
        last_page: 1,
        current_page: 1,
        total_pages: 1
      }
    } 
  },

  methods: {
    
    nextPage() {
      if (this.$data.current_page == this.$data.last_page) return;
      this.getItems( this.$data.current_page + 1);

    },

    prevPage() {
      if (this.$data.current_page == 1) return;
      this.getItems( this.$data.current_page - 1);
    },

    selectItem(data) {
      this.$dispatch('lister::selected', data);
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
    var resource = this.$resource('form{/id}');
    this.$set('resource', resource);
    this.getItems(1);
  }  
}
</script>