<template>
  <div class="row">
    <div class="col-sm-12">
      <div class="form-group">
        <select class="form-control" v-model="type">
          <option value="">Select a Type</option>
          <option value="textfield">Textfield</option>
          <option value="multiline">Multiline</option>
        </select>
        <a class="btn btn-primary" @click="assignType">Assign</a>
      </div>
    </div>  
  </div>
</template>

<script>
export default {

  props: ['content'],

  data() {
    return {
      type: '',
      resource: null,
      id: null
    }
  },

  methods: {
    assignType() {
      var obj = {
        question_type: this.type,
        parent_id: this.content.id
      };

      this.resource.save(obj).then((response) => {
        this.content.field = response.data;
        this.content.resource_id = response.data.id;
      });
    }
  },

  ready() {
    var resource = this.$resource('formfield{/id}');
    this.$set('resource', resource);
  }
}
</script>