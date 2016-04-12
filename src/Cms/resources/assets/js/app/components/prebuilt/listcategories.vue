<template>
  <div class="card">

    <div class="card-header">
      List Categories
      <div class="pull-right">
        <a class="btn btn-xs btn-light" @click="toggleStyling"><i class="fa fa-cog"></i></a>
        &nbsp;
        <a class="btn btn-xs btn-light" @click="confirmRemoval"><i class="fa fa-times"></i></a>
      </div>
    </div>
    <div class="card-block">   

      <div class="alert alert-default" role="alert">
        <div class="row"> 
          <div class="col-md-12">
            <div class="form-group">
              <label for="styling" class="control-label">Number to List</label>
              <input class="form-control" name="count" type="text" v-model="count" @blur="saveItem">
            </div>   
          </div>
          <div class="col-md-4"></div>
        </div>
      </div>

      <div v-if="showStyling">
        <styling :content="content"></styling>
      </div>

    </div>

    <confirm v-on:confirm="removeItem"></confirm>

  </div>
</template>

<script>
import styling from '../content/_styling.vue';

export default {
  props: ['content'],

  data() {
    return {
      showStyling: false,
      count: '',
      resource: null,
      id: null
    }
  },

  components: {
    styling
  },

  methods: {

    toggleStyling() {
      this.showStyling = !this.showStyling;
    },

    buildJson() {

      var obj = {};
      if (this.$data.count != '') obj.count = this.$data.count;

      var stringed = JSON.stringify(obj);

      this.content.data = stringed == "{}" ? "" : stringed;

    },

    loadJson() {

      var json = this.content.data;

      if (json && json.length) {
        try {
          json = JSON.parse(json);
        } catch(e) {
          console.log('data value is not valid');
        }
        if (json.count) this.$data.count = json.count;
      }
    },


    saveItem() {

      this.buildJson();

      if (!this.content.type) return; 

      if (!this.content.id) {
        this.resource.save(this.content).then((response) => {
          this.content = response.data
        });
        return;
      }  

      this.resource.update({id: this.id}, this.content);
    }, 

    // saveThis: function(e) {
    //   if (e) e.preventDefault();

    //   this.buildJson();

    //   this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');

    //   this.$http.post('/api/v1/content/' + this.content.id, this.content, function(data, status, request) {
    //    }).error(function(data, status, request) {
    //       System.handleError(status);
    //   });
    // }, 

    confirmRemoval() {
      this.$broadcast('confirm::ask');
    },

    removeItem() {
      this.resource.delete({id: this.id}).then((response) => {
        this.$dispatch('block::content::remove', this.id);
      });
    },

    // deleteAction: function() {
    //   this.$http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');
     
    //   this.$http.delete('/api/v1/content/' + this.content.id, {}, function(data, status, request) {
    //       this.$dispatch('block-updated');
    //    }).error(function(data, status, request) {
    //       System.handleError(status);
    //   });
    // },

  },


  ready() {

    var resource = this.$resource('content{/id}');
    this.$set('resource', resource);
    this.$set('id', this.content.id);

    this.loadJson();

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

  }
}
</script>