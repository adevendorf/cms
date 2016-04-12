<template>
  <div class="card">
    <content-header type="News Feed"></content-header>
    <!-- <selector :parent-id="content.id" :options="selectorOptions"></selector> -->
    <confirm v-on:confirm="removeItem"></confirm>

    <div class="card-block" v-if="visible.main">   
      <div v-if="sectionTitle != ''">
        <h5>{{ sectionTitle }}</h5>        
      </div>
      
      <div>
        <a class="btn btn-primary" @click="openSelector">Select Feed</a>    
        <a v-if="sectionTitle != ''" class="btn btn-primary" v-link="{ name: 'newsfeed-edit', params: { id: sectionId }}">Edit Feed</a>  
      </div>

      <styling :content="content"></styling>

    </div>
  </div>
</template>

<script>
import styling from './_styling.vue';
// import selector from '../feed/selector.vue';
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
      selectorOptions: {
        parentType: 'section'
      },
      sectionTitle: '',
      sectionId: '',
      new: false,
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


    if (this.content.data) {
      var dataVal = JSON.parse(this.content.data)
      this.sectionTitle = dataVal.title;
      this.sectionId = dataVal.id;
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
      this.content.data = JSON.stringify({
        id: data.id,
        title: data.title
      });
      this.sectionTitle = data.title;
      this.sectionId = data.id; 
      this.saveItem();
      $('#modal-selector-' + this.id).modal('hide'); 
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