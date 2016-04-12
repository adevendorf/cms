<template>
    <div class="card-header">
      {{ type }}
      <div class="pull-right">
        <a class="btn btn-xs btn-light" @click="toggleVisible"><i class="fa fa-unsorted"></i></a>
        <a class="btn btn-xs btn-light" @click="toggleStyler"><i class="fa fa-cog"></i></a>
        <a class="btn btn-xs btn-light" @click="confirmRemoval"><i class="fa fa-times"></i></a>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: [
      'type'
    ],

    methods: {
      confirmRemoval() {
        this.$dispatch('header::confirmRemoval');
      },
      toggleVisible() {
        this.$dispatch('header::toggleVisible');
      },
      toggleStyler() {
        this.$dispatch('header::toggleStyler');
      },
      addContent() {

      }
    }

  }
  </script>