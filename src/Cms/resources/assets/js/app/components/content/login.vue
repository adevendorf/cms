<template>
  <div class="card">
    <content-header type="User Log In Form"></content-header>

    <div class="card-block" v-if="visible.main">   
      <div v-if="sectionTitle != ''">
        <h5>User Log In Form</h5>        
      </div>

      <styling :content="content"></styling>
    </div>
  </div>
</template>

<script>
import styling from './_styling.vue';
import contentHeader from './_header.vue';

export default {

  props: ['content'],

  components: {
    contentHeader,
    styling,
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

    toggleStyling() {
      this.showStyling = !this.showStyling;
    },

    openSelector() {
      this.$broadcast('selector::open');
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
    },
    'blocker::hideAll'() {
      this.visible.main = false;
    }
  },

  ready() {

    var resource = this.$resource('content{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.id);
   

    this.$on('styler::updated', function() {
      this.saveItem();
      return false;
    });

    this.$on('confirm::confirmed', function() {
      this.removeItem();
      return false;
    });


    this.$on('content::save', () => {
      this.saveItem();
      return false;
    }); 

    this.$on('content::save::new', () => {
      if (this.content.new) this.saveItem();
      return false;
    }); 
  }
}
</script>