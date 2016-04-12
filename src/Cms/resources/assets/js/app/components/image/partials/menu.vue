<template>
  <a class="nav-link btn btn-primary" @click="done">Done</a>
  <ul class="nav nav-pills nav-inline">
    <li class="nav-item">
      <a v-bind:class="'nav-link ' + (active == 'main' ? 'active' : '' )" @click="manager('main')">Image</a>
    </li>
    <li class="nav-item">
      <a v-bind:class="'nav-link ' + (active == 'lister' ? 'active' : '' )" @click="manager('lister')">Library</a>
    </li>
    <li class="nav-item">
      <a v-bind:class="'nav-link ' + (active == 'uploader' ? 'active' : '' )" @click="manager('uploader')">Upload</a>
    </li>
    <li class="nav-item">
      <a v-bind:class="'nav-link ' + (active == 'cropsizes' ? 'active' : '' )" @click="manager('cropsizes')">Crops</a>
    </li>
  </ul>
</template>

<script>
/**
 * Components/Image/Menu
 */
export default {

  props: ['active'],

  methods: {
    manager(which) {
      this.$dispatch('image::manager::view', which);
    },
    done() {
      this.$dispatch('image::manager::done');
    }
  },

  ready() {
  }
}
</script>