<template>
<div class="row"> 
  <div class="col-md-4">
    <div class="form-group">
      <label for="styling" class="control-label">Align </label>
      <select class="form-control" name="float" v-model="float" @change=" buildJson"> 
          <option v-for="option in floatDirs" v-bind:value="option.value">
            {{ option.text }}
          </option>
      </select>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label for="styling" class="control-label">CSS ID </label>
      <input class="form-control" name="css_id" type="text" v-model="cssId" @blur="buildJson">
    </div>   
  </div>
  <div class="col-md-4">    
    <div class="form-group">
      <label for="styling" class="control-label">CSS Class </label>
      <input class="form-control" name="css_class" type="text" v-model="cssClass" @blur="buildJson">
    </div> 
  </div>
</div>
</template>

<script>
export default {
  props: ['model'],

  data() {
    return {
      float: '',
      cssId: '',
      cssClass: '',
      
      floatDirs: [{ 
        text: 'None',
        value: ''
      },{ 
        text: 'Left',
        value: 'left'
      },{ 
        text: 'Right',
        value: 'right'
      }]
    }
  },

  methods: {

    buildJson() {

      var obj = {};

      if (this.float != '') obj.float = this.float;
      if (this.cssId != '') obj.cssId = this.cssId;
      if (this.cssClass != '') obj.cssClass = this.cssClass;

      var stringed = JSON.stringify(obj);

      this.model.styling = stringed == "{}" ? "" : stringed;

      this.$dispatch('styler::updated');
    },

    loadStyler() {

      var json = this.model.styling;

      if (json && json.length) {
        try {
          json = JSON.parse(json);
        } catch(e) {
          console.log('styling value is not valid');
        }

        if (json.float) this.float = json.float;
        if (json.cssId) this.cssId = json.cssId;
        if (json.cssClass) this.cssClass = json.cssClass;
      }
    }

  },

  ready() {
    this.loadStyler();
  }
}
</script>