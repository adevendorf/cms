<template>
    <div class="card-header">
      {{ type }}
      <div class="pull-right">
        <a class="btn btn-xs btn-light" @click="toggleVisible"><i class="fa fa-unsorted"></i></a>
        <a class="btn btn-xs btn-light" @click="toggleStyler"><i class="fa fa-cog"></i></a>
        <a class="btn btn-xs btn-light" @click="confirmRemoval"><i class="fa fa-times"></i></a>
      </div>
      <confirm v-on:confirm="removeContent"></confirm>
    </div>
  </template>

  <script>
  export default {
    props: [
      'type'
    ],

    methods: {
      confirmRemoval() {
        this.$broadcast('confirm::ask');
      },
      toggleVisible() {
        this.$dispatch('header::toggleVisible');
      },
      toggleStyler() {
        this.$dispatch('header::toggleStyler');
      },
      removeContent() {
        this.$dispatch('header::removeContent');
      }
    }

  }
  </script>