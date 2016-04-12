<template>

  <div class="modal-body"> 

    <div class="row">
      <div class="col-sm-12">

        <div class="row"> 
          <div class="col-sm-6">
            <div class="form-group">
            <select class="form-control" v-model="folder" @change="searchOnEnter" v-if="folderOptions">
              <option v-for="option in folderOptions" v-bind:value="option.value">
                {{ option.text }}
              </option>
            </select>   
            </div>
          </div>
          <div class="col-sm-6 text-right form-inline">
            <div class="form-group">
              <input type="text" class="form-control" v-model="keyword" @keyup="searchOnEnter" />
              <button class="btn btn-info btn-inline" @click="keywordSearch" >Search</button>
            </div>      
          </div>
        </div>

        <hr />

        <div class="loading" v-if="!items"><div class="spinner"></div></div>

        <div class="row">
          <div class="col-sm-12">    
            <div class="asset" v-for="item in items.data">
              <a class="center-block"  @click="selectItem(item)" v-bind:style="'background-image:url(/img/asset/preview/' + item.path + item.cms_filename + ')'"></a>
            </div>
            <div style="clear: left"></div>
          </div>

          <div class="row"></div>

          <nav v-if="items.last_page > 1">
            <ul class="pagination">
              <li>
                <a @click="prevPage">
                  <span>&laquo;</span>
                </a>
              </li>
              <li>
                <a href="#">
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

      </div>
    </div>

  </div>

</template>

<script>
import menu from '../partials/menu.vue';

export default {

  components: {
    menu
  },


  data() {
    return {
      keyword: '',
      folder: '',
      folderOptions: false,
      items: {
        data: [],
        last_page: 1,
        total_pages: 1,
        current_page: 1
      },
      resource: null
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

    selectItem(item) {
      this.$dispatch('image::selected', item);
    },

    searchOnEnter(e) {
      if (e.keyCode == 13) this.keywordSearch(e);
    },


    keywordSearch() {

      // this.items = false;

      //  this.$http.get('/api/v1/asset/?keyword=' + this.keyword + '&page=1', function (data, status, request) {
      //   this.$set('items', data.data);
      //   this.$set('total_pages', Math.round(data.total / data.per_page));
      //   this.$set('current_page', data.current_page);
      // });

    },

    setPath(asset) {
      var extension = asset.cms_filename.split('.').reverse()[0];
      var path = asset.original_filename.replace('/files/', '');
      path = path.replace(asset.cms_filename, '');
      path = path.replace(/ /g, '-');
      path = path + asset.preview_id  + '.' + extension;

      return '/img/asset/preview/' + path; 
    },

    getItems(pageNumber) {
      var options = {
        page: pageNumber ? pageNumber : 1
      };

      this.resource.get(options).then((response) => {
        this.$set('items', response.data);
      });
    },

    getFolders: function() {
      var options = {
        count: 100,
        key: 'image',
        type: 'folders'
      };

      this.$http.get('/api/v1/extension', options).then((response) => {
        var options = [];

        for (var i = 0; i < response.data.data.length; i++) {
          if (response.data.data[i].value == 'recent') { 
            this.folder = response.data.data[i].id;
          }
          
          options.push({
            text: response.data.data[i].value,
            value: response.data.data[i].id
          });
        }

        this.$set('folderOptions', options);
      }); 
    }
  },

  ready() {
    var resource = this.$resource('asset{/id}');
    this.$set('resource', resource);
    this.getItems(1);
    this.getFolders();
  }  
}
</script>