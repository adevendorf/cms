<template>
<div>
  <div class="row">   
    <div class="form-group col-sm-12">
      <label class="control-label">Question Text</label>
      <input type="text" class="form-control" v-model="content.field.question" @blur="saveItem">
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-2">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="encrypt" v-model="content.field.encrypt" @change="saveItem"> <strong>Encrypt</strong> 
        </label>
      </div>
    </div>
    <div class="form-group col-sm-2">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="required" v-model="content.field.required" @change="saveItem"> <strong>Required Field</strong>
        </label>
      </div>
    </div>  
  </div>

</div>

</template>

<script>

export default {
  props: ['content'],

  data() {
    return {
      id: null, 
      resource: null,
    }
  },

  methods: {

    saveItem(item) {
      this.resource.update({id: this.id}, this.content.field);
    },

    confirmRemoval() {
      this.$broadcast('confirm::ask');
    },

    removeItem() {
      this.resource.delete({id: this.id}).then((response) => {
        this.$dispatch('block::content::remove', this.id);
      });
    },
  },

  ready() {

    var resource = this.$resource('formfield{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.field.id);


    this.$on('content::save', function() {
      this.saveItem();
      return false;
    });
    this.$on('confirm::confirmed', function() {
      this.removeItem();
      return false;
    });    
  }
}
</script>