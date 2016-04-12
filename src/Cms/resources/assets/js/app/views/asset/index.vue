<template>

<div class="content">
    <div class="card card-block">
      <div class="row">
        <div class="col-sm-3">
          <h3>Latest Images</h3>
          <hr />
          <a v-link="{ name: 'asset-upload' }" class="btn btn-primary">New Image</a>
        </div>  
        <div class="col-sm-6">
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <label class="label-control">Keyword Search</label>
            <input type="text" class="form-control" v-model="keyword" @keyup.enter="getItem(1)" placeholder="Keyword search..." />
          </div>
        </div>
      </div>
    </div>

    <loader :show="!items.current_page"></loader>


    <div class="asset" v-for="item in items.data">
        <a class="center-block" v-link="{ name: 'asset-edit', params: { id: item.id }}" v-bind:style="'background-image:url(/img/asset/preview/' + item.path + item.cms_filename +  ')'"></a>
    </div>

    <div class="row"></div>



</div>

</template>

<script>
export default {
  data() {
    return {
      keyword: '',
      resource: null,
      id: null,
      items: {
        data: [],
        total_pages: 1,
        current_page: false,
        last_page: 1
      }
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

    searchOnEnter: function(e) {
      e.preventDefault();
      if (e.keyCode == 13) this.getItems(1);
    },


    getItems: function(pageNumber) {

      var options = {
        page: pageNumber ? pageNumber : 1
      };

      if (this.keyword != '') options.keyword = this.keyword;

      this.resource.get(options).then((response) => {
          // for (var i = 0; i < response.data.data.length; i++) {
          //   response.data.data[i].path = this.setPath(response.data.data[i]);
          // }
          this.$set('items', response.data);
      }); 

    },

    setPath(asset) {
       var extension = asset.cms_filename.split('.').reverse()[0];
      var path = asset.original_filename.replace('/files/', '');
      path = path.replace(asset.cms_filename, '');
      path = path.replace(/ /g, '-');
      path = path + asset.preview_id  + '.' + extension;

      return '/img/asset/preview/' + path; 
    }

  },

  ready() {
    var resource = this.$resource('asset{/id}');
    this.$set('resource', resource);
    this.getItems(1);
  }  
}
</script>