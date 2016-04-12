<template>
  <div class="sidepanel-mask" v-show="show" transition="sidepanel">
      <div class="sidepanel-wrapper">
        <div v-bind:class="'sidepanel-container sidepanel-' + size + ' sidepanel-' + side">

          <div class="sidepanel-header">
            <button v-bind:class="'sidepanel-close-button ' + side"
              @click="hide"
              v-show="side == 'left'"
              >
              <i class="fa fa-angle-left"></i>
            </button>
            <slot name="header">
            </slot>
            <button v-bind:class="'sidepanel-close-button ' + side"
              @click="hide"
              v-show="side == 'right'"
              >
              <i class="fa fa-angle-right"></i>
            </button>
            
          </div>
          
          <div class="sidepanel-body">
            <slot name="menu" v-show="menu">
            </slot>
            <slot name="body">
            </slot>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  props: ['show', 'size', 'side', 'closeAction'],

  methods: {
    hide() {
      this.$emit('close');
      this.show = false;
    }
  }
}
</script>