<template>
  <a class="nav-link"  @click="addStep">
    Add Step
  </a>
</template>

<script>
export default {

  props: ['parent'],

  data() {
    return {
      resource: null,
      item: {
        parent_id: this.parent.id,
        title: '',
        type: 'form'
      }
    }
  },

  methods: {

    addStep() {
      
      this.item.title = (this.parent.blocks.length + 1);

      this.resource.save(this.item).then((response) => {
        this.parent.blocks.push(response.data);
      });
    }  
  },

  ready() {

    var resource = this.$resource('form/newblock');
    this.$set('resource', resource);

  }
}
</script>