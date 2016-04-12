<template>
  <div>
    <modal size="medium" :show.sync="modalVisible" header="true" footer="true">
      <div slot="header">
        Confirm
      </div>
      <div slot="body">   
        <h5 class="text-center">Are you sure you want to delete this?</h5>    
      </div>
      <div slot="footer">
        <button class="btn btn-primary" @click="confirm">Yes</button>
        <button class="btn btn-default" @click="modalVisible = false">No</button>
      </div>
    </modal>
  </div>
</template>

<script>
import Modal from './modal.vue';

export default {
  components: {
    Modal
  },
  data() {
    return {
      modalVisible: false
    }
  },
  methods: {
    confirm() {
      this.modalVisible = false;
      this.$emit('confirm');
    },
  },
  events: {
    'confirm::ask'() {
      this.modalVisible = true;
    }
  }
}
</script>