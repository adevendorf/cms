<template>
  <div>
    <h3>No asset assigned</h3> 
    <p>
      <a class="btn btn-info" @click="emitEvent('lister')">Select From Library</a> 
      or 
      <a class="btn btn-info" @click="emitEvent('uploader')">Upload Images</a>
    </p>
  </div>
</template>

<script>
export default {

  methods: {
    emitEvent(type) {
      this.$dispatch('image::manager::view', type);
    }
  },
}
</script>