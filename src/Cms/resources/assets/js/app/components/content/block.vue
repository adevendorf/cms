<template>
  <div class="card">
    <content-header type="Block"></content-header>
    <confirm v-on:confirm="removeItem"></confirm>

    <div class="card-block" v-if="visible.main">   
      <h5 v-if="blockTitle != ''">
        {{ blockTitle }}
      </h5>
      
      <div>
        <a class="btn btn-primary" @click="openSelector">Select Block</a>    
        <a v-if="blockTitle != ''" class="btn btn-primary" v-link="{ name: 'block-edit', params: { id: blockId }}">Edit Block</a>  
        <!-- <a v-show="blockTitle != ''" class="btn btn-primary" @click="editModalVisible = true">Edit Block</a>  -->
      </div>

      <styling :content="content"></styling>

    </div>

    <modal
      size="medium"
      footer="true"
      header="true"
      :show.sync="modalVisible"
      >
      <div slot="header">
        Select a Block
      </div>
      <div slot="body">
        <lister 
          :parent-id="content.id"
          >
        </lister>
      </div>
      <div slot="footer">
        <button class="btn btn-default" @click="modalVisible = false">Cancel</button>
      </div>
    </modal>

    <!-- <modal
      size="large"
      footer="true"
      header="true"
      :show.sync="editModalVisible"
      >
      <div slot="header">
        Edit a Block
      </div>
      <div slot="body">
         <block-edit 
          :block-id="id"
          >
        </block-edit> 
      </div>
      <div slot="footer">
        <button class="btn btn-default" @click="editModalVisible = false">Close</button>
      </div>
    </modal> -->
  </div>
</template>

<script>
import styling from './_styling.vue';
import contentHeader from './_header.vue';
import Lister from '../../components/block/lister.vue';
import BlockEdit from '../../views/block/edit.vue';

export default {

  props: ['content'],

  components: {
    contentHeader,
    styling,
    Lister,
    BlockEdit
  },

  data() {
    return {
      resource: null,
      id: null,
      selectorOptions: {
        allowUpload: false,
        parentType: 'block'
      },
      blockTitle: '',
      blockId: '',
      new: false,
      showStyling: false,
      visible: {
        main: true
      },
      modalVisible: false,
      editModalVisible: false
    }
  },


  methods: {

    toggleStyling() {
      this.showStyling = !this.showStyling;
    },

    openSelector() {
      this.modalVisible = true;
    },

    saveItem() {

      if (!this.content.type) return; 

      if (!this.content.id) {
        this.resource.save(this.content).then((response) => {
          this.content = response.data
        });
        return;
      }  

      this.resource.update({id: this.id}, this.content);
    }, 

    confirmRemoval() {
      this.$broadcast('confirm::ask');
    },

    removeItem() {
      this.resource.delete({id: this.id}).then((response) => {
        this.$dispatch('block::content::remove', this.id);
      });
    },

    getResource() {
      this.$http.get('/api/v1/block/' + this.content.resource_id).then((response) => {
        this.blockTitle = response.data.title;
        this.blockId = response.data.id;
      });
    }, 

  },

  events: {
    'header::toggleVisible'() {
      this.visible.main = !this.visible.main;
      return false;
    },
    'header::toggleStyler'() {
      if (!this.visible.main) this.visible.main = true;
      this.$broadcast('styler::toggle');
      return false;
    },    
    'header::confirmRemoval'() {
      this.$broadcast('confirm::ask');
      return false;
    },
    'blocker::expandAll'() {
      this.visible.main = true;
    },
    'blocker::hideAll'() {
      this.visible.main = false;
    }
  },


  ready() {
    var resource = this.$resource('content{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.id);


    if (this.content.resource_id) {
      this.blockTitle = this.content.resource.title;
      this.blockId = this.content.resource.id;
    }
    
    this.$on('styler::updated', function() {
      this.saveItem();
      return false;
    });

    this.$on('confirm::confirmed', function() {
      this.removeItem();
      return false;
    });
    
    this.$on('lister::selected', function(data) {
      this.content.resource_id = data.id;     
      this.blockTitle = data.title;
      this.blockId = data.id; 
      this.saveItem();
      this.modalVisible = false; 
      return false;
    }); 

    this.$on('content::save', () => {
      console.log('content save')
      this.saveItem();
      return false;
    }); 

    this.$on('content::save::new', () => {
      console.log('content save new')
      if (this.content.new) this.saveItem();
      return false;
    }); 
  }
}
</script>