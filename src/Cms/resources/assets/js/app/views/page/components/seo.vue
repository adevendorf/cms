<template>
<div class="row">
  <div class="col-sm-12">
    <div class="form-group">
      <label for="keywords" class="control-label">Keywords</label>
      <textarea class="form-control" v-model="page.keywords" rows="2" @blur="saveParent"></textarea>
    </div>
    <div class="form-group">
      <label for="description" class="control-label">Description</label>
      <textarea class="form-control" v-model="page.description" rows="4" @blur="saveParent"></textarea>
    </div>      

    <div class="form-group">
      <label class="control-label">GoogleBot</label>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="goog_no_index" v-model="page.goog_no_index" @change="saveParent"> No Index
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="goog_no_follow" v-model="page.goog_no_follow" @change="saveParent"> No Follow
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="goog_index" v-model="page.goog_index" @change="saveParent"> Index
        </label>
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" name="goog_follow" v-model="page.goog_follow" @change="saveParent"> Follow
        </label>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  props: ['page'],

  methods: {
    saveParent() {
      this.$dispatch('parent::save');
    }
  },

}
</script>