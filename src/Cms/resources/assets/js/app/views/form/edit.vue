<template>

<div class="content">
  <div class="row">
    <div class="col-md-12">

      <div class="card card-block"> 
          <span v-if="item" class="row">

            <div class="col-sm-4">
              <div class="form-group">
                <label for="type" class="control-label">Title</label>
                <input class="form-control" name="type" type="text" v-model="item.title" @blur="saveItem" />
              </div>

              <div class="form-group">
                <label for="type" class="control-label">Final Button Text</label>
                <input class="form-control" name="type" type="text" v-model="item.submit_text" @blur="saveItem" />
              </div>
            </div>

            <div class="col-sm-3">
              <label class="control-label">Data Storage</label>

              <div class="checkbox">
                <label>
                  <input type="checkbox" name="action_email" v-model="item.send_email" value="1" @change=" saveItem"> Send Email
                </label>
              </div>

              <div class="form-group" v-if="item.send_email">
                <input class="form-control" name="type" type="text" v-model="item.email_to" @blur="saveItem" placeholder="email@domain.com" />
              </div> 


              <div class="checkbox">
                <label>
                  <input type="checkbox" name="save_data" v-model="item.save_data" value="1" @change=" saveItem"> Save Data
                </label>
              </div>  

              <div class="form-group" v-if="item.save_data">
                <select v-model="item.save_to" class="form-control" @change="saveItem">
                  <option value="database">Database</option>
                  <option value="google_docs">Google Docs</option>
                </select>
              </div>  
            </div>

            <div class="col-sm-4">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="ajax" v-model="item.ajax" value="1" @change="saveItem"> AJAX Submit
                </label>
              </div>  

              <div class="form-group" v-if="item.ajax">
                  <div>                
                    
                    <div v-if="item.redirect_page && item.redirect_page.id">
                      {{item.redirect_page.title}}
                      &nbsp;
                      <a v-link="{ name: 'block-edit', params: { id: item.redirect_page.id }}">View Block <i class="fa fa-caret-right"></i></a>
                    </div> 

                    <button class="btn btn-primary" @click="openSelector">Select Content</button>
                  </div>
                </div>  

              <div v-if="!item.ajax">

                <label for="type" class="control-label">Redirect To</label>

                <div class="radio">
                  <label>
                    <input type="radio" v-model="item.redirect_to_page" value="1" @change="saveItem"> Internal Page
                  </label>
                  &nbsp; 
                  <label>
                    <input type="radio" v-model="item.redirect_to_page" value="0" @change="saveItem"> URL
                  </label>              
                </div>  

                <div class="form-group" v-if="item.redirect_to_page == 1">
                  <div>                
                    <div v-if="item.redirect_page && item.redirect_page.id">
                      {{item.redirect_page.title}}
                      &nbsp;
                      <a v-link="{ name: 'page-edit', params: { id: item.redirect_page.id }}">View Page <i class="fa fa-caret-right"></i></a>                      
                    </div> 
                    <button class="btn btn-primary" @click="openSelector">Select Page</button>
                  </div>
                </div>  

                <div class="form-group" v-if="item.redirect_to_page == 0">
                  <div>                
                    <input class="form-control" type="text" v-model="item.redirect_url" placeholder="http://" @blur="saveItem" />
                  </div>
                </div> 

                <selector :parent-id="item.id" :options="selectorOptions"></selector>
              </div>

            </div>             
          </span>
      </div>
    </div>

    <div class="col-md-12">
      <steps :form.sync="item"></steps>
    </div>
  </div>
</div>

</template>

<script>
import steps from '../../components/formsteps/steps.vue';
import selector from '../../components/page/selector.vue';

export default {
  data() {
    return{
      resource: null,
      id: null,
      item: null,
      loadingNewContent: false,
      dropSpotsActive: false,
      validTarget: false,
      selectorOptions: {
        parentType: 'form'
      }
    }
  },

  components: {
    steps,
    selector
  },

  methods: {

    openSelector() {
      this.$broadcast('selector::open');
    },


    getItem: function() {
      this.resource.get({id: this.id}).then((response) => {
        this.$set('item', response.data);

      }); 

    },

    saveItem() {
      this.resource.update({id: this.id}, this.item);
    }, 

    addContent: function(type) {

      this.loadingNewContent = true;

      var options = {
        form_id: this.item.id,
        question_type: type
      };

      this.$http.post('/api/v1/formfield/', options).then((response) => {
        this.item.fields.push(response.data);
        this.loadingNewContent = false;
      });
    },
  },

  ready() {
    var resource = this.$resource('form{/id}');
    this.$set('resource', resource);
    this.$set('id', this.$route.params.id);
    this.getItem();

    this.$on('lister::selected', function(data) {   
      this.item.redirect_page_id = data.id;
      if (!this.item.redirect_page) this.item.redirect_page = {};
      this.item.redirect_page.id = data.id;
      this.item.redirect_page.title = data.title;
      this.saveItem();
      $('#modal-selector-page' + this.item.id).modal('hide'); 
      return false;
    });

    this.$on('form::field::remove', (id) => {
      for (var i = 0 ; i < this.item.fields.length; i++) {
        if (this.item.fields[i].id == id) {
          this.item.fields.splice(i, 1);
          break;
        } 
      }

      return false;
    }); 
  }
}
</script>