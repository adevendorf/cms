<template>
  <div v-if="content">
    <component :is="type" :content.sync="content"></component>
  </div>
</template>

<script>
export default {
  props: ['content'],

  components: {
    'content-Input': require('../content/input.vue'),
    'content-Text': require('../content/text.vue'),
    'content-Code': require('../content/code.vue'),
    'content-Image': require('../content/image.vue'),
    'content-Gallery': require('../content/gallery.vue'),
    'content-Form': require('../content/form.vue'),
    'content-Block': require('../content/block.vue'),
    'content-Input': require('../content/input.vue'),
  },

  computed: {
    type() {
      return 'content-' + this.content.type;
    }
  }
}
</script>