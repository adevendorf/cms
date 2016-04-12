<template>
<a class="nav-link"  @click="open">
  New Section
</a>

<div class="modal fade" id="modal-section-creater" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Section</h4>          
          </div>
          <div class="modal-body">


            <div class="form-group">
              <label>Presets</label>
              <select class="form-control" v-model="selectedName">
                <option v-for="option in selections" v-bind:value="option.value">
                  {{ option.text }}
                </option>
              </select>
            </div>


              <div class="form-group" v-if="selectedName == ''">
                <label>Custom Name</label>
                <input type="text" class="form-control" v-model="item.title" @keyup="keyAction" />
              </div>

            <div class="form-group">
              <button class="btn btn-success" @click="addSection">Add Section</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {

  props: ['parent'],

  data() {
    return {
      resource: null,
      selectedName: '',
      selections: [
        {
          text: 'Custom Name',
          value: ''
        },
        {
          text: 'Sidebar',
          value: 'sidebar'
        },
        {
          text: 'Hero',
          value: 'hero'
        }
      ],
      item: {
        parent_id: this.parent.id,
        title: '',
        type: 'page'
      }
    }
  },

  methods: {

    close() {
      this.modalClose();
    },

    open() {
      this.selectedName = '';
      $('#modal-section-creater').modal('show'); 
    },


    modalClose() {
      $('#modal-section-creater').modal('hide'); 
    },  

    keyAction(e) {
      if (e.keyCode === 13) this.addSection();
    },


    addSection() {

      if (this.selectedName != '') {
        this.item.title = this.selectedName;
      }
      
      this.item.title = this.item.title.toLowerCase().replace(/ /g, '-');


      this.resource.save(this.item).then((response) => {
        var newBlock = response.data;
        newBlock.content = [];
        this.parent.blocks.push(newBlock);
        $('#modal-section-creater').modal('hide');
      });
    }  
  },

  ready() {

    var resource = this.$resource('page/newblock');
    this.$set('resource', resource);

  }
}
</script>