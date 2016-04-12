<template>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="access" class="control-label">Synopsis </label>
      <textarea class="form-control" v-model="page.synopsis" rows="5" @blur="saveParent"></textarea>
    </div>       
  </div> 
  <div class="col-md-4">
  </div>   
</div>         
</template>

<script>
export default {
  props: ['page'],

  data() {
    return {
      positions: [1,2,3,4,5,6,7,8,9,10],
      resource: null,
      item: null
    }
  },

  methods: {
    saveParent() {
      this.$dispatch('parent::save');
    }

  },

  ready() {
  }
}
</script>