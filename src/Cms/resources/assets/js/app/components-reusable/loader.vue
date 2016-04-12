<template>
  <div v-show="show">
    <h2 class="text-xs-center"><i class="fa fa-spinner fa-spin"></i></h2>
    <h5 class="text-xs-center text-muted">loading</h5>
  </div>
</template>

<script>
export default {
  props: ['show']
}
</script>