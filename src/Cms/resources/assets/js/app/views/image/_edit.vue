<template>
<div class="well">
  <div class="loading" v-if="!item"><div class="spinner"></div></div>
  <span v-if="item">
    <div class="form-group">
      <label for="name" class="control-label">Parent ID: </label>
      <input class="form-control" name="name" type="text" v-model="item.parent_id" />
    </div>
    <div class="form-group">
      <label for="name" class="control-label">Parent Type: </label>
      <input class="form-control" name="name" type="text" v-model="item.parent_type" />
    </div>    
    <div class="form-group">
      <label for="email" class="control-label">Asset ID: </label>
      <input class="form-control" name="email" type="text" v-model="item.asset_id" />

      <div id="image-crop">
        <img v-bind:src="'/img/sized/_x500/' + asset.cms_filename" />
      </div>       


      <button v-if="!cropping" class="btn btn-default" @click="startCrop($event)">Crop</button> 
      <button v-if="!cropping" class="btn btn-default" @click="previewCrop($event)">Preview Crop</button> 
      <button v-if="cropping" class="btn btn-success" @click="saveCrop($event)">Save Crop</button>
      <button v-if="cropping" class="btn btn-danger" @click="cancelCrop($event)">Cancel Crop</button>

    </div>
    <div class="form-group">
     <label for="user_level" class="control-label">Caption: </label>
     <input class="form-control" name="email" type="text" v-model="item.caption" />
    </div>
    <div class="form-group">
      <label for="sites" class="control-label">Crop: </label>
      <input class="form-control" name="sites" type="text" v-model="item.crop" />
    </div>        
    <div class="form-group">
      <input class="btn btn-primary" type="submit" value="Update" @click="saveItem($event)" />
    </div>
  </span>
</div>    
</template>

<script>
export default {

  data() {
    return {
      cropping: false,
      item: null,
      asset: null
    }
  }, 


  methods: {

    openSelector: function(e) {
      e.preventDefault();
      this.$broadcast('selector-open');
    },

    saveItem: function(e) {
      e.preventDefault();

      this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');

      this.$http.post('/api/v1/image/' + this.$route.params.id, 
        this.item, 
        function(data, status, request) {
          console.log(data, status, request);
          router.go({name: 'image-index', params: { }});
       })
        .error(function(data, status, request) {
          System.handleError(status);
      });
    },


    getItem: function() {
      this.$http.get('/api/v1/image/' + this.$route.params.id, function (data, status, request) {
        this.$set('item', data.data);
        if (data.asset) {
          this.$set('asset', data.asset);
        }
      }).error(function (data, status, request) {
         System.handleError(status);
      }); 
    },

    previewCrop: function(e) {
       window.open(location.origin + '/img/cropped/_x320/'  + this.$data.item.id,  'IMG_PREVIEW');
    },

    startCrop: function(e) {
      e.preventDefault();

      window.cropBoxData = null;
      window.canvasData = null;

      var cropData = JSON.parse(this.$data.item.crop);
      this.$data.cropping = true;

      if (this.$data.item.crop) {
        var existingCrop = JSON.parse(this.$data.item.crop);
        var img = $('#image-crop img');
        window.$image = $('#image-crop img').imgAreaSelect({
          instance: true,
          handles: true,
          show: true,
          x1: Math.round(existingCrop.left/100 * img.width()),
          y1: Math.round(existingCrop.top/100 * img.height()),
          x2: Math.round(existingCrop.right/100 * img.width()),
          y2: Math.round(existingCrop.bottom/100 * img.height())
        });

      } else {
        window.$image = $('#image-crop img').imgAreaSelect({
          instance: true,
          handles: true,
          show: true
        });
      }    

    },


    saveCrop: function(e){
      if (e) e.preventDefault();

      var crop = $image.getSelection();
       var img = $('#image-crop img')

      var left =  (crop.x1 / img.width()) * 100;
      var top = (crop.y1 / img.height()) * 100;
      var right =  (crop.x2 / img.width()) * 100; 
      var bottom = (crop.y2 / img.height()) * 100;     

      this.$data.item.crop = JSON.stringify({
        left: left || 0,
        top: top || 0,
        right: right || 0,
        bottom: bottom || 0
      })

      this.cancelCrop();

    },

    cancelCrop: function(e) {
      if (e) e.preventDefault();
      this.$data.cropping = false;
      $image.cancelSelection();
      window.$image = $('#image-crop img').imgAreaSelect({remove: true});

    }



  },

  ready() {
    this.getItem();
  }  
}
</script>