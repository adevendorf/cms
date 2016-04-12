<template>
  <div class="modal fade" id="modal-selector-{{ id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          Error
        </div>
        <div class="modal-body">

          <p>{{ code }}: {{ msg }}</p>

        </div>
        <div class="modal-footer">
          <button v-if="showOk" type="button" class="btn btn-info" @click="ok($event)"><i class="fa fa-check"></i> Ok</button>
          <button type="button" class="btn btn-primary" @click="close($event)"><i class="fa fa-close"></i> Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {

  el: '#system',

  data:  {
    showOk: false,
    id: 'cms-system',
    code: 0,
    msg: 'Server Error',
    redir: '#'
  },

  methods: {
    handleError: (type, msg) => {
      this.$data.code = type;

      if (type == 401) {
        this.$data.msg = 'Your session has expired or is no longer valid';
        this.$data.redir = "/admin/";
        this.$data.showOk = true;
      }

      if (type == 404) {
        this.$data.msg = 'Route not found';
      }

      if (type == 405) {
        this.$data.msg = 'Error 405?';
      }


      this.open();
    },

    open: () => {
      $('#modal-selector-' + this.$data.id).modal('show'); 
    }, 

    close: () => {
      $('#modal-selector-' + this.$data.id).modal('hide'); 
      this.$data.code =  0;
    },

    ok: (e) => {
      e.preventDefault();
      window.location.href = this.$data.redir;
    }
  },

  ready: () => {
  } 
}
</script>