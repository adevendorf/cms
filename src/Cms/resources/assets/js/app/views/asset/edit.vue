<template>
  <div class="content"> 

    <loader :show="!item"></loader>

    <div v-if="item">

      <div class="card" v-if="item.type == 'image'">
        <div class="card-block image-bg">
          <div class="image-box">
            <div class="dot tl {{ item.crop_preference == 'top-left' ? 'selected' : '' }}" @click="cropPos('top-left')"></div>
            <div class="dot t {{ item.crop_preference == 'top' ? 'selected' : '' }}" @click="cropPos('top')"></div>
            <div class="dot tr {{ item.crop_preference == 'top-right' ? 'selected' : '' }}" @click="cropPos('top-right')"></div>
            <div class="dot bl {{ item.crop_preference == 'bottom-left' ? 'selected' : '' }}" @click="cropPos('bottom-left')"></div>
            <div class="dot b {{ item.crop_preference == 'bottom' ? 'selected' : '' }}" @click="cropPos('bottom')"></div>
            <div class="dot br {{ item.crop_preference == 'bottom-right' ? 'selected' : '' }}" @click="cropPos('bottom-right')"></div>
            <div class="dot center {{ item.crop_preference == 'center' ? 'selected' : '' }}" @click="cropPos('center')"></div>
            <div class="dot r {{ item.crop_preference == 'right' ? 'selected' : '' }}" @click="cropPos('right')"></div>
            <div class="dot l {{ item.crop_preference == 'left' ? 'selected' : '' }}" @click="cropPos('left')"></div>
            <img v-bind:src="'/img/asset/utility/' + item.path + item.cms_filename" />
          </div>
        </div>
      </div>

      <div class="row">

        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label">Caption </label>
            <textarea class="form-control" v-model="item.caption"></textarea>
          </div>   
          <div class="form-group">
            <label class="control-label">Keywords </label>
            <input class="form-control" type="text"  v-model="item.keywords">
          </div>                   
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label">Folder </label>
            <select class="form-control" v-model="item.folder">
              <option v-for="option in folders" v-bind:value="option.value">
                {{ option.value }}
              </option>
            </select>   
          </div>           
        </div>
        
      </div>

      <div class="form-group">
        <button class="btn btn-primary" @click="saveItem">Save</button>
      </div>

    </div>
  </div>  
</template>

<script>
export default {
  computed: {
    imagepath: function () {
      var extension = this.item.cms_filename.split('.').reverse()[0];
      var path = this.item.original_filename.replace('/files/', '');
      path = path.replace(this.item.cms_filename, '');
      path = path.replace(/ /g, '-');
      return '/img/asset/preview/' + path + this.item.preview_id  + '.' + extension;
    }
  },

  data() {
    return {
      item: false,
      folderOptions: false,
      optsAssetTypes: [
        {
          text: 'Image',
          value: 'image'
        },
        {
          text: 'Document',
          value: 'document'
        }
      ]
    }
  },

  methods: {

    cropPos(pos) {
      this.item.crop_preference = pos;
    },

    saveItem: function(e) {
      this.$http.put('/api/v1/asset/' + this.$route.params.id, this.item).then((response) => {
        this.$router.go({name: 'asset-index', params: { }});
       });
    },

    getItem: function() {
      this.$http.get('/api/v1/asset/' + this.$route.params.id).then((response) => {
        this.$set('item', response.data);
      }); 
    },

    remove: function(e, id) {
      e.preventDefault();
    
      this.$http.delete('/api/v1/asset/' + id).then((response) => {
        router.go({name: 'asset-index', params: { }});
      });
    },

    getFolders: function() {
      var options = {
        count: 100,
        key: 'image',
        type: 'folders'
      };

      this.$http.get('/api/v1/extension', options).then((response) => {

        this.$set('folders', response.data.data);

        this.getItem();
      }); 
    }
  },

  ready() {
    this.getFolders();
    
    
  }
}
</script>