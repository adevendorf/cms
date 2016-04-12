<template>
  <div>
      <div class="row">

        <div v-if="cropping" class="col-sm-12 text-center"> 

          <button class="btn btn-success" @click="endCrop">Save</button>


          <div class="image-bg">
              <img v-bind:id="'image-' + image.image.id" v-bind:src=" '/img/asset/utility/' + image.image.asset.path + image.image.asset.cms_filename" />
          </div>
        </div>

        <div v-if="!cropping" class="col-sm-12">


              <button v-if="!cropping" class="btn btn-primary" @click="openCropTool">Crop</button>
              <button v-if="!cropping" class="btn btn-primary" @click="rotate">Rotate</button>



              <div class="image-bg final">

                <div v-if="image.image.asset_id" class="image-box">
                  <img v-bind:src=" '/admin/img-preview-lg/' + image.image.asset_id + '?crop=' + crop.name + '&crop_x=' + cropSettings.crop_x + '&crop_y=' + cropSettings.crop_y + '&crop_width=' + cropSettings.crop_width + '&crop_height=' + cropSettings.crop_height+  '&buster=' + cache" />
                </div>
              </div>

        </div>
      </div>

      <div class="row">
        <div class="col-sm-4 form-group">
            <label>&nbsp;</label>
            <div>
             
            </div>
        </div>
        <div v-if="cropping && crop.name == 'default'" class="col-sm-4 form-group">
          <label>Aspect Ratio (1:1)</label>
          <select class="form-control" v-model="selectedRatio" @change="changeAspect">
            <option value="">Free Form</option>
            <option value="1:1">Square</option>
            <option value="3:2">3:2</option>
            <option value="5:3">5:3</option>
            <option value="4:3">4:3</option>
            <option value="5:4">5:4</option>
            <option value="7:5">7:5</option>
            <option value="16:9">16:9</option>
          </select>
        </div>

        
      </div>



</div>
</template>

<script>
/**
 * Components/Image/Cropper
 */
export default {

  props: ['image',  'crop', 'show'],

  data() {
    return {
      cropSettings: false,      
      cache: Math.random().toString(36).substring(7),
      cropping: false,
      cropper: null,
      cropData: '',
      selectedRatio: '',
      aspect: false,
      previewPath: ''
    }
  },

  methods: {

    rotate() {

    },

    manager(which) {
      this.$dispatch('image::manager::view', which);
    },

    setPath(asset) {
      var extension = asset.cms_filename.split('.').reverse()[0];
      var path = asset.original_filename.replace('/files/', '');
      path = path.replace(asset.cms_filename, '');
      path = path.replace(/ /g, '-');
      path = path + asset.preview_id  + '.' + extension;

      this.previewPath =  path; 
    },

    checkMaxDimension() {

      if (typeof this.crop.max_dimension != 'number') {
        this.crop.max_dimension = this.image.image.asset.max_dimension;
      }

      if (this.crop.max_dimension > this.image.image.asset.max_dimension) {
        this.crop.max_dimension = this.image.image.asset.max_dimension;
      }
    },

    

    updateSize() {      
      var data = {
        max_dimension: this.crop.max_dimension,
        aspect_ratio: this.crop.aspect_ratio,
        name: this.crop.name
      };

      this.$http.post('/api/v1/imagecrop/' + this.image.image.id, data, function(data, status, request) {
       });      
    },


    switchCrop(opt) {      
      this.crop.name = opt;
      //this.getSize();
    },



    changeAspect() {
        var AR = this.selectedRatio.split(':');
        if (AR.length = 2) {
          this.aspect = AR[0]/AR[1];
        }

        $('#image-' + this.image.image.id).cropper('setAspectRatio', this.aspect);
    },

    openCropTool() {

      this.cropping = true;
      var that = this;
      setTimeout(function(){
        that.startCrop();
      }, 250);

    }, 



    startCrop() {
      
      window._CROP = {};
      var that = this;

      var dbCropData = {
        x: this.crop.crop_x,
        y: this.crop.crop_y,
        width: this.crop.crop_width,
        height: this.crop.crop_height
      };

      if (this.crop.name == 'default') {
        if (this.selectedRatio && this.selectedRatio != '') {
          var AR = this.selectedRatio.split(':');
          if (AR.length = 2) {
            this.aspect = AR[0]/AR[1];
          }
        }
      } else {
        this.aspect = this.crop;
        var AR = this.crop.aspect_ratio.split(':');
        if (AR.length = 2) {
          this.aspect = AR[0]/AR[1];
        }
      }

      $('#image-' + this.image.image.id).cropper({
        viewMode: 1,
        scaleable: false,
        zoomable: false,
        aspectRatio: that.aspect,
        built() {

          setTimeout((function(cropData) {

            var data = cropData;

             var preview = {
              width: $('#image-' + that.image.image.id).cropper('getImageData').naturalWidth,
              height: $('#image-' + that.image.image.id).cropper('getImageData').naturalHeight
            };

            var setCropData = {
              x: (data.x/100) * preview.width,
              y: (data.x/100) * preview.height,
              width: (data.width/100) * preview.width,
              height: (data.height/100) * preview.height
            };
     
            $('#image-' + that.image.id).cropper('setData', setCropData);

          })(dbCropData), 100);

        },
        crop(e) {
  
          var preview = {
            width: $('#image-' + that.image.image.id).cropper('getImageData').naturalWidth,
            height: $('#image-' + that.image.image.id).cropper('getImageData').naturalHeight
          };

          window._CROP = {
            x: (e.x / preview.width) * 100,
            y: (e.y / preview.height) * 100,
            width: (e.width / preview.width) * 100,
            height: (e.height / preview.height) * 100
          };    

          _CROP.x = _CROP.x < 0 ? 0 : _CROP.x;
          _CROP.y = _CROP.y < 0 ? 0 : _CROP.y;
          _CROP.width = _CROP.width > 100 ? 100: _CROP.width;
          _CROP.height = _CROP.height > 100 ? 100 : _CROP.height;

        }
      });
    },



    endCrop(e) {
      if (e) e.preventDefault();

      if (!window._CROP) {
        this.show = false;
        return;
      }

      var that = this;

      var crop = this.image.image.crops.filter(function(item) {
        return item.name == that.crop.name;
      });


      var cropData = {
        crop_x: _CROP.x,
        crop_y: _CROP.y,
        crop_width: _CROP.width,
        crop_height: _CROP.height,
        type: this.type,
        max_dimension: this.crop.max_dimension,
        aspect_ratio: this.crop.aspect_ratio,

      };


      crop[0].crop_x = _CROP.x;
      crop[0].crop_y = _CROP.y;
      crop[0].crop_width = _CROP.width;
      crop[0].crop_height = _CROP.height;
      crop[0].max_dimension = this.crop.max_dimension;
      crop[0].aspect_ratio = this.crop.aspect_ratio;

      // this.$http.post('/api/v1/imagecrop/' + crop[0].id, cropData, function(data, status, request) {
      //   $('#image-' + this.image.id).cropper('destroy');
      //   this.cropping = false;
      //   this.cache = Math.random().toString(36).substring(7);
      // }).error(function(data, status, request) {
      //   System.handleError(status);
      // });

      $('#image-' + this.image.image.id).cropper('destroy');
      this.cropping = false;
      this.cache = Math.random().toString(36).substring(7);

      this.show = false;

    },
  },

  events: {
    'cropper::end'() {
      this.endCrop();
      return false;
    }
  },

  ready() {

    // var cropValues = this.image.crops.filter(function(item) {
    //   return item.name == this.crop.name;
    // }, this);

    // this.cropSettings = cropValues[0]; 

    this.cropSettings = this.crop;  


    this.previewPath = this.setPath(this.image.image.asset);

  }
}
</script>