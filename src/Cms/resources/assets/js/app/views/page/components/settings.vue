<template>
<!-- begin main -->
<div class="row">
  <div class="col-md-12">
    
    <div class="form-group">
      <label class="control-label">Title</label>
      <input class="form-control" type="text" v-model="page.title" @blur="saveParent">
    </div>

    <div class="form-group">
      <label>URL</label>
      <input type="text" class="form-control" v-model="page.route.url" @blur="updateUrl" />
    </div>

    <div class="form-group" v-if="page.type != 'blog'">
      <label class="control-label">Section</label>
        <select class="form-control" v-model="page.section_id" @change="saveParent">
          <option v-for="option in sectionOptions" v-bind:value="option.value">
            {{ option.text }}
          </option>
        </select>   
    </div>

    <div class="form-group">
      <label class="control-label">Featured Image</label>
       <div v-if="page">
           <component is="controller" :parent.sync="page"></component>
        </div>  
    </div>

    <div class="row">
      <div class="col-sm-12">

        <label class="form-label">Scheduled Publish</label>
        <div>
          <span v-if="page.scheduled_publish">
            {{ publishMoment }} <button class="btn btn-danger btn-xs" @click="reset('scheduled_publish')"><i class="fa fa-times"></i></button>
          </span>
        </div>
        <datetimer :model.sync="page.scheduled_publish" heading="Schedule Publishing" time-format="YYYY-MM-DD HH:mm:ss" v-on:save="schedulePost"></datetimer>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <label class="form-label">Scheduled Unpublish</label>
        <div>
          <span v-if="page.scheduled_unpublish">
            {{ unpublishMoment }} <button class="btn btn-danger btn-xs" @click="reset('scheduled_unpublish')"><i class="fa fa-times"></i></button>
          </span>                    
        </div>  
        <datetimer :model.sync="page.scheduled_unpublish" heading="Schedule Un-Publishing" time-format="YYYY-MM-DD HH:mm:ss" v-on:save="scheduleUnpost"></datetimer>
      </div>
    </div>

  </div>
</div>
<!-- end main -->
</template>

<script>
import controller from '../../../components/image/controller.vue';

export default {
  props: ['page'],

  components: {
    controller,
  },

  data() {
    return {
      sectionOptions: []
    }
  },

  computed: {
    unpublishMoment() {
      if (!this.page.scheduled_unpublish || this.page.scheduled_unpublish == '') return '';
      let m = moment(this.page.scheduled_unpublish);
      return m.format('ddd MMM Do h:mm A');
    },
    publishMoment() {
      if (!this.page.scheduled_publish || this.page.scheduled_publish == '') return '';
      let m = moment(this.page.scheduled_publish);
      return m.format('ddd MMM Do h:mm A');
    }
  },  

  methods: {
    reset(el) {
      this.page[el] = null;
      this.saveParent();
    },
    
    updateUrl() {
      if (!this.routeResource) {
        this.routeResource = this.$resource('route{/id}');
      }

      let data = {
        url: this.page.route.url
      }

      this.routeResource.update({id: this.page.route.id}, data).then((response) => {
        this.page.route.url = response.data.url;
      });
    },

    saveParent() {
      this.$dispatch('parent::save');
    },

    setOptions(data) {
      this.sectionOptions = data.map(function(item) {
        return {
          text: item.name,
          value: item.id
        };
      });

      if (!this.page.section_id) this.page.section_id = '';
    },  

    schedulePost() {
      this.schedule();
    },

    scheduleUnpost() {
      this.schedule();
    },


    schedule() {    
      this.$http.put('/api/v1/page/' + this.page.id + '/schedule', {
        scheduled_publish: this.page.scheduled_publish,
        scheduled_unpublish: this.page.scheduled_unpublish
      }).then((response) => {
        this.page.status = 'scheduled';
        this.saveParent();
      });
    },

  },

  ready() {
    this.setOptions(this.page.sections);

    this.$on('image::set', () => {
      this.saveParent();
      return false;
    });

  }
}
</script>