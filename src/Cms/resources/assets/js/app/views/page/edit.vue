<template>
    <div v-if="item"  class="content">

      <side-panel
        header="true"
        :show.sync="sidepanel.show"
        size="large"
        side="left"
        >
        <div slot="header">{{ sidepanel.header }}</div>
        <div slot="body">
          <component :is="sidepanel.component"
            :page.sync="item"
            >
          </component>
        </div>
      </side-panel>
      
      <div class="row">
        <div class="col-sm-12">
          <button class="btn btn-primary" @click="showSidePanel('settings', 'Settings')">Settings</button>
          <button class="btn btn-primary" @click="showSidePanel('visual', 'Visual')">Visual</button>
          <button class="btn btn-primary" @click="showSidePanel('social', 'Social')">Social</button>
          <button class="btn btn-primary" @click="showSidePanel('seo', 'SEO')">SEO</button>
          <button class="btn btn-primary" @click="showSidePanel('security', 'Security')">Security</button>

          <div class="btn-group pull-xs-right" role="group">
            <button v-if="item.status != 'published'"class="btn btn-success" @click="publish"><i class="fa fa-gavel"></i> Publish</button>
            <button v-if="item.status == 'published'"class="btn btn-success" @click="publish"><i class="fa fa-check"></i> Update</button>
            <button v-if="item.status != 'hidden'" class="btn btn-default" @click="unpublish"><i class="fa fa-eye-slash"></i> Hide</button>
          </div>
          <hr />

        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">

          <h4>
            <span v-if="item.status == 'published'" class="label label-success">{{ (item.status).toUpperCase().substring(0,1) }}</span>
            <span v-if="item.status == 'scheduled'" class="label label-warning">{{ (item.status).toUpperCase().substring(0,1) }}</span>
            <span v-if="item.status == 'hidden'" class="label label-default">{{ (item.status).toUpperCase().substring(0,1) }}</span>
            {{ item.title }}              
          </h4>   

          <hr />

        </div>   
      </div>

      <div class="row">
        <div class="col-sm-12">
          <sections :page.sync="item"></sections>
        </div>
      </div>

    </div>    
  </div>

</template>

<script>
import moment from 'moment';
import settings from './components/settings.vue'; //./page-main.vue';
import seo from './components/seo.vue';
import security from './components/security.vue';
import social from './components/social.vue';
import visual from './components/visual.vue';
import news from './components/news.vue';

import sections from '../../components/blocksections/sections.vue';


export default {

  data() {
    return {
      id: null,
      resource: null,
      blockResource: null,
      item: null,
      loadingNewContent: false,
      tabs: {
        active: 'page'
      },
      sidepanel: {
        show: false,
        header: '',
        component: ''
      }
    }
  },


  components: {
    settings,
    seo,
    security,
    social,
    visual,
    sections,
    news,
  },

  methods: {

    showSidePanel(componentType, header) {
      this.sidepanel.header = header
      this.sidepanel.component = componentType;
      this.sidepanel.show = true;
    },

    changeTab(tab) {
      this.tabs.active = tab;
    },

    publish() {  
      this.$http.put('/api/v1/page/' + this.id + '/publish').then((response) => {
        this.item.status = 'published';
        this.item.scheduled_publish = '';
      });
    },

    unpublish() { 
      this.$http.put('/api/v1/page/' + this.id + '/hide').then((response) => {
        this.item.status = 'hidden';
      });
    },   


    getItem() {
      this.resource.get({id: this.id}).then((response) => {
        this.item = response.data;
      });      
    },
  
    saveItem() {  
      this.resource.update({id: this.id}, this.item).then((response) => {

        _.each(response.data, (value, key) => {
          if (key != 'templates' && key != 'sectons' && key != 'blocks') this.item[key] = value;
        });

      });
    }
  },

  events: {
    'parent::save'() {
      this.saveItem();
      return false;
    }
  },


  ready() {
    var resource = this.$resource('page{/id}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItem();

    var blockResource = this.$resource('block{/id}');
    this.$set('blockResource', blockResource);
  }  
}
</script>