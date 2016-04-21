<template>
  <div class="card">
    <content-header type="Icon Box"></content-header>

    <div class="card-block" v-if="visible.main">
      <div class="row">
        <div class="col-sm-12" v-if="content">

          <controller :parent.sync="content" details="true"></controller>

          <hr />

          <textarea name="data" v-bind:id="'textarea_' + content.id" v-model="content.data" style="display: none"></textarea>

        </div> 
      </div>

      <styling :content="content"></styling>       
    </div>
  </div>
</template>

<script>
import styling from './_styling.vue';
import controller from '../image/controller.vue';
import contentHeader from './_header.vue';

export default {

  props: ['content'],

  components: {
    contentHeader,
    styling,
    controller
  },

  data() {
    return {
      resource: null,
      id: null,
      visible: {
        main: true
      }
    }
  },

  methods: {
    startEditing() {
      this.editing = true;
      setTimeout(this.createWYSIWYG, 100);
    },

    createWYSIWYG() {
      CKEDITOR.replace('textarea_' + this.id, {
      });

      CKEDITOR.instances['textarea_' + this.id].on('blur', () => {
         this.content.data = CKEDITOR.instances['textarea_' + this.id].getData();
         this.saveItem();
      });

    },

    killWYSIWYG() {
      CKEDITOR.instances['textarea_' + this.id].destroy();
    },

    stopEditing(saveAfter) {     
      this.content.data = CKEDITOR.instances['textarea_' + this.id].getData();
      this.editing = false;      
      this.saveItem();
      setTimeout(this.killWYSIWYG, 0);
    },

    saveItem(item) {
      if (item) {
        this.resource.update({id: this.content.id}, item);
        return;
      } 
      if (!this.content.id) {
        this.resource.save(this.content).then((response) => {
          this.content = response.data
        });
        return;
      }      
      this.resource.update({id: this.content.id}, this.content).then((response) => {
        this.content = response.data;
      }); 
    },


    removeItem() {
      this.resource.delete({id: this.id}).then((response) => {
        this.$dispatch('block::content::remove', this.id);
      });
    },

    updateCache() {
      this.cachebust = Date.parse(new Date());
    },

    checkIfNeededToSave() {
      if (!this.content.id) {
        this.saveItem();
      }
    }

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
    'header::removeContent'() {    
      this.removeItem();
      return false;
    },
    'blocker::expandAll'() {
      this.visible.main = true;
      this.startEditing();
      return false;
    },
    'blocker::hideAll'() {
      this.stopEditing();
      setTimeout(() => { this.visible.main = false; }, 100)
      return false;
    },
  },


  ready() {
    var resource = this.$resource('content{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.id);

    this.checkIfNeededToSave();

    setTimeout(this.startEditing, 100);

    
    this.$on('styler::updated', () => {
      this.saveItem();
      return false;
    });

    this.$on('confirm::confirmed', () => {
      this.removeItem();
      return false;
    });

    this.$on('image::set', () => {
      this.saveItem();
      return false;
    });



    this.$on('content::save', (item) => {
      if (item && item.id == this.content.id) {
        this.saveItem(item);
        return false;
      }
      this.saveItem();
      return false;
    });  

  }
}
</script>