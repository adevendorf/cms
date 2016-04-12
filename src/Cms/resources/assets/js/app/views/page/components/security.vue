<template>
<div class="row">
  <div class="col-md-6">
    <label for="access" class="control-label">Options: </label>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="require_https" v-model="page.require_https" @change="saveParent"> Require HTTPS
      </label>
    </div>
    <div class="checkbox">
      <label>
        <input type="checkbox" name="ignore_in_search" v-model="page.ignore_in_search" @change="saveParent"> Don't return in internal search results
      </label>
    </div>
     <div class="checkbox">
      <label>
        <input type="checkbox" name="ignore_in_sitemap" v-model="page.ignore_in_sitemap" @change="saveParent"> Don't list in Sitemap
      </label>
    </div>                
  </div>  
</div>         
</template>

<script>
export default {
  props: ['page'],
  methods: {
    saveParent: function() {
      this.$dispatch('parent::save');
    }
  }
}
</script>