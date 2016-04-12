<template>
  <div v-if="content">
    <component :is="type" :content.sync="content"></component>
  </div>
</template>

<script>
export default {
  props: ['content'],

  components: {
    'content-Text': require('../../components/content/text.vue'),
    'content-Code': require('../../components/content/code.vue'),
    'content-Image': require('../../components/content/image.vue'),
    'content-Gallery': require('../../components/content/gallery.vue'),
    'content-Form': require('../../components/content/form.vue'),
    'content-Block': require('../../components/content/block.vue'),
    'content-Feed': require('../../components/content/feed.vue'),
    'content-LatestPosts': require('../../components/prebuilt/latestposts.vue'),
    'content-BlogPageBreak': require('../../components/prebuilt/blogpagebreak.vue'),
    'content-ListCategories': require('../../components/prebuilt/listcategories.vue'),
    'content-Register': require('../../components/content/register.vue'),
    'content-Login': require('../../components/content/login.vue')
  },

  computed: {
    type() {
      return 'content-' + this.content.type;
    }
  }
}
</script>