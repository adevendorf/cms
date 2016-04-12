<template>
<div class="row">  
  <div class="col-md-4">
    <div class="">
      <label for="template" class="control-label">Template</label>
      <select name="template_name" v-model="page.template_name" class="form-control" @change="saveParent">
        <option v-for="option in templateOptions" v-bind:value="option.value">
          {{ option.text }}
        </option>
      </select>
    </div>  
  </div> 
</div>                            
</template>

<script>
export default {
  props: ['page'],

  data() {
    return {
      templateOptions: []
    }
  },

  methods: {

    setOptions: function(data){
      this.templateOptions = data.map(function(item) {
        return {
          text: item,
          value: item
        };
      });

      if (!this.page.template_name) this.page.template_name = '';
    },
    
    saveParent: function() {
      this.$dispatch('parent::save');
    }
  },

  ready() {
    this.setOptions(this.page.templates);
  }
}
</script>