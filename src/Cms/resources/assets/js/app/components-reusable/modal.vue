<template>
  <div class="modal-mask" v-show="show" transition="modal">
      <div class="modal-wrapper">
        <div v-bind:class="'modal-container modal-' + size">

          <div class="modal-header" v-show="header">
            <button class="modal-close-button"
              @click="hide">
              <i class="fa fa-angle-left"></i>
            </button>
            <slot name="header">
              HEADER
            </slot>            
          </div>
          
          <div class="modal-body">
            <slot name="menu" v-show="menu">
            </slot>
            <slot name="body">
             BODY
            </slot>
          </div>

          <div class="modal-footer" v-show="footer">
            <slot name="footer">
              <button class="modal-default-button btn btn-primary"
                @click="hide">
                Close
              </button>
            </slot>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  props: ['show', 'size', 'header', 'footer', 'menu'],

  methods: {
    hide() {
      this.show = false;
    }
  }
}
</script>