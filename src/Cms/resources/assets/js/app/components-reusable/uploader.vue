<template>
<div>
  <span v-if="uploading">
    Uploads in progress...
  </span>

  <span v-if="!uploading">
    <input type="hidden" v-model="item.type" value="image" />
    <div class="form-group">
      <label for="file" class="control-label">File </label>
      <input name="file" type="file" id="file" v-el:file-input multiple>
      {{ errorMsg }}
    </div>    
    <div class="form-group">
      <button class="btn btn-primary" @click="upload">Upload</button>
    </div>
  </span>


  <div  v-if="files.length">
    <ul class="list-group">
      <li v-for="file in files" class="list-group-item">
        <h4 class="uploading">
          <span v-if="file.inprogress"><i class="fa fa-cog fa-spin inprogress"></i></span>
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
</template>

<script>
export default {


  data() {

    return {
      uploading: false,
      item: {
        type: 'image',
        file: ''
      },
      files: [],
      errorMsg: '',
      resource: null
    }
  },

  methods: {
    manager: function(e, which) {
      if (e) e.preventDefault();
      this.$dispatch('image-manager', which);
    },



    upload: function(e) {
      e.preventDefault();

      var files = this.$els.fileInput.files;


      if (files.length == 0) return;

      this.startUploading();

      for (var i = 0; i < files.length; i++) {
        this.$data.files.push({
          done: false,
          inprogress: false,
          name: files[i].name,
          size: Math.round(files[i].size / 1024) + 'k',
          error: false
        });
      }

      this.uploadFile(this.$els.fileInput.files, 0);

    },

    startUploading: function() {

      this.$data.files = [];
      this.$data.uploading = true;

    },

    doneUploading: function() {
      this.$data.uploading = false;
    },

    fileInProgress: function(index) {
      this.$data.files[index].inprogress = true;
    },

    fileDone: function(index) {
      this.$data.files[index].inprogress = false;
      this.$data.files[index].done = true;
    },

    fileError: function(index) {
      this.$data.files[index].inprogress = false;
      this.$data.files[index].error = true;
    },

    next: function(files, index, data) {
      if (index == files.length - 1) {
        
        this.doneUploading();
        
        if (data) this.$dispatch('upload-done', data);

      } else {
        
        index = index + 1;
        
        this.uploadFile(files, index);
      
      }
    },  


    uploadFile: function(files, index) {

      var formData = new FormData();
      
      formData.append('type', this.$data.item.type);
      
      formData.append('file', files[index]);

      this.fileInProgress(index);

      if (!files[index].type.match(/image/)) {
        this.fileError(index)
        this.next(files, index);
        return;
      } 

      this.resource.save({}, formData).then((response) => {
        
        this.fileDone(index);
        this.next(files, index, response.data);

       });;

    }
  },

  ready() {
    var resource = this.$resource('asset{/id}');
    this.$set('resource', resource);  
  }
}
</script>