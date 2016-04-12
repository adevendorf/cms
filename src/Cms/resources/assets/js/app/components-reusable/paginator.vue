<template>
<div class="row text-xs-center">
  <nav v-if="items.last_page > 1">
    <ul class="pagination">
      <li class="page-item">
        <a class="page-link" @click="prevPage">
          <span>&nbsp; <i class="fa fa-angle-left"></i> &nbsp;</span>
        </a>
      </li>
      <li class="page-item">
        <a class="page-link">
          <span>{{ items.current_page }} of {{ items.last_page }}</span>
        </a>
      <li class="page-item">
        <a class="page-link" @click="nextPage">
          <span>&nbsp; <i class="fa fa-angle-right"></i> &nbsp;</span>
        </a>
      </li>
    </ul>
  </nav> 
</div>
</template>

<script>
export default {

  props: ['items'],

  methods: {
    prevPage() {
      this.$emit('prev');
    },
    nextPage() {
      this.$emit('next');
    }
  }

}
</script>