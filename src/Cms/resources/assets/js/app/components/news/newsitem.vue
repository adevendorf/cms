<template>
<div class="card">

  <div class="card-header">
    <span v-bind:class="'text-' + (content.active ? 'success' : 'default')">
      <span v-if="content.active"><i class="fa fa-check"></i></span>
      <span v-if="!content.active"><i class="fa fa-ban"></i></span>
    </span>
    &nbsp; {{ content.custom_headline ? content.headline : content.page.title }} &nbsp;
    <div class="pull-right">
      <span v-if="content.custom_headline"><i class="fa fa-file-text"></i></span>
      <span v-if="content.custom_synopsis"><i class="fa fa-file-text-o"></i></span>
      <span v-if="content.custom_url"><i class="fa fa-link"></i></span>
      <span v-if="content.custom_image"><i class="fa  fa-photo"></i></span>
      <span v-if="content.new_window"><i class="fa fa-external-link"></i></span>

      <a class="btn btn-xs btn-light" @click="toggleVisible"><i class="fa fa-unsorted"></i></a>
      <a class="btn btn-xs btn-light" @click="confirmRemoval"><i class="fa fa-times"></i></a>
    </div>
  </div>  
  <div class="card-block" v-if="content.id && visible.main"> 
    <div class="row">

      <div class="col-sm-2">
        <div class="checkbox">
          <label>
            <input type="checkbox" v-model="content.active" @change="saveItem"> Active
          </label>
        </div> 
        <hr />
      </div>

      <div class="col-sm-10">
        <p><strong>{{ content.custom_headline ? content.headline : content.page.title }}</strong></p>
        <p class="small"><strong>Synopsis</strong>  {{ content.custom_synopsis ? content.synopsis : content.page.synopsis }}</p>
        <p class="small"><strong>URL</strong> {{ content.custom_url ? content.redirect_url : content.page.slug }}</p>
      </div>          

    </div>

    <div class="row">

      <hr />

      <div class="col-sm-2">
        <div class="checkbox">
          <label>
            <input type="checkbox" v-model="content.custom_headline" @change="saveItem"> Headline
          </label>
        </div> 
      </div>

      <div class="col-sm-2">
        <div class="checkbox">
          <label>
            <input type="checkbox" v-model="content.custom_synopsis" @change="saveItem"> Synopsis
          </label>
        </div> 
      </div>

      <div class="col-sm-2">
        <div class="checkbox">
          <label>
            <input type="checkbox" v-model="content.custom_url" @change="saveItem"> URL
          </label>
        </div> 
      </div>

      <div class="col-sm-2">
        <div class="checkbox">
          <label>
            <input type="checkbox" v-model="content.custom_image" @change="saveItem"> Image
          </label>
        </div> 
      </div>  

      <div class="col-sm-2">
        <div class="checkbox">
          <label>
            <input type="checkbox" v-model="content.new_window" @change="saveItem"> Open in New Window
          </label>
        </div>  
      </div>   
      </div>
      <div class="row">     

        <div class="col-sm-12" v-if="content.custom_headline">      
          <div class="form-group">
            <label>Headline</label>  
            <input class="form-control" type="text" v-model="content.headline" placeholder="{{ content.page.title }}" @blur="saveItem" />
          </div>
        </div>
      
        <div class="col-sm-12" v-if="content.custom_synopsis">      
          <div class="form-group">
            <label>Synopsis</label>  
            <textarea class="form-control" v-model="content.synopsis" placeholder="{{ content.page.synopsis }}" @blur="saveItem"></textarea>
          </div>
        </div>
       
        <div class="col-sm-12" v-if="content.custom_url">      
          <div class="form-group">
            <label>URL</label>  
            <input class="form-control" type="text" v-model="content.redirect_url" placeholder="{{ content.page.slug }}" @blur="saveItem" />
          </div>
        </div>      

        <div class="col-sm-12" v-if="content.custom_image">
          <div v-if="content.custom_image"> 
            <controller :parent.sync="content"></controller>
          </div>
        </div> 
    </div>


    
  </div> 
  
  <confirm v-on:confirm="removeItem"></confirm>

</div>
</template>

<script>
import controller from '../image/controller.vue';


export default {
  props: ['content'],

  components: {
    controller
  },

  data() {
    return {
      id: null,
      resource: null,
      visible: {
        main: true,
        custom: false
      }
    }
  },

  methods: {
    toggleVisible() {
      this.visible.main = !this.visible.main;
    },
    toggleCustom() {
      this.visible.custom = !this.visible.custom;
    },

    // sendField(name) {
    //   this.resource.update({id: this.content.id}, {field: name, data: this.content[name]}).then((response) => {
    //     // this.$dispatch('field::updated', this.content.id)
    //   });
    // },

    saveItem(content) {  
      this.resource.update({id: this.content.id}, this.content).then((response) => {
        this.content = response.data;
      });
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
    'confirm::confirmed'() {
      this.removeItem();
      return false;
    },

    'image::set'() {
      this.saveItem();
      return false;
    },

    'feed::expandAll'() {
      this.visible.main = true;
      return false;
    },
    'feed::hideAll'() {
      this.visible.main = false;
      return false;
    }
  },

  ready() {
    var resource = this.$resource('newsitem{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.id);

    if (this.content.image_id) this.customImage = true;
  }
}
</script>