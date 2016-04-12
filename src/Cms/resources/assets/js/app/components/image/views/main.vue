<template>
  <div class="row">

    <div class="col-sm-12">

<!--       <div class="row" v-if="visible=='cropper'">
        <div class="col-sm-12">
          <button @click="showMain" class="btn btn-default"><i class="fa fa-caret-left"></i> Show Crop Sizes</button>
        </div>
        <hr />
      </div> -->


      <div class="row" v-if="parent.image && !parent.image.asset_id && visible=='main'">
        <no-image 
          v-on:select="manager('lister')" 
          v-on:upload="manager('uploader')"
          >
        </no-image>
      </div>


      <div class="row" v-if="parent.image && parent.image.asset_id && visible=='main'">
        <div v-for="crop in parent.image.crops" class="col-sm-12 main-preview {{ currentCrop == crop.name ? 'selected' : '' }}">

          <div class="row">
            <div class="col-sm-6">
              <img v-bind:src=" '/admin/img-preview/' + parent.image.asset.id + '?crop_x=' + crop.crop_x + '&crop_y=' + crop.crop_y + '&crop_width=' + crop.crop_width + '&crop_height=' + crop.crop_height + '&buster=' + cachebust" />
            </div>
            <div class="col-sm-6 meta">
              <div v-show="crop.name != 'default'"><i class="fa fa-tag"></i> {{ crop.name }}</div>
              <div><i class="fa fa-crop"></i> {{ !crop.aspect_ratio ? 'Free Form' : crop.aspect_ratio }}</div>
              <div><i class="fa fa-expand"></i> <span class="pixel-size"><input class="form-control" type="text" v-model="crop.max_dimension" size="5" /><span class="px-label">px</span></span></div>
            </div>
          </div>
          
          <div class="details">
            <button class="btn btn-primary" @click="showCropper(crop)">Adjust</button>
            <button v-if="crop.name !== 'default'" class="btn btn-danger" @click="removeItem(crop)">Remove</button>   
          </div>

          <hr />       
        </div>
      </div>

      <div class="row">

        <div v-if="!showCroppings" class="col-sm-12">             
          <a class="btn btn-primary" @click="showCropOptions">Add Crop</a>
        </div>

        <div v-if="showCroppings" class="card card-block">
          <div class="card-body">
            <div  class="form-group">
              <select class="form-control" name="crop" v-model="selectedCropSize">
                <option v-for="option in managedCropOptions" v-bind:value="option.value">
                  {{ option.text }}
                </option>
              </select>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" @click="addItem">Add Size</button>
              <button class="btn btn-default pull-right" @click="cancelAddItem">Cancel</button>
            </div>
          </div>
        </div> 
      </div>


      <div v-if="cropperVisible">
      <modal
        size="large"
        footer="false"
        header="true"
        :show.sync="cropperVisible"
        >
        <div slot="header">
          Image Adjustments
        </div>
        <div slot="body">              
            <cropper 
              :image="parent" 
              :crop="selectedCrop"
              >
            </cropper>
        </div>
<!--         <div slot="footer">
          <button class="btn btn-success" @click="endCrop">Save</button>
          <button 
            class="btn btn-default" 
            @click="cropperVisible = false"
            >
            Cancel
          </button>
        </div> -->
      </modal>
      </div>

    </div>
  </div>
</template>

<script>
import Cropper from '../partials/cropper.vue';
import Menu from '../partials/menu.vue';
import NoImage from '../partials/no-image.vue';

export default {

  props: ['parent'],

  components: {
    Menu,
    Cropper,
    NoImage
  },

  data() {
    return {
      cropperVisible: false,
      showCroppings: false,
      visible: 'main',
      cachebust: Math.random().toString(36).substring(7),
      managedCropOptions: [],
      selectedCropSize: '',
      selectedCrop: null,
      resource: null,
      items: {
        data: []
      }
    }
  },

  methods: {

    // endCrop() {
    //   this.$broadcast('cropper::end')
    // },

    manager(which) {
      this.$dispatch('image::manager::view', which);
      this.cachebust = Math.random().toString(36).substring(7);
    },

    showCropper(item) {
      window._CROP = null;
      this.selectedCrop = item;      
      // this.visible = 'cropper';
      this.cropperVisible = true;
    },
    showMain() {
      this.visible = 'main';
    },

    showCropOptions() {
      this.showCroppings = true;
      this.getAvailableCroppings();
    },

    cancelAddItem() {
      this.showCroppings = false;
    },

    getAvailableCroppings() {
      this.managedCropOptions = [];
      this.resource.get().then((response) => {
        this.items = response.data;

        var arr = jsonOut(response.data.data);

        for (var i = 0; i < arr.length; i++) {
          this.managedCropOptions.push({
            text: arr[i].name,
            value: arr[i].name
          });
        }

        this.selectedCropSize = this.items.data[0].name;

        // this.setSize();

      });
    },

    addItem() {

      // already defined
      if (this.parent.image.crops.filter(function(item) { 
          return item.name == this.selectedCropSize; 
        }, this).length > 0) {
        
        return;
      }

      var managedCrop = this.items.data.filter(function(item) {
        return item.name == this.selectedCropSize;
      }, this);

      managedCrop = managedCrop[0];

      var cropData = {
        name: this.selectedCropSize,
        crop_x: 0,
        crop_y: 0,
        max_dimension: managedCrop.max_dimension,
        aspect_ratio: managedCrop.aspect_ratio,
        crop_width: 100,
        crop_height: 100
      };

      if (this.parent.image.id) {
        cropData['image_id'] = this.parent.image.id;
      }

        
      this.parent.image.crops.push(cropData);

      // this.$http.post('/api/v1/imagecrop/create', cropData).then((response) => {
      //   this.image.crops.push(response.data);
      // });
      
      this.showCroppings = false;
    },

    removeItem(item) {
      this.parent.image.crops.$remove(item);
    }

  },

  events: {

    'image::main::refresh'() {
      this.cachebust = Math.random().toString(36).substring(7);
      return false;
    }
    
  },

  ready() {
    var resource = this.$resource('cropsize{/id}');
    this.$set('resource', resource);
  }
}
</script>