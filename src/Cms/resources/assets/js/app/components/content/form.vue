<template>
  <div class="card">
    <content-header type="Form"></content-header>
    <confirm v-on:confirm="removeItem"></confirm> 
    <selector :parent-id="content.id" :options="selectorOptions"></selector>

    <div class="card-block" v-if="visible.main">
      <h5 v-if="formTitle != ''">
         {{ formTitle }}
      </h5>
      
      <div>
        <a class="btn btn-primary" @click="openSelector">Select Form</a>
        <a v-if="formTitle != ''" class="btn btn-primary" v-link="{ name: 'form-edit', params: { id: formId }}">Edit Form</a>
        <a v-if="formTitle != ''" class="btn btn-primary" v-link="{ name: 'formsubmission-index', params: { id: formId }}">View Submissions</a>
      </div>

      <styling :content="content"></styling>

    </div>
  </div>
</template>

<script>
import styling from './_styling.vue';
import selector from '../form/selector.vue';
import contentHeader from './_header.vue';

export default {

  props: ['content'],

  components: {
    contentHeader,
    styling,
    selector
  },

  data() {
    return {
      resource: null,
      id: null,
      selectorOptions: {
        allowUpload: true,
        parentType: 'form'
      },
      formTitle: '',
      formId: '',
      visible: {
        main: true
      }
    }
  },

  methods: {

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

    getResource() {
      this.$http.get('/api/v1/form/' + this.content.resource_id).then((response) => {
        this.formTitle = response.data.title;
        this.formId = response.data.id;
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
      this.getResource();
    }

    this.$on('confirm::confirmed',() => {
      this.removeItem();
      return false;
    });

    this.$on('lister::selected', (data) => {
      this.content.resource_id = data.id;
      this.getResource();
      this.saveItem();
      $('#modal-selector-' + this.content.id).modal('hide'); 
      return false;
    });

    this.$on('styler::updated', function() {
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