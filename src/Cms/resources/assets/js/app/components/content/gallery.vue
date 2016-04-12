<template>
  <div class="card">
    <content-header type="Gallery"></content-header>
    <confirm v-on:confirm="removeItem"></confirm>
    <selector :parent-id="content.id" :options="selectorOptions"></selector>

    <div class="card-block" v-if="visible.main">
      <div v-if="galleryTitle">
        <h5>{{ galleryTitle }}</h5>
      </div> 

      <div>
        <a class="btn btn-primary" @click="openSelector">Select Gallery</a>
        <a v-if="galleryTitle" class="btn btn-primary" v-link="{ name: 'gallery-edit', params: { id: content.resource_id }}">Edit Gallery</a>
      </div>
     
      <styling :content="content"></styling>

    </div>   
  </div>
</template>

<script>
import styling from './_styling.vue';
import selector from '../gallery/selector.vue';
import contentHeader from './_header.vue';

export default {

  props: ['content'],

  components: {
    styling,
    selector,
    contentHeader
  },

  data() {
    return {
      resource: null,
      id: null,
      selectorOptions: {
        allowUpload: true,
        parentType: 'gallery'
      },
      galleryId: null,
      galleryTitle: null,
      images: [],
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

    saveItem(item) {
      if (item) {
        this.resource.update({id: this.id}, item);
        return;
      } 
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

    // getResource() {
    //   this.$http.get('/api/v1/gallery/' + this.content.resource_id).then((response) => {
    //     this.galleryTitle = response.data.title;
    //     this.galleryId = response.data.id;
    //     // this.images = response.data.content;
    //   });
    // }

  },

  events: {
    'header::toggleStyler'() {
      this.$broadcast('styler::toggle');
      return false;
    }, 
    'header::toggleVisible'() {
      this.visible.main = !this.visible.main;
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

    if (this.content.resource) {
      this.galleryTitle = this.content.resource.title;
      this.galleryId = this.content.resource.id;
    } 
    // else if (this.content.resource_id) {
    //   this.getResource();
    // }

    this.$on('styler::updated', () => {
      this.saveItem();
      return false;
    });

    this.$on('confirm::confirmed', () => {
      this.removeItem();
      return false;
    });
    
    this.$on('lister::selected', function(data) {
      this.content.resource_id = data.id;     
      // this.getResource();
      this.galleryTitle = data.title;
      this.galleryId = data.id;
      this.saveItem();
      $('#modal-selector-' + this.id).modal('hide'); 
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