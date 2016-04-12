<template>

  <div class="modal-body"> 


    <div class="row">
      <div class="col-sm-12">

        <div class="card">
          <div class="card-header">
            Select files to upload
          </div>
          <div class="card-block">

          <span v-if="!uploading">
            <input type="hidden" v-model="item.type" value="image" />
            <div class="form-group">
              <label for="file" class="control-label">File </label>
              <input name="file" type="file" id="file" v-el:file-input multiple>
              {{ errorMsg }}
            </div>    
            <div class="form-group">
              <button class="btn btn-primary" @click="upload"><i class="fa fa-caret-right"></i> Upload Files</button>
            </div>
          </span>
          </div>

        </div>


        <div class="card" v-if="files.length">
          <div class="card-block">
            <ul class="list-group">
              <li v-for="file in files" class="list-group-item">
                <h4 class="uploading">
                  <span v-if="file.inprogress"><i class="fa fa-spinner fa-spin inprogress"></i></span>
                  <span v-if="file.done && !file.error"><i class="fa fa-check done"></i></span>
                  <span v-if="!file.done && !file.inprogress && !file.error"><i class="fa fa-circle-thin not-done"></i></span>
                  <span v-if="file.error"><i class="fa fa-exclamation error"></i></span>
                  {{ file.name }} 
                  <span v-if="!file.done">, {{ file.size }}</span>
                </h4>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
/**
 * Components/Image/Uploader
 */
import menu from '../partials/menu.vue';

export default {

  components: {
    menu
  },

  data() {
    return {
      uploading: false,
      item: {
        type: 'image',
        file: ''
      },
      fileInput: null,
      files: [],
      errorMsg: '',
      resource: null
    }
  },

  methods: {
    manager(which) {
      this.$dispatch('image::manager::view', which);
    },

    upload() {
      var files = this.$els.fileInput.files;

      if (files.length == 0) return;

      this.files = [];
      this.uploading = true;

      for (var i = 0; i < files.length; i++) {
        this.files.push({
          done: false,
          inprogress: false,
          name: files[i].name,
          size: Math.round(files[i].size / 1024) + 'k',
          error: false
        });
      }

      this.uploadFile(this.$els.fileInput.files, 0);
    },


    doneUploading() {
      this.uploading = false;
    },

    fileInProgress(index) {
      this.files[index].inprogress = true;
    },

    fileDone(index) {
      this.files[index].inprogress = false;
      this.files[index].done = true;
    },

    fileError(index) {
      this.files[index].inprogress = false;
      this.files[index].error = true;
    },

    next(files, index, data) {
      if (index == files.length - 1) {
        this.doneUploading();
        if (data) this.$dispatch('image::uploader::done', data);
      } else {
        index = index + 1;
        this.uploadFile(files, index);
      }
    },  

    uploadFile(files, index) {

      var formData = new FormData();
      
      formData.append('type', this.item.type);
      formData.append('file', files[index]);

      this.fileInProgress(index);

      // if (!files[index].type.match(/image/)) {
      //   this.fileError(index)
      //   this.next(files, index);
      //   return;
      // } 

      this.resource.save(formData).then((response) => {
        this.fileDone(index);
        this.next(files, index, response.data);
      });

    }
  },

  ready() {
    var resource = this.$resource('asset{/id}');
    this.$set('resource', resource);
  }
}
</script>