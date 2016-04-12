<template>
  <div class="card">
    <content-header type="Image"></content-header>
    <confirm v-on:confirm="removeItem"></confirm>

    <div class="card-block" v-if="visible.main">
      <div class="row">
        <div class="col-sm-12" v-if="content">

          <controller :parent.sync="content" details="true"></controller>

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

    this.checkIfNeededToSave();

    
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